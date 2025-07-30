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

        // Create image resource from uploaded file
        $sourceImage = $this->createImageFromFile($file);
        
        if (!$sourceImage) {
            throw new \Exception('Unable to create image from uploaded file. File mungkin rusak atau format tidak valid.');
        }
        
        // Get original dimensions
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);
        
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
        
        // Enable alpha blending for PNG images with transparency
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
        $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
        imagefill($resizedImage, 0, 0, $transparent);
        imagealphablending($resizedImage, true);
        
        // Copy and resize the cropped portion to the new image
        imagecopyresampled(
            $resizedImage, $sourceImage,
            0, 0, $cropX, $cropY,
            $width, $height, $cropWidth, $cropHeight
        );
        
        // Save the resized image
        $path = storage_path('app/public/profile_photos/' . $filename);
        
        // Ensure directory exists
        $directory = dirname($path);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
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
            throw new \Exception('Failed to save resized image');
        }
        
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
        
        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($tempPath);
            case 'image/png':
                return imagecreatefrompng($tempPath);
            case 'image/gif':
                return imagecreatefromgif($tempPath);
            default:
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
            return Storage::delete($filePath);
        } catch (\Exception $e) {
            // Log error but don't throw exception
            \Log::error('Failed to delete profile photo: ' . $e->getMessage());
            return false;
        }
    }
}