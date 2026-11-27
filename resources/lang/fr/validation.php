<?php

/*
| French validation messages for the rules used by the site (contact form).
| English messages come from Laravel's built-in translations.
| Add a rule here when a new validation rule is used in a form.
*/

return [
    'required' => 'Le champ :attribute est obligatoire.',
    'email' => 'Le champ :attribute doit être une adresse e-mail valide.',
    'string' => 'Le champ :attribute doit être un texte.',
    'max' => [
        'array' => 'Le champ :attribute ne doit pas contenir plus de :max éléments.',
        'file' => 'Le fichier :attribute ne doit pas dépasser :max kilo-octets.',
        'numeric' => 'Le champ :attribute ne doit pas dépasser :max.',
        'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
    ],

    'attributes' => [
        'name' => 'nom',
        'email' => 'e-mail',
        'message' => 'message',
    ],
];
