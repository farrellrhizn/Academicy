<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class ImageService
{
    /**
     * Resize and optimize profile photo
     * 
     * @param UploadedFile $file
     * @param string $filename
     * @param int $width
     * @param int $height
     * @return string
     */
    public function resizeProfilePhoto(UploadedFile $file, string $filename, int $width = 200, int $height = 200): string
    {
        // Validate file size (max 2MB)
        if ($file->getSize() > 2048000) {
            throw new Exception('File terlalu besar. Maksimal 2MB.');
        }

        // Validate mime type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new Exception('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
        }

        // Check if GD extension is available
        if (!extension_loaded('gd')) {
            Log::warning('GD extension not available, using direct file copy');
            return $this->copyFileDirectly($file, $filename);
        }

        try {
            // Create image resource from uploaded file
            $sourceImage = $this->createImageFromFile($file);
            
            if (!$sourceImage) {
                Log::warning('Failed to create image resource, using direct file copy');
                return $this->copyFileDirectly($file, $filename);
            }
            
            // Get original dimensions
            $originalWidth = imagesx($sourceImage);
            $originalHeight = imagesy($sourceImage);
            
            if ($originalWidth === false || $originalHeight === false) {
                imagedestroy($sourceImage);
                Log::warning('Failed to get image dimensions, using direct file copy');
                return $this->copyFileDirectly($file, $filename);
            }
            
            // Calculate aspect ratio
            $aspectRatio = $originalWidth / $originalHeight;
            
            // Calculate crop dimensions to maintain aspect ratio
            if ($aspectRatio > 1) {
                // Landscape image - crop width
                $cropWidth = $originalHeight;
                $cropHeight = $originalHeight;
                $cropX = ($originalWidth - $cropWidth) / 2;
                $cropY = 0;
            } else {
                // Portrait or square image - crop height
                $cropWidth = $originalWidth;
                $cropHeight = $originalWidth;
                $cropX = 0;
                $cropY = ($originalHeight - $cropHeight) / 2;
            }
            
            // Create a new square image
            $resizedImage = imagecreatetruecolor($width, $height);
            
            if (!$resizedImage) {
                imagedestroy($sourceImage);
                Log::warning('Failed to create resized image, using direct file copy');
                return $this->copyFileDirectly($file, $filename);
            }
            
            // Enable alpha blending for PNG images with transparency
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefill($resizedImage, 0, 0, $transparent);
            imagealphablending($resizedImage, true);
            
            // Copy and resize the cropped portion to the new image
            $resizeResult = imagecopyresampled(
                $resizedImage, $sourceImage,
                0, 0, $cropX, $cropY,
                $width, $height, $cropWidth, $cropHeight
            );
            
            if (!$resizeResult) {
                imagedestroy($sourceImage);
                imagedestroy($resizedImage);
                Log::warning('Failed to resample image, using direct file copy');
                return $this->copyFileDirectly($file, $filename);
            }
            
            // Save the resized image
            $path = storage_path('app/public/profile_photos/' . $filename);
            
            // Ensure directory exists with proper permissions
            $this->ensureDirectoryExists(dirname($path));
            
            // Save based on file extension
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $success = false;
            
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $success = imagejpeg($resizedImage, $path, 85); // 85% quality
                    break;
                case 'png':
                    $success = imagepng($resizedImage, $path, 6); // Compression level 6
                    break;
                case 'gif':
                    $success = imagegif($resizedImage, $path);
                    break;
                default:
                    // Default to JPEG
                    $filename = pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
                    $path = storage_path('app/public/profile_photos/' . $filename);
                    $success = imagejpeg($resizedImage, $path, 85);
                    break;
            }
            
            // Clean up memory
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);
            
            if (!$success) {
                Log::warning('Failed to save resized image, using direct file copy');
                return $this->copyFileDirectly($file, $filename);
            }
            
            // Verify the file was actually created and has content
            if (!file_exists($path) || filesize($path) === 0) {
                Log::warning('Resized image file not created properly, using direct file copy');
                return $this->copyFileDirectly($file, $filename);
            }
            
            // Set proper file permissions
            chmod($path, 0644);
            
            Log::info('Profile photo resized successfully: ' . $filename);
            return $filename;

        } catch (Exception $e) {
            Log::error('Error during image resize: ' . $e->getMessage());
            Log::warning('Falling back to direct file copy');
            return $this->copyFileDirectly($file, $filename);
        }
    }
    
    /**
     * Copy file directly without resizing (fallback method)
     * 
     * @param UploadedFile $file
     * @param string $filename
     * @return string
     */
    private function copyFileDirectly(UploadedFile $file, string $filename): string
    {
        try {
            $directory = storage_path('app/public/profile_photos');
            $this->ensureDirectoryExists($directory);
            
            $path = $directory . '/' . $filename;
            
            // Copy the uploaded file directly
            if ($file->move(dirname($path), basename($path))) {
                chmod($path, 0644);
                Log::info('Profile photo copied directly: ' . $filename);
                return $filename;
            } else {
                throw new Exception('Failed to copy file to destination');
            }
        } catch (Exception $e) {
            Log::error('Failed to copy file directly: ' . $e->getMessage());
            throw new Exception('Gagal menyimpan file gambar: ' . $e->getMessage());
        }
    }
    
    /**
     * Ensure directory exists with proper permissions
     * 
     * @param string $directory
     * @return void
     */
    private function ensureDirectoryExists(string $directory): void
    {
        if (!file_exists($directory)) {
            if (!mkdir($directory, 0755, true)) {
                throw new Exception('Tidak dapat membuat direktori untuk menyimpan gambar.');
            }
        }
        
        // Make sure directory is writable
        if (!is_writable($directory)) {
            if (!chmod($directory, 0755)) {
                throw new Exception('Direktori tidak dapat ditulis. Periksa permission.');
            }
        }
    }
    
    /**
     * Create image resource from uploaded file
     * 
     * @param UploadedFile $file
     * @return resource|false
     */
    private function createImageFromFile(UploadedFile $file)
    {
        $mimeType = $file->getMimeType();
        $tempPath = $file->getPathname();
        
        // Verify file exists and is readable
        if (!file_exists($tempPath) || !is_readable($tempPath)) {
            return false;
        }
        
        try {
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    $image = @imagecreatefromjpeg($tempPath);
                    break;
                case 'image/png':
                    $image = @imagecreatefrompng($tempPath);
                    break;
                case 'image/gif':
                    $image = @imagecreatefromgif($tempPath);
                    break;
                default:
                    return false;
            }
            
            // Additional check to ensure image was created successfully
            if ($image === false) {
                return false;
            }
            
            return $image;
        } catch (Exception $e) {
            Log::error('Error creating image from file: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete profile photo
     * 
     * @param string|null $filename
     * @return bool
     */
    public function deleteProfilePhoto(?string $filename): bool
    {
        if (!$filename) {
            return false;
        }

        $filePath = 'public/profile_photos/' . $filename;
        
        if (!Storage::exists($filePath)) {
            // File already doesn't exist, consider it as successfully deleted
            return true;
        }
        
        try {
            $deleted = Storage::delete($filePath);
            
            // Double check that file is actually deleted
            if ($deleted && !Storage::exists($filePath)) {
                Log::info('Profile photo deleted successfully: ' . $filename);
                return true;
            } else {
                Log::warning('Failed to delete profile photo: ' . $filename);
                return false;
            }
        } catch (Exception $e) {
            // Log error but don't throw exception
            Log::error('Failed to delete profile photo: ' . $e->getMessage(), [
                'filename' => $filename,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}