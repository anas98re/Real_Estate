<?php

return [
    //Public
    'unique' => 'Le :attribute a déjà été pris.',
    'required' => 'Le champ :attribute est obligatoire.',
    'required_with' => 'Le champ :attribute est nécessaire lorsque :values est présent.',
    'numeric' => 'Le champ :attribute doit être un nombre.',
    'gt' => [
        'numeric' => 'Le :attribute doit être plus grand que :value.',
    ],
    'lt' => [
        'numeric' => 'Le :attribute doit être moins que :value.',
    ],
    'integer' => 'Le champ :attribute doit être un entier.',
    'max' => [
        'numeric' => 'Le :attribute ne peut pas être supérieur à :max.',
        'file' => 'Le :attribute ne peut pas avoir plus de :max kilo-octets.',
        'string' => 'Le :attribute ne peut pas contenir plus de :max caractères.',
    ],
    'in' => 'Le :attribute sélectionné est invalide.',

    //Appointements
    'status.required' => 'Le champ de statut est obligatoire.',
    'status.in' => 'Le statut sélectionné est invalide. Seulement accepter ou rejeter sont autorisés.',

    //Activity
    'activity_name.required' => 'Le nom de l\'activité est requis.',

    //Realty
    'name' => 'nom',
    'description' => 'description',
    'price' => 'prix',
    'photo' => 'photo',
    'location' => 'emplacement',
    'country' => 'pays',
    'city' => 'ville',
    'num_rooms' => 'nombre de chambres',
    'num_bathrooms' => 'nombre de salles de bain',
    'space_sqft' => 'espace (m²)',
    'year_built' => 'année de construction',
    'parking_spaces' => 'places de parking',
    'lot_size' => 'taille du lot',
    'status' => 'statut',
    'average_rating' => 'note moyenne',
    'realty_name_unique' => 'Le nom est déjà pris dans une ou plusieurs langues.',


];
