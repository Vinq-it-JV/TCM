<?php

namespace StoreBundle\Model;

use StoreBundle\Model\om\BaseStoreType;

class StoreType extends BaseStoreType
{
    const BAR_NAME = "STORE_TYPE.BAR.NAME";
    const BAR_DESCRIPTION = "STORE_TYPE.BAR.DESCRIPTION";
    const RESTAURANT_NAME = "STORE_TYPE.RESTAURANT.NAME";
    const RESTAURANT_DESCRIPTION = "STORE_TYPE.RESTAURANT.DESCRIPTION";
    const BRASSERIE_NAME = "STORE_TYPE.BRASSERIE.NAME";
    const BRASSERIE_DESCRIPTION = "STORE_TYPE.BRASSERIE.DESCRIPTION";
    const EATERY_NAME = "STORE_TYPE.EATERY.NAME";
    const EATERY_DESCRIPTION = "STORE_TYPE.EATERY.DESCRIPTION";
    const CINEMA_NAME = "STORE_TYPE.CINEMA.NAME";
    const CINEMA_DESCRIPTION = "STORE_TYPE.CINEMA.DESCRIPTION";
    const HOTEL_NAME = "STORE_TYPE.HOTEL.NAME";
    const HOTEL_DESCRIPTION = "STORE_TYPE.HOTEL.DESCRIPTION";
    const NIGHTSPOT_NAME = "STORE_TYPE.NIGHTSPOT.NAME";
    const NIGHTSPOT_DESCRIPTION = "STORE_TYPE.NIGHTSPOT.DESCRIPTION";

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
