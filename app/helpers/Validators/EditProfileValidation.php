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

class EditProfileValidation extends Validation
{
    public function initialize()
    {
        $userModel = new Users();

        /* ------------------------ Validation ----------------------------*/

        $this->add(
            'first_name',
            new Alpha(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_FIRST_NAME,
                    'message' => 'First name must only contain letters. Please enter a valid value.',
                    'allowEmpty' => true
                ]
            )
        );

        $this->add(
            'last_name',
            new Alpha(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_LAST_NAME,
                    'message' => 'Last name must only contain letters. Please enter a valid value.',
                    'allowEmpty' => true
                ]
            )
        );

        $this->add(
            'email',
            new Email(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_EMAIL,
                    'message' => 'E-mail is not valid! Please enter a valid e-mail address.',
                    'allowEmpty' => true
                ]
            )
        );

        $this->add(
            'address',
            new Alnum(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_ADDRESS,
                    'message' => 'Address is not valid! Please enter a valid address.',
                    'allowEmpty' => true
                ]
            )
        );

        $this->add(
            'city',
            new Alpha(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_CITY,
                    'message' => 'City is not valid! Please enter a valid city name.',
                    'allowEmpty' => true
                ]
            )
        );

        $this->add(
            'country',
            new Alpha(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_COUNTRY,
                    'message' => 'Country is not valid! Please enter a valid country name.',
                    'allowEmpty' => true
                ]
            )
        );

        $this->add(
            'phone',
            new Digit(
                [
                    'code'    => UsersService::ERROR_INVALID_REG_PHONE,
                    'message' => 'Phone number is not valid! Please enter a valid phone number.',
                    'allowEmpty' => true
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
                    'message' => 'The password needs to be at least 8 characters and it needs to contain at least one letter and one number!',
                    'allowEmpty' => true
                ]
            )
        );

        /* ------------------------ Uniqueness ----------------------------*/

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
