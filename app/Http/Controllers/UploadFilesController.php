<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFile\DeleteRequest;
use App\Http\Requests\UploadFile\InsertRequest;
use App\Traits\ApiResponse;
use App\Traits\DocumentTraits;

class UploadFilesController extends Controller
{
    use ApiResponse, DocumentTraits;

    /**
     * Store a newly created resource in storage.
     */
    public function store(InsertRequest $request)
    {
        try {
            $response = $this->uploadFile($request);

            return $this->success($response);
        } catch (\Exception $error) {
            return $this->validationError($error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteRequest $request)
    {
        try {
            $this->deleteFile($request->file_path);

            return $this->deleted();
        } catch (\Exception $error) {
            return $this->validationError($error->getMessage());
        }

    }
}
