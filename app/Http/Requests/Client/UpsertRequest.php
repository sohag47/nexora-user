<?php

namespace App\Http\Requests\Client;

use App\Rules\BdPhone;
use App\Traits\ApiResponse;
use App\Traits\NormalizesPhone;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpsertRequest extends FormRequest
{
    use ApiResponse, NormalizesPhone;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizePhoneField();
    }

    public function rules(): array
    {
        $clientId = is_object($this->route('client')) ? $this->route('client')->id : $this->route('client');

        return [
            'name' => [
                'bail', 'required', 'string', 'max:255',
                Rule::unique('clients', 'name')->ignore($clientId),
            ],
            'email' => [
                'bail', 'required', 'string', 'email:rfc,dns', 'max:255',
                Rule::unique('clients', 'email')->ignore($clientId),
            ],
            'phone' => [
                'bail', 'required', 'string', new BdPhone,
                Rule::unique('clients', 'phone')->ignore($clientId),
            ],
            'photo' => [
                'nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048',
            ],
            'address' => [
                'nullable', 'string', 'max:255',
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->validationError($validator->errors())
        );
    }
}
