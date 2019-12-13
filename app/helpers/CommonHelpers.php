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

    function check_in_range($start_date, $end_date, $date_from_user)
    {
        // Convert to timestamp
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $user_ts = strtotime($date_from_user);
        // Check that user date is between start & end
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }
}