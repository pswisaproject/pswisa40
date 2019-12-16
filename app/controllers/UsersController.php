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
use App\Models\RegistrationLinks;
use App\Models\Diagnosis;
use App\Models\UsersToDoctorSpecialties;
use App\Models\DoctorSpecialties;
use App\Models\DoctorRatings;
use App\Models\Vacations;
use App\Models\Clinics;
use App\Models\ClinicsAppointmentSlots;
use App\Models\Countries;
use App\Models\Cities;


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
          $changedPassword = $user->changedPassword;
          
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
          return ['data' => ['Ht' => $hashToken, 'changedPassword' => $changedPassword], 'message' => 'User successfully logged in!'];

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
                      'error_code' => $message->getCode(),
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

  public function getUserInfoAction() {
      try {
          $user      = CommonHelpers::getCurrentUser($this->request);
          if (!$user) {
              throw new \Exception('User does not exist!');
          }
          return ['data' => 
          [
              'healthcare_id' => $user->id,
              'first_name' => $user->first_name,
              'last_name' => $user->last_name,
              'email' => $user->email,
              'address' => $user->address,
              'city' => $user->city,
              'country' => $user->country,
              'phone' => $user->phone
          ], 'message' => 'Successfully fetched user info!'];

      } catch (\Throwable $th) {
          throw $th;
      }
  }

  public function getUserMedicalRecordsAction() {
      try {
          $user      = CommonHelpers::getCurrentUser($this->request);
          if (!$user) {
              throw new \Exception('User does not exist!');
          }

          $patientId = $user->id;
          $patientDiagnosisList = UsersService::getPatientDiagnosisList($patientId);

          $diagnosisNames = [];
          for ($i = 0; $i < count($patientDiagnosisList); $i++) {
              $name = Diagnosis::findFirstById($patientDiagnosisList[$i])->name;
              $diagnosisNames[] = $name;
          }

          return ['data' => ['diagnosis_names' => $diagnosisNames], 
                  'message' => 'Successfully fetched user medical records!'];
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
                  $token = HashTokenHelper::generateRegistrationHashToken();
                  UsersService::createRegistrationLink($userId, $token);
                  $mailerHelper::sendMail($email, 'Registration confirmation at ISAPSW', 
                  '<h3>Please confirm your registration by clicking on the following link:<h3>
                  <a href="api.pswisa40/users/confirmRegistration?token=' . $token . '">LINK</a>');
              } else {
                  $result = 'registration denied!';
                  $mailerHelper::sendMail($email, 'Registration denied at ISAPSW', 
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

  public function userConfirmationRegistrationAction() {
    // user/confirmRegistration?token=dfn326wtsdftrua
    try {
      $hashToken = $this->request->get('token');
      $userId = RegistrationLinks::findFirstByToken($hashToken)->user_id;
      $user = Users::findFirstById($userId);  

      if ($user !== null && $user->reg_request == 'APPROVED') {
        UsersService::userConfirmationRegistration($user);
        echo 'You have successfully confirmed your registration!';
      } else {
        echo 'ERROR 404: ';
        throw new \Exception('Invalid URL.');
      }
        
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function getClinicsAction () {
    $errors = [];
    try {
      $doctors = $this->request->get('doctors');      
    }
    catch (ServiceException $e) {
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

  // data is the end result of the search and it gets changed after each point
  public function searchClinicsAction() {
        // ?date=2015-02-04T05%3A10%3A58%2B05%3A30&type=ONCOLOGY
        // &country=...&city=...
        // &rating=3
        $errors = [];
        try {
        // exact address is not needed
        $date =     $this->request->get('date'); // this date needs to be handled!
        $type =     $this->request->get('type');
        $country =  $this->request->get('country');
        $city =     $this->request->get('city');
        $rating =   $this->request->get('rating');

        // PARSE THE DATE INTO NEEDED FORM
        $date = str_replace("+", " ", $date);

        // MODELS
        $sqlHelper = new SQLHelper();
        $usersModel = new Users();
        $usersToDoctorSpecialty = new UsersToDoctorSpecialties();
        $doctorSpecialtiesModel = new DoctorSpecialties();
        $doctorRatingsModel = new DoctorRatings();
        $vacationsmodel = new Vacations();
        $clinicsModel = new Clinics();
        $clinicsAppointmentSlotsModel = new ClinicsAppointmentSlots();

        // FIND BY TYPE
        $specialtyId = $doctorSpecialtiesModel::findFirstBySpecialty($type)->id;
        $doctorsWithSpecialty = $usersToDoctorSpecialty::findBySpecialtyId($specialtyId);
        $data = $doctorsWithSpecialty->toArray();

        // FIND BY RATING - OPTIONAL
        if ($rating != null) {
            $searchByRatings = "SELECT DISTINCT DR.doctor_id FROM App\Models\DoctorRatings DR GROUP BY DR.doctor_id HAVING AVG(DR.rating)>=$rating";
            $ratingResults = $sqlHelper->createAndExecuteQuery($searchByRatings)->toArray();
            $ratingResultIds = [];

            foreach ($ratingResults as $ratingResult) {
            $ratingResultIds[] = $ratingResult['doctor_id'];
            }

            $doctorsWithOkRatings = [];
            foreach ($data as $doctor) {
            if (in_array($doctor['doctor_id'], $ratingResultIds)) {
                $doctorsWithOkRatings[] = $doctor;
            }
            }
            $data = $doctorsWithOkRatings;
        }
        
        // DOCTORS NOT ON VACATION
        $doctorsNotOnVacation = [];
        foreach ($data as $doctor) {
            $doctorVacations = Vacations::findByUsersId($doctor['doctor_id'])->toArray();
            $doctorId = $doctorVacations[0]['users_id'];
            $isOnVacation = false;
            foreach ($doctorVacations as $vacation) {
            if (CommonHelpers::check_in_range($vacation['start_date'], $vacation['end_date'], $date)) {
                $isOnVacation = true;
                break;
            }
            }
            // ...leaving this as is for the memes.
            if ($isOnVacation) { continue; }
            $doctorsNotOnVacation[] = $doctorId;
        }

        $data = $doctorsNotOnVacation;

        // DOCTORS BY AVAILABLE TIME
        // LET'S SAY THE APPOINTMENT LASTS FOR 1h, SO THIS ONLY LISTS APPOINTMENTS
        // WHERE THERE'S AT LEAST AN HOUR FREE

        // I HAVE NO CLUE WHAT I WROTE HERE... JESUS
        $availableDoctors = [];
        foreach ($data as $doctor) {
            $doctorsAppointments = $clinicsAppointmentSlotsModel::findByDoctorId($doctor)->toArray();
            $isAvailable = true; // BY DEFAULT, THE DOCTOR IS AVAILABLE
            foreach ($doctorsAppointments as $appointment) {

            // IF THE SELECTED DATE IS BETWEEN APPOINTMENTS
            // THEN EXIT IMMEDIATELY, THE DOCTOR IS NOT AVAILABLE
            if (CommonHelpers::check_in_range($appointment['start_date'], $appointment['end_date'], $date)) {
                $isAvailable = false; 
                break; 
            }

            // IF THE SELECTED DATE IS BIGGER THAN THE START DATE, DON'T LOOK FOR RESULTS
            if (strtotime($date) <= strtotime($appointment['start_date'])) {
                // FINALLY, CHECK IF THERE'S AN HOUR BETWEEN THE SELECTED DATE AND ALL APPOINTMENTS
                if (strtotime($appointment['start_date']) - strtotime($date) >= 3600) {
                continue;
                // IF THERE'S NOT, THE DOCTOR IS NOT AVAILABLE
                } else {
                $isAvailable = false; 
                break;
                }
            } else {
                continue;
            }
            }
            if ($isAvailable) {
            $availableDoctors[] = $doctor;
            }
        }

        $data = $availableDoctors;
        // ABOVE WORKS

        $data = $availableDoctors;
        $availableByCountry = [];
        $availableByCity = [];

        // FIND BY COUNTRY - OPTIONAL
        if ($country != null) {
            foreach ($data as $doctorId) {
                $clinicId = $clinicsAppointmentSlotsModel->findFirstByDoctorId($doctorId)->clinics_id;
                $countryName = Countries::findFirstById($clinicsModel->findFirstById($clinicId)->country_id)->country;

                if ($country == $countryName) {
                    $availableByCountry[] = $doctorId;
                }
            }

            $data = $availableByCountry;
        }

        // FIND BY CITY - OPTIONAL
        if ($city != null) {
            foreach ($data as $doctorId) {
                $clinicId = $clinicsAppointmentSlotsModel->findFirstByDoctorId($doctorId)->clinics_id;
                $cityName = Cities::findFirstById($clinicsModel->findFirstById($clinicId)->city_id)->city;

                if ($country == $countryName) {
                    $availableByCity[] = $doctorId;
                }
            }

            $data = $availableByCity;
        }

        // PREPARE DATA
        // Za svaku stavku rezultata prikazani su naziv klinike, prosečna ocena klinike, 
        // adresa klinike i cena pregleda

        // Za svakog lekara prikazano je njegovo ime i prezime, prosečna ocena i lista vremena kada
        // pacijent može da zakaže pregled za taj dan.

        return ['data' => [$data], 'message' => 'Successfully fetched search results'];
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
