<?php

namespace App\Models;

class UsersToUserRole extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('users_2_user_roles');
    }

    protected $users_id;
    protected $user_roles_id;
    protected $created_at;
    protected $updated_at;

    public function getUsersId()
    {
        return $this->users_id;
    }

    public function setUsersId($users_id)
    {
        $this->users_id = $users_id;
    }

    public function getUserRolesId()
    {
        return $this->user_roles_id;
    }

    public function setUserRolesId($user_roles_id)
    {
        $this->user_roles_id = $user_roles_id;
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
