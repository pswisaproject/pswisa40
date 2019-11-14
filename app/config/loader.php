<?php

$loader = new \Phalcon\Loader();
$loader->registerNamespaces(
    [
        'App\Services'        => realpath(__DIR__ . '/../services/'),
        'App\Controllers'     => realpath(__DIR__ . '/../controllers/'),
        'App\Models'          => realpath(__DIR__ . '/../models/'),
        'App\Libs'            => realpath(__DIR__ . '/../libs/'),
        'App\Helpers'         => realpath(__DIR__ . '/../helpers/')
    ]
);

$loader->register();
