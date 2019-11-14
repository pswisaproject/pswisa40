<?php

namespace App\Controllers\HttpExceptions;

use App\Controllers\AbstractHttpException;

class Http400Exception extends AbstractHttpException
{
    protected $httpCode    = 400;
    protected $httpMessage = 'Bad request';
}
