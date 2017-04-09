<?php

namespace StoreBundle\Model;

use StoreBundle\Model\om\BaseStoreType;

class StoreType extends BaseStoreType
{
    const BAR_NAME = "STORE_TYPE.BAR.NAME";
    const BAR_DESCRIPTION = "STORE_TYPE.BAR.DESCRIPTION";

    /**
     * getStoreTypeListArray()
     * @return mixed
     */
    static public function getStoreTypeListArray()
    {
        $types = StoreTypeQuery::create()
            ->orderByName('ASC')
            ->find();

        $typeArr['store_type'] = $types->toArray();
        return $typeArr;
    }
}
