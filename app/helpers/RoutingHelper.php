<?php

namespace App\Helpers;

class RoutingHelper
{
    const AUTHENTICATION_MIDDLEWARE_EXCEPTIONS = [
        '/user/login',
        '/user/register',
        '/user/postTest',
        '/user/test'
    ];

    const AUTHENTICATION_MIDDLEWARE_EXCEPTION_CONTAINS = [];

    public function checkUrlToExceptionContains($url)
    {
        // print_r(RoutingHelper::AUTHENTICATION_MIDDLEWARE_EXCEPTION_CONTAINS);
        // $arr = RoutingHelper::AUTHENTICATION_MIDDLEWARE_EXCEPTION_CONTAINS;

        foreach (RoutingHelper::AUTHENTICATION_MIDDLEWARE_EXCEPTION_CONTAINS as $string) {
            if (strpos($url, $string) > -1) {
                return true;
            }
        }
        return false;
    }
}
