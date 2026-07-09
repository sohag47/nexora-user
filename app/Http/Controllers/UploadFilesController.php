<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFile\DeleteRequest;
use App\Http\Requests\UploadFile\InsertRequest;
use App\Http\Requests\UploadFile\UpdateRequest;
use App\Services\FileUploadService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UploadFilesController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly FileUploadService $service
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(InsertRequest $request)
    {
        try {
            $response = $this->service->upload(
                $request['path_prefix'],
                $request['field_name'],
                $request['files']
            );

            return $this->success($response);
        } catch (\Exception $error) {
            return $this->validationError($error->getMessage());
        }
    }

    /**
     * Update an existing resource in storage.
     */
    public function update(UpdateRequest $request)
    {
        try {
            $response = $this->service->update(
                $request['path_prefix'],
                $request['field_name'],
                $request['files'],
                $request['old_file_path'] ?? null
            );

            return $this->success($response);
        } catch (\Exception $error) {
            return $this->validationError($error->getMessage());
        }
    }

    /**
     * Download the specified resource.
     */
    public function download(Request $request)
    {
        try {
            return $this->service->download($request->file_path);
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
            $this->service->delete($request->file_path);

            return $this->deleted();
        } catch (\Exception $error) {
            return $this->validationError($error->getMessage());
        }
    }
}
