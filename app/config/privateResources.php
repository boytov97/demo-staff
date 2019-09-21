<?php

use Phalcon\Config;

return new Config([
    'privateResources' => [
        'users' => [
            'index',
            'edit',
            'profile',
            'create',
            'updateActivity',
            'deleteUploads',
            'changePassword'
        ],
        'hours' => [
            'index',
            'update',
            'updateTotal',
        ],
        'notWorkingDays' => [
            'index',
            'create',
            'delete',
        ],
        'settings' => [
            'createOrUpdate'
        ],
        'admin' => [
            'index',
            'updateStartEnd',
        ],
        'permissions' => [
            'index'
        ]
    ]
]);
