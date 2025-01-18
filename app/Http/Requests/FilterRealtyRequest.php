<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterRealtyRequest extends FormRequest
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
            'min_price' => 'required_with:max_price|numeric',
            'max_price' => 'required_with:min_price|numeric|gt:min_price',
            'min_space_sqft' => 'required_with:max_space_sqft|numeric',
            'max_space_sqft' => 'required_with:min_space_sqft|numeric|gt:min_space_sqft',
            'max_lot_size' => 'required_with:min_lot_size|numeric',
            'min_lot_size' => 'required_with:max_lot_size|numeric|lt:max_lot_size',
        ];
    }


    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        // Pulling messages based on the locale set by the middleware
        return [
            'min_price.required_with' => __('validation.required_with', ['values' => 'max_price']),
            'max_price.required_with' => __('validation.required_with', ['values' => 'min_price']),
            'max_price.gt' => __('validation.gt.numeric', ['value' => ':input']),
            'min_space_sqft.required_with' => __('validation.required_with', ['values' => 'max_space_sqft']),
            'max_space_sqft.required_with' => __('validation.required_with', ['values' => 'min_space_sqft']),
            'max_space_sqft.gt' => __('validation.gt.numeric', ['value' => ':input']),
            'min_lot_size.required_with' => __('validation.required_with', ['values' => 'max_lot_size']),
            'max_lot_size.required_with' => __('validation.required_with', ['values' => 'min_lot_size']),
            'max_lot_size.gt' => __('validation.gt.numeric', ['value' => ':input'])
        ];
    }
}
