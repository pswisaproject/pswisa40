<?php

namespace App\Helpers;

use App\Globals\Constants;

class HashTokenHelper
{
    public function generateHashToken($email)
    {
        return
        md5($email . date("Y-m-d H:i:s") . Constants::FIRST_SALT_KEY);
    }
}