<?php

return [

    'name' => 'User',
    'description' => 'User resource',
    'model' => config('auth.model'),
    'controller' => \Despark\Cms\Http\Controllers\UsersController::class,
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