<?php

namespace App\Controllers\HttpExceptions;

use App\Controllers\AbstractHttpException;

class Http422Exception extends AbstractHttpException
{
    protected $httpCode    = 422;
    protected $httpMessage = 'Unprocessable entity';
}
