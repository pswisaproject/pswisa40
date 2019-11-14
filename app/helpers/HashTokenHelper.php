<?php

namespace App\Helpers;

use App\Globals\Constants;

class HashTokenHelper
{
    public function generateHashToken()
    {
        return
        md5(date("Y-m-d H:i:s") . Constants::FIRST_SALT_KEY) .
        md5(date("Y-m-d H:i:s") . Constants::SECOND_SALT_KEY);
    }
}