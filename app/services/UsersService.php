<?php

namespace App\Services;

use App\Models\Users;
use App\Models\UserSession;

class UsersService extends AbstractService
{
    /* --------------------- ERRORS ------------------------------- */

    /* --------------------- REGISTRATION ERRORS ------------------ */
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

    /* --------------------- LOGIN ERRORS ------------------ */

    const ERROR_INVALID_USER_CREDENTIALS = 11001;
    const ERROR_USER_NOT_ACTIVE = 11002;
    const ERROR_USER_NOT_VERIFIED = 11003;
    const ERROR_UNAUTHORIZED = 11004;

    /* --------------- USER SERVICE FUNCTIONS --------------- */
    
    public function getUsersList()
    {

    }

    public function login($userId, $hashToken)
    {
        try {
            $userSessionModel                   = new UserSession();
            $userSessionModel->user_id          = $userId;
            $userSessionModel->token            = $hashToken;
            $userSessionModel->created_at       = date("Y-m-d H:i:s");
            $userSessionModel->updated_at       = date("Y-m-d H:i:s");
            $userSessionModel->expire_at        = date("Y-m-d H:i:s", strtotime("+1 month", time()));

            if (!$userSessionModel->create()) {
                print_r($userSessionModel->getMessages());
                throw new \Exception('Unable to create user session!');
            }
        } catch (\Throwable $th) {
            print_r("throwable: \n" . $th);
        }
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
            $usersModel->active = 0;
            $usersModel->verified = 0;
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

    public function getPendingUsersList() {
        try {
            $pendingUsers = Users::findByRegRequest('PENDING');

            if (!$pendingUsers) {
                return [];
            }

            return $pendingUsers->toArray();
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function handleUserRegistration($userId, $approved) {
        try {
            $user = Users::findFirstById($userId);
            if ($approved == 1) {
                $user->reg_request = 'APPROVED';
            } else {
                $user->reg_request = 'DECLINED';
            }

            if (!$user->update()) {
                throw new \Exception('Unable to confirm user!');
            }
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
