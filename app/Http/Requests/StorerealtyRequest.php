<?php

namespace App\Http\Requests;

use App\Models\Realty;
use App\Rules\UniqueMultilanguageRealtyName;
use Illuminate\Foundation\Http\FormRequest;

class StorerealtyRequest extends FormRequest
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
        $realtyId = $this->realty ? $this->realty->id : null;
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
            'name' => [
                'required',
                'max:255',
                new UniqueMultilanguageRealtyName('realties', 'name', $realtyId)
            ],
            'description' => 'required|max:5000',
            'price' => 'required|numeric',
            'photo' => 'nullable|file|image|max:2048',  // If expecting an image file, specify 'image' and size limit
            'location' => 'required|max:500',
            'country' => 'nullable|max:100',
            'city' => 'nullable|max:100',
            'num_rooms' => 'nullable|integer|min:1',
            'num_bathrooms' => 'nullable|integer|min:1',
            'space_sqft' => 'nullable|integer|min:1',
            'year_built' => 'nullable|integer|digits:4',
            'parking_spaces' => 'nullable|integer|min:0',
            'lot_size' => 'nullable|numeric',
            'status' => 'nullable|max:100',
            'average_rating' => 'nullable|numeric|between:0,5'
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
        return Realty::where("name->$lang", $value)->exists();
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('fields.name')]),
            'name.unique' => __('validation.unique', ['attribute' => __('fields.name')]),
            'description.required' => __('validation.required', ['attribute' => __('fields.description')]),
            'price.required' => __('validation.required', ['attribute' => __('fields.price')]),
            'price.numeric' => __('validation.numeric', ['attribute' => __('fields.price')]),
            'photo.file' => __('validation.file', ['attribute' => __('fields.photo')]),
            'location.required' => __('validation.required', ['attribute' => __('fields.location')]),
            'num_rooms.integer' => __('validation.integer', ['attribute' => __('fields.num_rooms')]),
            'num_bathrooms.integer' => __('validation.integer', ['attribute' => __('fields.num_bathrooms')]),
            'space_sqft.integer' => __('validation.integer', ['attribute' => __('fields.space_sqft')]),
            'year_built.integer' => __('validation.integer', ['attribute' => __('fields.year_built')]),
            'parking_spaces.integer' => __('validation.integer', ['attribute' => __('fields.parking_spaces')]),
            'lot_size.numeric' => __('validation.numeric', ['attribute' => __('fields.lot_size')]),
            'average_rating.numeric' => __('validation.numeric', ['attribute' => __('fields.average_rating')]),
        ];
    }
}
