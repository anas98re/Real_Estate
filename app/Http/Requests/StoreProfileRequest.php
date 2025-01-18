<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProfileRequest extends FormRequest
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
            'country_id' => '',
            'username' => 'required|unique:users,username',
            'first_name' => '',
            'second_name' => '',
            'phone' => 'unique:users,phone',
            'facebook_account' => 'unique:users,facebook_account',
            'instagram_account' => 'unique:users,instagram_account',
            'tiktok_account	' => 'unique:users,tiktok_account',
            'birthday' => 'nullable|date',
            'city' => '',
            'last_login' => '',
            'cover' => 'nullable|image',
            // 'roles' => 'in:2,3,4,5,6',
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
