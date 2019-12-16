<?php

namespace App\Models;

class Clinics extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('clinics');

    }

    protected $id;
    protected $clinic_center_id;
    protected $name;
    protected $address;
    protected $city_id;
    protected $country_id;
    protected $description;
    protected $created_at;
    protected $updated_at;

    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getClinicCenterId()
    {
        return $this->clinic_center_id;
    }

    public function setClinicCenterId($clinic_center_id)
    {
        $this->clinic_center_id = $clinic_center_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getCityId()
    {
        return $this->city_id;
    }

    public function setCityId($city_id)
    {
        $this->city_id = $city_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($Description)
    {
        $this->description = $description;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getUpdateAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
}
