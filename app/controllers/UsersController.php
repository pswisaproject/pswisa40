<?php

namespace App\Controllers;

use App\Controllers\HttpExceptions\Http400Exception;
use App\Models\Users;
use App\Services\UsersService;
use App\Helpers\Validators\RegistrationValidator;

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

            return ['data' => [], 'message' => 'User successfully registered!'];
        } catch (\Throwable $th) {
            throw $th;
        }
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
