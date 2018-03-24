<?php

namespace StoreBundle\Model;

use StoreBundle\Model\om\BaseMaintenanceType;

class MaintenanceType extends BaseMaintenanceType
{
    const TYPE_GENERAL_ID = 1;
    const TYPE_GENERAL_NAME = 'MAINTENANCE_TYPE.GENERAL.NAME';

    const TYPE_PERIODICALLY_ID = 2;
    const TYPE_PERIODICALLY_NAME = 'MAINTENANCE_TYPE.PERIODICALLY.NAME';
}
