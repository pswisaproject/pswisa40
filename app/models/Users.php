<?php

namespace App\Models;

class Users extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('users');
    }
}
