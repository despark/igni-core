<?php

return [

    'name' => 'User',
    'description' => 'User resource',
    'model' => config('auth.providers.users.model'),
    'controller' => \Despark\Cms\Http\Controllers\UsersController::class,
    'adminColumns' => [
        'name',
        'email',
    ],
    'actions' => ['edit', 'create', 'destroy'],
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
    'adminMenu' => [
        'user_management' => [
            'name' => 'User Management',
            'iconClass' => 'fa-users',
        ],
        'users' => [
            'name' => 'Users',
            'link' => 'user.index',
            'parent' => 'user_management',
        ],
    ],
];
