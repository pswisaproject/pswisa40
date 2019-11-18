<?php

namespace App\Helpers;

class SQLHelper extends \Phalcon\Mvc\Model
{
    public function createAndExecuteQuery($phqlparam)
    {
        $modelsManager = $this->getModelsManager();
        $phql          = $phqlparam;
        $query         = $modelsManager->createQuery($phql);
        $result        = $query->execute();

        return $result;
    }

    public function isUserAuthorized($id, $permissionParam)
    {
        $modelsManager = $this->getModelsManager();

        $phql = 'SELECT RP.roles_permissions FROM App\Models\Users U
                          LEFT OUTER JOIN App\Models\UsersToUserRole U2UR ON U.id = U2UR.users_id
                          LEFT OUTER JOIN App\Models\UserRoles UR ON U2UR.user_roles_id = UR.id
                          LEFT OUTER JOIN App\Models\UserRoleToRolePermission UR2RP ON UR.id = UR2RP.user_roles_id
                          LEFT OUTER JOIN App\Models\RolesPermissions RP ON UR2RP.role_permissions_id = RP.id
                          WHERE U.id = ' . $id;

        $query       = $modelsManager->createQuery($phql);
        $permissions = $query->execute()->toArray();

        // check if user is authorized
        $isAuthorized = false;
        foreach ($permissions as $permission) {
            if (in_array($permissionParam, $permission)) {
                $isAuthorized = true;
            }
        }

        return $isAuthorized;
    }
}
