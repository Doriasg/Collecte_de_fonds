<?php

return [
    'required'  => 'Le champ :attribute est obligatoire.',
    'email'     => 'Le champ :attribute doit être une adresse email valide.',
    'min'       => [
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'max'       => [
        'string' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
    ],
    'confirmed' => 'La confirmation du :attribute ne correspond pas.',
    'unique'    => 'Cette valeur est déjà utilisée pour :attribute.',

    'attributes' => [
        'email'    => 'email',
        'password' => 'mot de passe',
        'name'     => 'nom',
    ],
];
