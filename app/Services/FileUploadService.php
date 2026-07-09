<?php

namespace App\Services;

use App\Traits\DocumentTraits;
use Illuminate\Http\UploadedFile;

class FileUploadService
{
    use DocumentTraits;

    public function upload(string $directory, string $fieldName, UploadedFile $files): ?string
    {
        return $this->uploadFile([
            'directory' => $directory,
            'fieldName' => $fieldName,
            'files' => $files,
        ]);
    }

    public function update(string $directory, string $fieldName, UploadedFile $files, ?string $oldFilePath = null): ?string
    {
        return $this->updateFile([
            'directory' => $directory,
            'fieldName' => $fieldName,
            'files' => $files,
        ], $oldFilePath);
    }

    public function download(string $file_path, ?string $custom_file_name = null)
    {
        return $this->downloadFile($file_path, $custom_file_name);
    }

    public function delete(string $file_path): string
    {
        return $this->deleteFile($file_path);
    }
}
