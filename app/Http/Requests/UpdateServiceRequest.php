<?php

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
            'service_name.en' => [
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($this->isNameTaken($value, 'en')) {
                        $fail(__('validation.unique', ['attribute' => __('fields.name') . ' (EN)']));
                    }
                },
            ],
            'service_name.fr' => [
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($this->isNameTaken($value, 'fr')) {
                        $fail(__('validation.unique', ['attribute' => __('fields.name') . ' (FR)']));
                    }
                },
            ],
        ];
    }
    /**
     * Check if the name is already taken in a specific language.
     *
     * @param string $value The name to check.
     * @param string $lang The language code.
     * @return bool
     */
    protected function isNameTaken($value, $lang)
    {
        return Service::where("service_name->$lang", $value)->exists();
    }
}
