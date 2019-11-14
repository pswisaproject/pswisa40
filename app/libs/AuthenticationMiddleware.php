<?php

namespace App\Libs;

class AuthenticationMiddleware
{
    protected $app;

    protected $di;

    public function __construct(\Phalcon\Mvc\Micro $app)
    {
        if (empty($app)) {
            throw new \Exception('empty!');
        }
        $this->app = $app;
        $this->di  = $this->app->getDI();
    }

    public function isUserAuthenticated()
    {

    }
}
