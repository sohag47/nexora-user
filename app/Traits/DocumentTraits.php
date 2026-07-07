<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait DocumentTraits
{
    public function uploadFile($request)
    {
        // upload new file
        $outputFileName = null;
        if ($request->hasFile('files')) {

            // ready or file
            $file = $request->file('files');
            $original_name = $file->getClientOriginalName();

            // create directory path
            $directory = trim($request->path_prefix.'/'.$request->field_name, '/');
            $filename = $directory.'/'.uniqid().'_'.preg_replace('/\s+/', '_', $original_name);

            // store file
            $path = $file->storeAs($directory, $filename, 'public');

            // get public path
            $publicPath = Storage::url($path);
            $outputFileName = parse_url($publicPath, PHP_URL_PATH) ?: $publicPath;
        }

        return $outputFileName;
    }

    public function deleteFile($file_path)
    {
        $file_path_format = explode('/', $file_path);

        if (count($file_path_format) > 0 && ($file_path_format[0] === '' && $file_path_format[1] === 'storage')) {
            $original_file_path = '';
            foreach ($file_path_format as $index => $directory) {
                if ($index != 0 && $index != 1) {
                    $original_file_path = $original_file_path.'/'.$directory;
                }
            }
            if (! empty($original_file_path)) {
                if (Storage::disk('public')->exists($original_file_path)) {
                    Storage::disk('public')->delete($original_file_path);

                    return 'File Deleted Successfully';
                }
                throw new \Exception('Incorrect File Path!');
            }
        } else {
            throw new \Exception('Incorrect File Path!');
        }
    }
}
