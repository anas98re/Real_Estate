<?php

namespace App\Http\Requests;

use App\Models\Realty;
use Illuminate\Foundation\Http\FormRequest;

class UpdaterealtyRequest extends FormRequest
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
            'name.en' => [
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($this->isNameTaken($value, 'en')) {
                        $fail(__('validation.unique', ['attribute' => __('fields.name') . ' (EN)']));
                    }
                },
            ],
            'name.fr' => [
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
        return Realty::where("name->$lang", $value)->exists();
    }
}
