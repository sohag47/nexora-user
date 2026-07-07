<?php

namespace App\Http\Requests\UploadFile;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\File;

class InsertRequest extends FormRequest
{
    use ApiResponse;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'field_name' => ['required', 'string', 'max:255'],
            'path_prefix' => ['required', 'string', 'max:255'],
            'files' => ['required', File::types(['jpg', 'png', 'pdf'])->max('5mb')],
        ];
    }

    /**
     * Override the default validation failure behavior for APIs.
     * This intercepts errors and formats them using your existing method.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->validationError($validator->errors())
        );
    }
}
