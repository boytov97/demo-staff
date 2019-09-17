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
        'action'     => 'index',
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
        'controller' => 'admin',
        'action'     => 'users',
    ]
)->setName('admin-users');

$router->add(
    '/admin/user/{id}/edit',
    [
        'controller' => 'admin',
        'action'     => 'edit',
    ]
)->setName('admin-users-edit');

$router->addPost(
    '/admin/user/{id}/activate-deactivate',
    [
        'controller' => 'admin',
        'action'     => 'updateActivity',
    ]
)->setName('admin-users-update-activity');

$router->add(
    '/admin/create-user',
    [
        'controller' => 'admin',
        'action'     => 'createUser',
    ]
)->setName('admin-create-user');

return $router;
