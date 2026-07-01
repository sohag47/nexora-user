<?php

namespace App\Traits;

use App\Enums\ResponseStatusEnums;
use Symfony\Component\HttpFoundation\Response;


trait ApiResponse
{
    // for success response
    protected function success($data = null, string $message = 'Success', int $status = Response::HTTP_OK)
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function created($data = null, string $message)
    {
        return $this->success($data, empty($message) ? ResponseStatusEnums::CREATED->value : $message, Response::HTTP_CREATED);
    }
    protected function updated($data = null, string $message)
    {
        return $this->success($data, empty($message) ? ResponseStatusEnums::UPDATED->value : $message, Response::HTTP_OK);
    }
    protected function deleted($data = null, string $message)
    {
        return $this->success($data, empty($message) ? ResponseStatusEnums::DELETED->value : $message, Response::HTTP_OK);
    }

    // for error response
    protected function error(string $message = 'Error', int $status = Response::HTTP_BAD_GATEWAY, $errors = null)
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    protected function notFound(string $message)
    {
        return $this->error(empty($message) ? ResponseStatusEnums::NOT_FOUND->value : $message, Response::HTTP_NOT_FOUND);
    }
    protected function unauthorized(string $message)
    {
        return $this->error(empty($message) ? ResponseStatusEnums::UNAUTHORIZED->value : $message, Response::HTTP_UNAUTHORIZED);
    }
    protected function serverError(string $message)
    {
        return $this->error(empty($message) ? ResponseStatusEnums::SERVER_ERROR->value : $message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    protected function validationError($errors)
    {
        return $this->error(ResponseStatusEnums::VALIDATION_FAILED->value, Response::HTTP_UNPROCESSABLE_ENTITY, $errors);
    }
    protected function serviceError(string $message)
    {
        return $this->error(empty($message) ? ResponseStatusEnums::SERVICE_UNAVAILABLE->value : $message, Response::HTTP_SERVICE_UNAVAILABLE);
    }
    protected function forbidden(string $message)
    {
        return $this->error(empty($message) ? ResponseStatusEnums::FORBIDDEN->value : $message, Response::HTTP_FORBIDDEN);
    }
}
