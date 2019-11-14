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

    /*
    Registracija obuhvata unos
    email adrese, lozinke, imena, prezimena, adrese prebivališta, grada, države,
    broja telefona i jedinstveni broj osiguranika. Lozinka se unosi u dva polja da bi se
    otežalo pravljenje grešaka prilikom odabira nove lozinke. Nakon popunjavanja
    forme za registraciju, zahtev se šalje administratoru kliničkog centra na reviziju.
    Zahtev za registracijom administrator može da potvrdi ili odbije. Nakon
    odobravanja zahteva za registracijom, na datu email adresu se šalje link za
    aktivaciju korisnika. Korisnik ne može da se prijavi na aplikaciju dok se njegov
    nalog ne aktivira posećivanjem linka koji je dobio u emailu. Ukoliko je zahtev
    odbijen, korisniku se na email adresu šalje poruka da je zahtev odbijen uz kratku
    poruku gde administrator navodi razlog odbijanja zahteva.
    Napomena: potrebno je obezbediti bilo kakav mehanizam za autentifikaciju i
    autorizaciju korisnika na serverskoj strani.
    */
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
