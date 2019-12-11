<?php

namespace App\Models;

class UsersToClinic extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('users_2_clinic');

    }

    protected $users_id;
    protected $clinic_id;
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

    public function getUsersId()
    {
        return $this->users_id;
    }

    public function setUsersId($users_id)
    {
        $this->users_id = $users_id;
    }

    public function getClinicId()
    {
        return $this->clinic_id;
    }

    public function setClinicId($clinic_id)
    {
        $this->clinic_id = $clinic_id;
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
