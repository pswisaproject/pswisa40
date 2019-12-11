<?php

namespace App\Models;

class DoctorSpecialties extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('doctor_specialties');

    }

    protected $id;
    protected $specialty;
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

    public function getSpecialty()
    {
        return $this->specialty;
    }

    public function setSpecialty($specialty)
    {
        $this->specialty = $specialty;
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
