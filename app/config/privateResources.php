<?php

use Phalcon\Config;

return new Config([
    'privateResources' => [
        'users' => [
            'index',
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
            'edit',
            'users',
            'createUser',
            'updateActivity',
            'updateStartEnd',
        ],
        'permissions' => [
            'index'
        ]
    ]
]);
