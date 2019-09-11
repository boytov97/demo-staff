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
    '/forgot-password',
    [
        'controller' => 'session',
        'action'     => 'forgotPassword',
    ]
)->setName('session-forgot-password');

$router->addPost(
    '/hours/update',
    [
        'controller' => 'hours',
        'action'     => 'update',
    ]
)->setName('hours-update');

return $router;
