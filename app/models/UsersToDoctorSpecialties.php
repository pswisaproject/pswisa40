<?php

namespace App\Models;

class UsersToDoctorSpecialties extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('users_2_doctor_specialties');

    }

    protected $doctor_id;
    protected $specialty_id;
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

    public function getDoctorId()
    {
        return $this->doctor_id;
    }

    public function setDoctorId($doctor_id)
    {
        $this->doctor_id = $doctor_id;
    }

    public function getSpecialtyId()
    {
        return $this->specialty_id;
    }

    public function setSpecialtyId($specialty_id)
    {
        $this->specialty_id = $specialty_id;
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
