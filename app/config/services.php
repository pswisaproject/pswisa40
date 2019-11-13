<?php

use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di;

include __DIR__ . "/routes.php";

$di = new Di();

$di->set('router', function () {
    return require __DIR__ . '/routes.php';
}, true);

$di->set('db', function () use ($config) {
    return new DbAdapter(
        [
            'host'     => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname'   => $config->database->dbname
        ]
    );
});

$di->set('dispatcher', function () {
    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('App\Controllers');
    return $dispatcher;
});
