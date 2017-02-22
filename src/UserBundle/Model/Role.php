<?php

namespace UserBundle\Model;

use UserBundle\Model\om\BaseRole;

class Role extends BaseRole
{
    const ROLE_USER = "ROLE.USER";
    const ROLE_ADMIN = "ROLE.ADMIN";
    const ROLE_SUPER_ADMIN = "ROLE.SUPER_ADMIN";

    const ROLE_USER_ID = 1;
    const ROLE_ADMIN_ID = 2;
    const ROLE_SUPER_ADMIN_ID = 3;

    const ROLE_USER_STYLE = 'label label-default';
    const ROLE_ADMIN_STYLE = 'label label-warning';
    const ROLE_SUPER_ADMIN_STYLE = 'label label-danger';

    /**
     * getRoleDataArray()
     * @return array
     */
    public function getRoleDataArray()
    {   $data = [];
        $data['role'] = $this->toArray();

        unset($data['role']['CreatedAt']);
        unset($data['role']['UpdatedAt']);

        return $data;
    }

    /**
     * getRoleTemplateArray()
     * @return array
     */
    public function getRoleTemplateArray()
    {
        $role = new Role();
        return $role->getRoleDataArray();
    }

    /**
     * getRoleListArray()
     * @return mixed
     */
    static public function getRoleListArray()
    {
        $roles = RoleQuery::create()
            ->orderByName('ASC')
            ->find();

        foreach ($roles as $role)
            $rolesArr['roles'][] = $role->getRoleDataArray()['role'];
        return $rolesArr;
    }


}
