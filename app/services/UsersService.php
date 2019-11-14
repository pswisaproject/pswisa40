<?php

namespace App\Services;

use App\Models\Users;

class UsersService extends AbstractService
{
    /* --------------------- ERRORS ------------------------------- */
    const ERROR_INVALID_REG_ID = 10001;
    const ERROR_INVALID_REG_FIRST_NAME = 10002;
    const ERROR_INVALID_REG_LAST_NAME = 10003;
    const ERROR_INVALID_REG_PASSWORD = 10004;
    const ERROR_INVALID_REG_EMAIL = 10005;
    const ERROR_INVALID_REG_ADDRESS = 10006;
    const ERROR_INVALID_REG_CITY = 10007;
    const ERROR_INVALID_REG_COUNTRY = 10008;
    const ERROR_INVALID_REG_PHONE = 10009;
    const ERROR_ID_ALREADY_TAKEN = 10010;
    const ERROR_EMAIL_ALREADY_TAKEN = 10011;

    /* --------------- USER SERVICE FUNCTIONS --------------- */
    
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
