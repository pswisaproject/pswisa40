<?php

namespace App\Controllers\HttpExceptions;

use App\Controllers\AbstractHttpException;

class Http404Exception extends AbstractHttpException
{
    protected $httpCode    = 404;
    protected $httpMessage = 'Not Found';
}
