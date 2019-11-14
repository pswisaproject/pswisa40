<?php

use Phalcon\Db\Adapter\Pdo\Mysql;

$di = new \Phalcon\DI\FactoryDefault();

$di->setShared(
    'response',
    function () {
        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'utf-8mb4');

        return $response;
    }
);

$di->setShared('config', $config);

$di->set(
    'db',
    function () use ($config) {
        return new Mysql(
            [
                "host"     => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname"   => $config->database->dbname
            ]
        );
    }
);

$di->setShared('usersService', '\App\Services\UsersService');

return $di;
