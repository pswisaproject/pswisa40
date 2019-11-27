<?php

namespace App\Helpers;

use App\Models\Users;
use App\Models\UserSession;

class CommonHelpers
{
    public function getCurrentUser($request)
    {
        $hashToken = $request->getHeader('Ht');
        $currentUserId = UserSession::findFirstByToken($hashToken)->user_id;
        return Users::findFirstById($currentUserId);
    }
}