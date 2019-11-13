<?php

namespace App\Controllers;

use App\Models\Users;
use App\Services\UsersService;

/**
 * Operations with Users: CRUD
 */
class UsersController extends AbstractController
{
    public function loginAction()
    {
        
    }

    public function registerAction()
    {
       
    }

    public function editProfileAction()
    {
       
    }

    public function testGetAction() {
        return ['data' => [], 'message' => 'Got it!'];
    }

    public function postTestAction() {
        return ['data' => [], 'message' => 'Posted!'];
    }

    public function deleteTestAction() {
        return ['data' => [], 'message' => 'Deleted!'];
    }
}
