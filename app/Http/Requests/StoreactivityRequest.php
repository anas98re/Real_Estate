<?php

namespace App\Http\Requests;

use App\Models\Activity;
use Illuminate\Foundation\Http\FormRequest;

class StoreactivityRequest extends FormRequest
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
            'activity_name.en' => [
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($this->isNameTaken($value, 'en')) {
                        $fail(__('validation.unique', ['attribute' => __('fields.name') . ' (EN)']));
                    }
                },
            ],
            'activity_name.fr' => [
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($this->isNameTaken($value, 'fr')) {
                        $fail(__('validation.unique', ['attribute' => __('fields.name') . ' (FR)']));
                    }
                },
            ],
            // 'activity_name' => 'required',
            'type_id' => 'required|integer|exists:activity_types,id',  // Ensure the type exists.
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
        return Activity::where("activity_name->$lang", $value)->exists();
    }

    public function messages()
    {
        return [
            'activity_name.required' => __('validation.required', ['attribute' => __('fields.activity_name')]),
            'type_id.required' => __('validation.required', ['attribute' => __('fields.type_id')]),
        ];
    }
}
