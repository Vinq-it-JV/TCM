<?php

namespace UserBundle\Model;

use UserBundle\Model\om\BaseRole;

class Role extends BaseRole
{
    const ROLE_USER = "User";
    const ROLE_VIP_USER = "VIP user";
    const ROLE_BOOKMAKER = "Bookmaker";
    const ROLE_AFFILIATE = "Affiliate";
    const ROLE_ADMIN = "Admin";
    const ROLE_SUPER_ADMIN = "Super admin";

    const ROLE_USER_ID = 1;
    const ROLE_VIP_USER_ID = 2;
    const ROLE_BOOKMAKER_ID = 3;
    const ROLE_AFFILIATE_ID = 4;
    const ROLE_ADMIN_ID = 5;
    const ROLE_SUPER_ADMIN_ID = 6;
}
