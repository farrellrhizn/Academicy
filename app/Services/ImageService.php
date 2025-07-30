<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
            throw new \Exception('File terlalu besar. Maksimal 2MB.');
        }

        // Validate mime type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
        }

        // Ensure GD extension is available
        if (!extension_loaded('gd')) {
            throw new \Exception('GD extension tidak tersedia. Tidak dapat memproses gambar.');
        }

        // Create image resource from uploaded file
        $sourceImage = $this->createImageFromFile($file);
        
        if (!$sourceImage) {
            throw new \Exception('Tidak dapat membuat gambar dari file yang diupload. File mungkin rusak atau format tidak valid.');
        }
        
        // Get original dimensions
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);
        
        if ($originalWidth === false || $originalHeight === false) {
            imagedestroy($sourceImage);
            throw new \Exception('Tidak dapat membaca dimensi gambar.');
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
            throw new \Exception('Tidak dapat membuat gambar baru untuk resize.');
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
            throw new \Exception('Gagal melakukan resize gambar.');
        }
        
        // Save the resized image
        $path = storage_path('app/public/profile_photos/' . $filename);
        
        // Ensure directory exists with proper permissions
        $directory = dirname($path);
        if (!file_exists($directory)) {
            if (!mkdir($directory, 0755, true)) {
                imagedestroy($sourceImage);
                imagedestroy($resizedImage);
                throw new \Exception('Tidak dapat membuat direktori untuk menyimpan gambar.');
            }
        }
        
        // Make sure directory is writable
        if (!is_writable($directory)) {
            if (!chmod($directory, 0755)) {
                imagedestroy($sourceImage);
                imagedestroy($resizedImage);
                throw new \Exception('Direktori tidak dapat ditulis. Periksa permission.');
            }
        }
        
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
            throw new \Exception('Gagal menyimpan gambar yang sudah diresize.');
        }
        
        // Verify the file was actually created and has content
        if (!file_exists($path) || filesize($path) === 0) {
            throw new \Exception('File gambar tidak dapat disimpan atau file kosong.');
        }
        
        // Set proper file permissions
        chmod($path, 0644);
        
        return $filename;
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
                    $image = imagecreatefromjpeg($tempPath);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($tempPath);
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($tempPath);
                    break;
                default:
                    return false;
            }
            
            // Additional check to ensure image was created successfully
            if ($image === false) {
                return false;
            }
            
            return $image;
        } catch (\Exception $e) {
            \Log::error('Error creating image from file: ' . $e->getMessage());
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
                \Log::info('Profile photo deleted successfully: ' . $filename);
                return true;
            } else {
                \Log::warning('Failed to delete profile photo: ' . $filename);
                return false;
            }
        } catch (\Exception $e) {
            // Log error but don't throw exception
            \Log::error('Failed to delete profile photo: ' . $e->getMessage(), [
                'filename' => $filename,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}