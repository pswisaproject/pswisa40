<?php

namespace App\Helpers\Validators;

use App\Models\Users;
use App\Services\UsersService;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\Alnum;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex as RegexValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class RegistrationValidator extends Validation
{
    public function initialize()
    {
        $userModel = new Users();
                /* ------------------------ PresenceOf ----------------------------*/
        $this->add(
            'id',
            new PresenceOf(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_ID,
                    'message' => 'Healthcare ID is required!'
                ]
            )
        );

        $this->add(
            'firstName',
            new PresenceOf(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_FIRST_NAME,
                    'message' => 'First name is required!'
                ]
            )
        );

        $this->add(
            'lastName',
            new PresenceOf(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_LAST_NAME,
                    'message' => 'Last name is required!'
                ]
            )
        );

        $this->add(
            'password',
            new PresenceOf(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_PASSWORD,
                    'message' => 'Password is required!'
                ]
            )
        );

        $this->add(
            'email',
            new PresenceOf(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_EMAIL,
                    'message' => 'E-mail is required!'
                ]
            )
        );

        $this->add(
            'address',
            new PresenceOf(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_ADDRESS,
                    'message' => 'Address is required!'
                ]
            )
        );

        $this->add(
            'city',
            new PresenceOf(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_CITY,
                    'message' => 'City is required!'
                ]
            )
        );

        $this->add(
            'country',
            new PresenceOf(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_COUNTRY,
                    'message' => 'Country is required!'
                ]
            )
        );

        $this->add(
            'phone',
            new PresenceOf(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_PHONE,
                    'message' => 'Phone number is required!'
                ]
            )
        );

        /* ------------------------ Validation ----------------------------*/

        $this->add(
            'id',
            new Digit(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_ID,
                    'message' => 'Healthcare ID must be a digit. Please enter a valid value.'
                ]
            )
        );

        $this->add(
            'firstName',
            new Alpha(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_FIRST_NAME,
                    'message' => 'First name must only contain letters. Please enter a valid value.'
                ]
            )
        );

        $this->add(
            'lastName',
            new Alpha(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_LAST_NAME,
                    'message' => 'Last name must only contain letters. Please enter a valid value.'
                ]
            )
        );

        $this->add(
            'email',
            new Email(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_EMAIL,
                    'message' => 'E-mail is not valid! Please enter a valid e-mail address.'
                ]
            )
        );

        $this->add(
            'address',
            new Alnum(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_ADDRESS,
                    'message' => 'Address is not valid! Please enter a valid address.'
                ]
            )
        );

        $this->add(
            'city',
            new Alpha(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_CITY,
                    'message' => 'City is not valid! Please enter a valid city name.'
                ]
            )
        );

        $this->add(
            'country',
            new Alpha(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_COUNTRY,
                    'message' => 'Country is not valid! Please enter a valid country name.'
                ]
            )
        );

        $this->add(
            'phone',
            new Digit(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_PHONE,
                    'message' => 'Phone number is not valid! Please enter a valid phone number.'
                ]
            )
        );

        // Minimum eight characters, at least one letter and one number
        $this->add(
            'password',
            new RegexValidator(
                [
                    'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
                    'code'    => UsersService::ERROR_INVALID_REG_PASSWORD,
                    'message' => 'The password needs to be at least 8 characters and it needs to contain at least one letter and one number!'
                ]
            )
        );

        /* ------------------------ Uniqueness ----------------------------*/
        $this->add(
            'id',
            new UniquenessValidator([
                'model'   => $userModel,
                'code'    => UsersService::ERROR_ID_ALREADY_TAKEN,
                'message' => 'A user with that healthcare ID already exists!'
            ])
        );

        $this->add(
            'email',
            new UniquenessValidator([
                'model'   => $userModel,
                'code'    => UsersService::ERROR_EMAIL_ALREADY_TAKEN,
                'message' => 'A user with that email address already exists!'
            ])
        );
    }
}
