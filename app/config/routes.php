<?php

use Phalcon\Mvc\Micro\Collection;

// ************************ user collection -> user actions/controllers *************************

$usersCollection = new Collection();
$usersCollection->setHandler('\App\Controllers\UsersController', true);
$usersCollection->setPrefix('/user');

$usersCollection->post('/login', 'loginAction');
$usersCollection->post('/register', 'registerAction');
$usersCollection->post('/handleRegister', 'handleRegistrationAction');
$usersCollection->put('/editProfile', 'editProfileAction');
$usersCollection->get('/list', 'getUsersListAction');
$usersCollection->get('/listPending', 'getPendingUsersListAction');

// tests
$usersCollection->get('/getTest', 'testGetAction');
$usersCollection->post('/postTest', 'postTestAction');
$usersCollection->delete('/deleteTest', 'deleteTestAction');

$app->mount($usersCollection);

// ************************ not found URLS *************************
$app->notFound(
    function () use ($app) {
        echo 'Not found m8! What a b8, do you r8... 8/8?';
    }
);
