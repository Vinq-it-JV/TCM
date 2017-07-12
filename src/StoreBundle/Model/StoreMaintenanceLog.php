<?php

namespace StoreBundle\Model;

use PropelPDO;
use StoreBundle\Model\om\BaseStoreMaintenanceLog;
use UserBundle\Model\UserQuery;

class StoreMaintenanceLog extends BaseStoreMaintenanceLog
{
    /**
     * getStoreDataArray()
     * @return array
     */
    public function getStoreMaintenanceLogDataArray()
    {
        $data = [];
        $data['maintenancelog'] = $this->toArray();

        return $data;
    }

    /**
     * getHandledBy()
     * @return int|\UserBundle\Model\User
     */
    public function getMaintenanceBy()
    {
        $user = parent::getMaintenanceBy();
        if (is_numeric($user))
            $user = UserQuery::create()->findOneById($user);
        if (!empty($user))
            $user = $user->getUserDataArray()['user'];
        return $user;
    }

    /**
     * getMaintenanceStore()
     * @return int|Store
     */
    public function getMaintenanceStore()
    {
        $store = parent::getMaintenanceStore();
        if (is_numeric($store))
            $store = StoreQuery::create()->findOneById($store);
        if (!empty($store))
            $store = $store->getStoreDataArray()['store'];
        return $store;
    }

    /**
     * getType()
     * @return int|MaintenanceType
     */
    public function getType()
    {
        $type = parent::getType();
        if (is_numeric($type))
            $type = MaintenanceTypeQuery::create()->findOneById($type);
        if (!empty($type))
            $type = $type->toArray();
        return $type;
    }
}
