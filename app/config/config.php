<?php

return new \Phalcon\Config(
    [
        'database'     => [
            'adapter'  => 'mysql',
            'host'     => 'localhost',
            'username' => 'root',
            'password' => 'LogicS',
            'dbname'   => 'pswisa',
            'charset'  => 'utf8mb4'
        ],

        'application'  => [
            'controllersDir' => "app/controllers/",
            'modelsDir'      => "app/models/",
            'baseUri'        => "/"
        ]
    ]
);
