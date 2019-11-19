<?php

namespace App\Models;

class UserRoleToRolePermission extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('user_role_2_role_permissions');
    }

    protected $user_roles_id;
    protected $role_permissions_id;
    protected $created_at;
    protected $updated_at;

    public function getUserRolesId()
    {
        return $this->user_roles_id;
    }

    public function setUserRolesId($user_roles_id)
    {
        $this->user_roles_id = $user_roles_id;
    }

    public function getRolePermissionsId()
    {
        return $this->role_permissions_id;
    }

    public function setRolePermissionsId($role_permissions_id)
    {
        $this->role_permissions_id = $role_permissions_id;
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
