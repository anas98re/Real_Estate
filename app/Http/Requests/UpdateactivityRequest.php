<?php

namespace App\Http\Requests;

use App\Models\Activity;
use Illuminate\Foundation\Http\FormRequest;

class UpdateactivityRequest extends FormRequest
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
        ];
    }

    protected function isNameTaken($value, $lang)
    {
        return Activity::where("activity_name->$lang", $value)->exists();
    }
}
