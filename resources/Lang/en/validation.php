<?php


return [
    // Public
    'unique' => 'The :attribute has already been taken.',
    'required' => 'The :attribute field is required.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'numeric' => 'The :attribute must be a number.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
    ],
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
    ],
    'integer' => 'The :attribute must be an integer.',
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
    ],
    'in' => 'The selected :attribute is invalid.',

    //Appointements
    'status.required' => 'The status field is required.',
    'status.in' => 'The selected status is invalid. Only accept or reject are allowed.',

    //Activity
    'activity_name.required' => 'The activity name is required.',

    //Realty
    'name' => 'name',
    'description' => 'description',
    'price' => 'price',
    'photo' => 'photo',
    'location' => 'location',
    'country' => 'country',
    'city' => 'city',
    'num_rooms' => 'number of rooms',
    'num_bathrooms' => 'number of bathrooms',
    'space_sqft' => 'space (sq ft)',
    'year_built' => 'year built',
    'parking_spaces' => 'parking spaces',
    'lot_size' => 'lot size',
    'status' => 'status',
    'average_rating' => 'average rating',
    'realty_name_unique' => 'The name is already taken in one or more languages.',

];
