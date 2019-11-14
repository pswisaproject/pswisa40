<?php

namespace App\Controllers\HttpExceptions;

use App\Controllers\AbstractHttpException;

class Http401Exception extends AbstractHttpException
{
    protected $httpCode    = 401;
    protected $httpMessage = 'Unauthorized';
}
