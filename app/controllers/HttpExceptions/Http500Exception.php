<?php

namespace App\Controllers\HttpExceptions;

use App\Controllers\AbstractHttpException;

class Http500Exception extends AbstractHttpException
{
    protected $httpCode    = 500;
    protected $httpMessage = 'Internal Server Error';
}
