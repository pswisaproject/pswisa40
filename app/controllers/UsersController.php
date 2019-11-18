<?php

namespace App\Controllers;

use App\Controllers\HttpExceptions\Http400Exception;
use App\Models\Users;
use App\Services\UsersService;
use App\Helpers\Validators\RegistrationValidator;
use App\Helpers\CommonHelpers;
use App\Helpers\MailerHelper;
use App\Helpers\HashTokenHelper;
use App\Helpers\SQLHelper;

class UsersController extends AbstractController
{
    public function loginAction()
    {
        $errors = [];
        try {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $hashToken = HashTokenHelper::generateHashToken($email);
            $user = Users::findFirstByEmail($email);

            $userId         = $user->id;
            $isUserVerified = $user->verified;
            $isUserActive   = $user->active;
            
            if ($email == $user->email && $isUserVerified == 0) {
                $errors[] =
                    [
                    'invalid_login' => 'User is not verified!',
                    'error_code'    => UsersService::ERROR_USER_NOT_VERIFIED
                ];
            }

            if ($email == $user->email && $isUserActive == 0) {
                $errors[] =
                    [
                    'invalid_login' => 'User is disabled!',
                    'error_code'    => UsersService::ERROR_USER_NOT_ACTIVE
                ];
            }

            if ($email !== $user->email || $password !== $user->password) {
                $errors[] =
                    [
                    'invalid_login' => 'Incorrect email or password! Please try again.',
                    'error_code'    => UsersService::ERROR_INVALID_USER_CREDENTIALS
                ];
            }

            if ($errors) {
                $exception = new Http400Exception(_('Invalid login parameters.'), self::ERROR_INVALID_REQUEST);
                throw $exception->addErrorDetails($errors);
            }

            UsersService::login($userId, $hashToken);
            return ['data' => [], 'message' => 'User successfully logged in!'];

        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case UsersService::ERROR_INVALID_USER_CREDENTIALS:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function registerAction()
    {
        $errors = [];
        try {
            $id = $this->request->getPost('id');
            $firstName = $this->request->getPost('firstName');
            $lastName = $this->request->getPost('lastName');
            $email = $this->request->getPost('email');
            $password = $this->request->get('password');
            $address = $this->request->getPost('address');
            $city = $this->request->getPost('city');
            $country = $this->request->getPost('country');
            $phone = $this->request->get('phone');

            $validation = new RegistrationValidator();
            $messages = $validation->validate($this->request->getPost());

            if (count($messages)) {
                foreach ($messages as $message) {
                    $errors[] = [
                        'code' => $message->getCode(),
                        'message' => $message->getMessage()];
                }
                $exception = new Http400Exception(_('Invalid registration parameters'), self::ERROR_BAD_REQUEST);
                throw $exception->addErrorDetails($errors);
            }
            UsersService::register($id, $firstName, $lastName, $email, $password, 
                                              $address, $city, $country, $phone);

            $mailerHelper = new MailerHelper();
            $mailerHelper::sendMail($email, 'Confirm your registration.', 'Please confirm your registration at this link: www.temporary.com');
            return ['data' => [], 'message' => 'User successfully registered!'];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function editProfileAction()
    {
       
    }

    public function getPendingUsersListAction()
    {
        $errors = [];

        try {
            $sqlHelper = new SQLHelper();

            $user      = CommonHelpers::getCurrentUser($this->request);
            $id        = $user->id;

            $isAuthorized = $sqlHelper->isUserAuthorized($id, 'LIST_PENDING_USERS');

            if (!$isAuthorized) {
                $errors[] =
                    [
                    'invalid_permission' => 'User is not authorized to list pending users!',
                    'error_code'         => UsersService::ERROR_UNAUTHORIZED
                ];
                $exception = new Http401Exception(_('Unauthorized access error'), self::ERROR_INVALID_REQUEST);
                throw $exception->addErrorDetails($errors);
            }

            try {
                $pendingUsersList = $this->usersService->getPendingUsersList();
            } catch (ServiceException $e) {
                throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }

            return ['data' => ['pending_users' => $pendingUsersList], 'message' => 'Successfully fetched pending users!'];

        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case UsersService::ERROR_UNAUTHORIZED:
                    throw new Http401Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function handleUserRegistrationAction() {
        // ?userID=id&approved=boolean
        $errors = [];
        try {
            $sqlHelper = new SQLHelper();
            $user      = CommonHelpers::getCurrentUser($this->request);
            $id        = $user->id;
            $isAuthorized = $sqlHelper->isUserAuthorized($id, 'HANDLE_USER_REGISTRATION');

            $userId     = $this->request->get('userID');
            $approved   = $this->request->get('approved');
            $message    = $this->request->getPost('message');

            if (!$isAuthorized) {
                $errors[] =
                    [
                    'invalid_permission' => 'User is not authorized to handle user registrations!',
                    'error_code'         => UsersService::ERROR_UNAUTHORIZED
                ];
                $exception = new Http401Exception(_('Unauthorized access error'), self::ERROR_INVALID_REQUEST);
                throw $exception->addErrorDetails($errors);
            }

            $result = '';
            $user = Users::findFirstById($userId);
            $email = $user->email;
            UsersService::handleUserRegistration($user, $approved);
            $mailerHelper = new MailerHelper();
            try {
                if ($approved == 1) {
                    $result = 'registration approved!';
                    // SEND A MAIL TO THE USER WITH LINK
                } else {
                    $result = 'registration denied!';
                    $mailerHelper::sendMail($email, 'Registration denied!', 
                    'Your registration was denied with the following message: ' . $message);
                }
                
            } catch (ServiceException $e) {
                throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }

            return ['data' => [], 'message' => 'User ' . $result];
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case UsersService::ERROR_UNAUTHORIZED:
                    throw new Http401Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
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
