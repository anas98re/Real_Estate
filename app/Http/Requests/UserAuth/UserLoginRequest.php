<?php

namespace App\Http\Requests\UserAuth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserLoginRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'email' => 'required|email',
            // 'name' => 'required|string',
            'email-or-name' => 'required|string',
            'password' => 'required|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            // 'email.exists' => 'error email or password failed',
            // 'name.exists' => 'error username or password failed',

        ];

    }

    protected function failedValidation(Validator $validator)
    {
        $errors = collect($validator->errors()->all())->unique();

        $response = response()->json([
            'errors' => $errors->all(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
