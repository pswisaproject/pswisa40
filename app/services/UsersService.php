<?php

namespace App\Services;

use App\Models\IllnessRecords;
use App\Models\RegistrationLinks;
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

    /* --------------------- EDIT PROFILE ERRORS ------------------ */

    const ERROR_EMPTY_EDIT_PROFILE_REQUEST = 12001;
    const ERROR_EDIT_PROFILE_ID = 12002;
    const ERROR_EDIT_PROFILE_ACTIVE = 12003;
    const ERROR_EDIT_PROFILE_VERIFIED = 12004;
    
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
            $usersModel->active = 1;
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

    public function editProfile($user)
    {
        try {
            $user->updated_at = date("Y:m:d H:i:s");

            if (!$user->update()) {
                throw new \Exception('Unable to edit user profile.');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
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

    public function getPatientProceduresList() {
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

    public function handleUserRegistration($user, $approved) {
        try {
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

    public function createRegistrationLink($userId, $token) {
        try {
            $registrationLink = new RegistrationLinks();
            $registrationLink->user_id = $userId;
            $registrationLink->token = $token;
            $registrationLink->active = 1;
            $registrationLink->created_at = date("Y-m-d H:i:s");
            $registrationLink->updated_at = date("Y-m-d H:i:s");
            $registrationLink->expire_at = date("Y-m-d H:i:s", strtotime("+1 month", time()));


            if (!$registrationLink->create()) {
                throw new \Exception('Unable to create registration link!');
            }
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function userConfirmationRegistration($user) {
        try {
            $user->verified = 1;

            if (!$user->update()) {
                throw new \Exception('Unable to confirm user!');
            }
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getPatientDiagnosisList($patientId) {
        try {
            $patientIllnessRecordsList = IllnessRecords::findByPatientId($patientId);
            if (!$patientIllnessRecordsList) {
                return [];
            }

            $patientDiagnosisList = [];
            foreach ($patientIllnessRecordsList as $diagnosis) {
                $patientDiagnosisList[] = $diagnosis->diagnosis_id;
            }

            return $patientDiagnosisList;
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
