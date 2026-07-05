<?php

namespace App\Http\Requests\Department;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpsertRequest extends FormRequest
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
        $id = $this->route('department');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')->ignore($id)],
            'code' => ['required', 'string', 'max:255', Rule::unique('departments', 'code')->ignore($id)],
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
