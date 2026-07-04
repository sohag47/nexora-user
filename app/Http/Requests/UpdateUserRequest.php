<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
            'branch_id' => ['nullable', 'integer'],
            'designation_id' => ['nullable', 'integer'],
            'department_id' => ['nullable', 'integer'],
            'photo' => ['nullable', 'url'],
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
