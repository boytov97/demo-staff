<?php

use Phalcon\Mvc\Router;

$router = new Router();

$router->add('/home', [
    'controller' => 'index',
    'action' => 'index'
])->setName('home');

$router->add('/reset-password/{code}/{email}', [
    'controller' => 'user_control',
    'action' => 'resetPassword'
]);

$router->add(
    '/user/profile',
    [
        'controller' => 'users',
        'action'     => 'profile',
    ]
)->setName('user-profile');

$router->add(
    '/user/delete-uploads',
    [
        'controller' => 'users',
        'action'     => 'deleteUploads',
    ]
)->setName('user-delete-uploads');

$router->add(
    '/user/change-password',
    [
        'controller' => 'users',
        'action'     => 'changePassword',
    ]
)->setName('users-changePassword');

$router->addGet(
    '/login',
    [
        'controller' => 'session',
        'action'     => 'index',
    ]
)->setName('session-index');

$router->addPost(
    '/login',
    [
        'controller' => 'session',
        'action'     => 'login',
    ]
)->setName('session-login');

$router->add(
    '/logout',
    [
        'controller' => 'session',
        'action'     => 'logout',
    ]
)->setName('session-logout');

$router->add(
    '/hours/index/',
    [
        'controller' => 'hours',
        'action'     => 'index',
    ]
)->setName('hours-index');

$router->add(
    '/forgot-password',
    [
        'controller' => 'session',
        'action'     => 'forgotPassword',
    ]
)->setName('session-forgot-password');

$router->addPost(
    '/hours/{id}/update/{startEndId}',
    [
        'controller' => 'hours',
        'action'     => 'update',
    ]
)->setName('hours-update');

$router->addPost(
    '/hours/{id}/update-total/',
    [
        'controller' => 'hours',
        'action'     => 'updateTotal',
    ]
)->setName('hours-update-total');

$router->add(
    '/admin',
    [
        'controller' => 'admin',
        'action'     => 'index',
    ]
)->setName('admin-index');

$router->add(
    '/admin/users',
    [
        'controller' => 'users',
        'action'     => 'index',
    ]
)->setName('admin-users-list');

$router->add(
    '/admin/user/{id}/edit',
    [
        'controller' => 'users',
        'action'     => 'edit',
    ]
)->setName('admin-users-edit');

$router->addPost(
    '/admin/user/{id}/activate-deactivate',
    [
        'controller' => 'users',
        'action'     => 'updateActivity',
    ]
)->setName('admin-users-update-activity');

$router->add(
    '/admin/create-user',
    [
        'controller' => 'users',
        'action'     => 'create',
    ]
)->setName('admin-create-user');

$router->addPost(
    '/admin/{id}/update-start-end/',
    [
        'controller' => 'admin',
        'action'     => 'updateStartEnd',
    ]
)->setName('admin-update-start-end');

$router->addPost(
    '/admin/{userId}/create-counter/{createdAt}',
    [
        'controller' => 'admin',
        'action'     => 'createCounter',
    ]
)->setName('admin-create-counter');

$router->add(
    '/admin/not-working-days',
    [
        'controller' => 'not_working_days',
        'action'     => 'index',
    ]
)->setName('not-working-days');

$router->add(
    '/admin/not-working-day/create',
    [
        'controller' => 'not_working_days',
        'action'     => 'create',
    ]
)->setName('not-working-day-create');

$router->add(
    '/admin/not-working-day/{id}/delete',
    [
        'controller' => 'not_working_days',
        'action'     => 'delete',
    ]
)->setName('not-working-day-delete');

$router->addPost(
    '/admin/settings/create-update',
    [
        'controller' => 'settings',
        'action'     => 'createOrUpdate',
    ]
)->setName('settings-create-update');

$router->add(
    '/admin/permissions',
    [
        'controller' => 'permissions',
        'action'     => 'index',
    ]
)->setName('permissions-index');

$router->addPost(
    '/admin/individually_wd/{userId}/create-update/{createdAt}',
    [
        'controller' => 'individually_wd',
        'action'     => 'createOrUpdate',
    ]
)->setName('individually_wd-create-update');

return $router;
