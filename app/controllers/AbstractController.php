<?php

namespace App\Controllers;

abstract class AbstractController extends \Phalcon\DI\Injectable
{
    const ERROR_BAD_REQUEST = 400;
    const ERROR_NOT_FOUND = 404;
    const ERROR_INVALID_REQUEST = 422;
}
