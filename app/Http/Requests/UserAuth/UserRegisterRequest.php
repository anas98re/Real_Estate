<?php

namespace App\Http\Requests\UserAuth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegisterRequest extends FormRequest
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
            'name' => 'required|string|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'country_id' => '',
            'username' => '',
            'first_name' => '',
            'second_name' => '',
            'phone' => '',
            'facebook_account' => '',
            'instagram_account' => '',
            'tiktok_account	' => '',
            'birthday' => 'nullable|date',
            'city' => '',
            'last_login' => '',
            'cover' => 'nullable|image',
            'role_id' => 'in:2,3,4',
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
