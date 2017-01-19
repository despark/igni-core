<?php

return [
    'users' => [
        'name' => 'Team',
        'link' => '#',
        'isActive' => false,
        'iconClass' => 'fa-users',
        'permissionsNeeded' => 'manage_users',
        'subMenu' => [
            'users_manager' => [
                'name' => 'Users Manager',
                'link' => 'user.index',
                'isActive' => false,
                'permissionsNeeded' => 'manage_users',
            ],
        ],
    ],
];
