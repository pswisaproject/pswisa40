<?php

namespace App\Services;

use App\Models\Users;

class UsersService extends AbstractService
{
    public function getUserList()
    {

    }

    public function createLogin()
    {
    
    }

    public function register($id, $firstName, $lastName, $email, $password, 
                             $address, $city, $country, $phone)
    {
        try {
            $usersModel = new Users();

            $usersModel->id   = $id;
            $usersModel->first_name = $firstName;
            $usersModel->last_name= $lastName;
            $usersModel->email = $email;
            $usersModel->password = $password;
            $usersModel->address = $address;
            $usersModel->city = $city;
            $usersModel->country = $country;
            $usersModel->phone = $phone;
            $usersModel->active = 1;
            $usersModel->created_at = date("Y-m-d H:i:s");
            $usersModel->updated_at = date("Y-m-d H:i:s");

            if (!$usersModel->create()) {
                throw new \Exception('Unable to register user!');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function editProfile()
    {
      
    }
}
