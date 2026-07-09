<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait DocumentTraits
{
    public function uploadFile(array $payload): ?string
    {
        $outputFileName = null;
        if (isset($payload['files']) && $payload['files']) {

            $original_name = mb_convert_encoding($payload['files']->getClientOriginalName(), 'UTF-8', 'UTF-8');

            // Create directory path based on the passed strings
            $directory = trim($payload['directory'].'/'.$payload['fieldName'], '/');
            $filename = uniqid().'_'.preg_replace('/\s+/', '_', $original_name);

            $path = $payload['files']->storeAs($directory, $filename, 'public');

            $publicPath = Storage::url($path);

            $outputFileName = parse_url($publicPath, PHP_URL_PATH) ?: $publicPath;
        }

        return $outputFileName;
    }

    public function updateFile(array $payload, ?string $old_file_path = null): ?string
    {
        if ($old_file_path) {
            try {
                $this->deleteFile($old_file_path);
            } catch (\Exception $e) {
                Log::warning('Could not delete old file: '.$e->getMessage());
            }
        }

        return $this->uploadFile($payload);
    }

    public function downloadFile(string $file_path, ?string $custom_file_name = null)
    {
        if (! str_contains($file_path, '/storage/')) {
            throw new \Exception('Incorrect File Path!');
        }

        $relative_path = Str::after($file_path, '/storage/');

        if (! Storage::disk('public')->exists($relative_path)) {
            throw new \Exception('File does not exist on the server!');
        }

        $absolute_path = Storage::disk('public')->path($relative_path);

        return response()->download($absolute_path, $custom_file_name);
    }

    public function deleteFile(string $file_path): string
    {
        if (! str_contains($file_path, '/storage/')) {
            throw new \Exception('Incorrect File Path!');
        }
        $relative_path = Str::after($file_path, '/storage/');
        if (! Storage::disk('public')->exists($relative_path)) {
            throw new \Exception('Incorrect File Path!');
        }
        Storage::disk('public')->delete($relative_path);

        return 'File Deleted Successfully';
    }
}
