<?php

return [

    'adminColumns' => [
        'name',
        'email',
    ],
    'adminFormFields' => [
        'name' => [
            'type' => 'text',
            'label' => 'Name',
        ],
        'email' => [
            'type' => 'text',
            'label' => 'Email',
        ],
        'is_admin' => [
            'type' => 'checkbox',
            'label' => 'is_admin',
        ],
        'password' => [
            'type' => 'password',
            'label' => 'Password',
        ],
    ],
];