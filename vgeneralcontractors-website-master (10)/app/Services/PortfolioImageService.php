<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image; // Usar el Facade de Intervention para Laravel 11+
use Illuminate\Support\Str;
use Carbon\Carbon;

class PortfolioImageService
{
    // --- Configuración ---
    private const DISK = 's3'; // Disco de almacenamiento (configurable via .env)
    private const BASE_PATH = 'portfolios'; // Carpeta base en S3
    private const MAX_DIMENSION = 1200; // Dimensión máxima (ancho o alto)
    private const QUALITY = 80; // Calidad para WebP/JPG
    private const FORMAT = 'webp'; // Formato de salida preferido ('webp' o 'jpg')

    /**
     * Guarda una imagen de portfolio optimizada en S3.
     *
     * @param UploadedFile $file El archivo subido.
     * @return string|null La ruta relativa en S3 de la imagen guardada, o null si falla.
     */
    public function storeImage(UploadedFile $file): ?string
    {
        try {
            // 1. Generate path based on date and unique name
            $now = Carbon::now();
            $directory = self::BASE_PATH . '/' . $now->year . '/' . $now->format('m') . '/' . $now->format('d');
            $filename = Str::uuid()->toString() . '.' . self::FORMAT;
            $s3Path = $directory . '/' . $filename;

            // 2. Process image with Intervention
            $image = Image::make($file);

            // 3. Resize if needed (maintaining aspect ratio)
            $image->resize(self::MAX_DIMENSION, self::MAX_DIMENSION, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // 4. Encode properly for storage
            if (self::FORMAT === 'webp' && method_exists($image, 'encode')) {
                $encodedImage = $image->encode('webp', self::QUALITY)->stream();
            } else {
                // Fallback to JPEG if WebP not supported
                $encodedImage = $image->encode('jpg', self::QUALITY)->stream();
            }

            // 5. Upload to S3
            $success = Storage::disk(self::DISK)->put($s3Path, $encodedImage);

            if (!$success) {
                throw new \Exception("Failed to upload image to S3 disk.");
            }

            // 6. Generate and return the FULL URL - This is the change
            $fullUrl = Storage::disk(self::DISK)->url($s3Path);
            
            Log::info('Portfolio image stored successfully on S3.', [
                'path' => $s3Path,
                'url' => $fullUrl
            ]);

            return $fullUrl; // Return full URL instead of path
        } catch (\Exception $e) {
            Log::error('Error storing portfolio image.', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Elimina una imagen de portfolio de S3.
     *
     * @param string|null $url The full S3 URL of the image to delete.
     * @return bool True if deleted or didn't exist, False if error.
     */
    public function deleteImage(?string $url): bool
    {
        if (empty($url)) {
            return true; // Nothing to delete
        }

        try {
            // Extract the path from the URL
            $path = $this->getPathFromUrl($url);
            
            if (Storage::disk(self::DISK)->exists($path)) {
                $deleted = Storage::disk(self::DISK)->delete($path);
                if ($deleted) {
                    Log::info('Portfolio image deleted successfully from S3.', ['url' => $url]);
                } else {
                    Log::warning('Failed to delete portfolio image from S3.', ['url' => $url]);
                }
                return $deleted;
            } else {
                Log::info('Portfolio image not found on S3, skipping deletion.', ['url' => $url]);
                return true; // Consider success if it doesn't exist
            }
        } catch (\Exception $e) {
            Log::error('Error deleting portfolio image from S3.', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Extracts the relative path from a full S3 URL.
     *
     * @param string $url The full S3 URL.
     * @return string The relative path.
     */
    private function getPathFromUrl(string $url): string
    {
        // Parse the URL
        $parsedUrl = parse_url($url);
        
        // Get the path component
        $path = $parsedUrl['path'] ?? '';
        
        // Remove leading slash
        $path = ltrim($path, '/');
        
        // Remove bucket name from path if present
        $bucketName = config('filesystems.disks.s3.bucket');
        if (!empty($bucketName)) {
            $path = preg_replace('/^' . preg_quote($bucketName, '/') . '\//', '', $path);
        }
        
        return $path;
    }

    /**
     * Obtiene la URL pública completa para una ruta de imagen.
     *
     * @param string|null $path La ruta relativa en S3.
     * @return string|null La URL completa o null.
     */
    public function getImageUrl(?string $path): ?string
    {
         if (empty($path)) {
            return null;
         }
         // Asegúrate de que tu disco S3 esté configurado para URLs públicas
         // o usa URLs temporales si es privado: Storage::disk(self::DISK)->temporaryUrl($path, now()->addMinutes(5));
         return Storage::disk(self::DISK)->url($path);
    }
}