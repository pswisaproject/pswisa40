<?php

namespace App\Services;

abstract class AbstractService extends \Phalcon\DI\Injectable
{
    const ERROR_INVALID_PARAMETERS = 10001;
    const ERROR_ALREADY_EXISTS = 10002;
}
