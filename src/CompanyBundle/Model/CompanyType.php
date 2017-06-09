<?php

namespace CompanyBundle\Model;

use CompanyBundle\Model\om\BaseCompanyType;

class CompanyType extends BaseCompanyType
{
    const BAR_ID = 1;
    const BAR_NAME = "COMPANY_TYPE.BAR.NAME";
    const BAR_DESCRIPTION = "COMPANY_TYPE.BAR.DESCRIPTION";

    const SERVICE_ID = 2;
    const SERVICE_NAME = "COMPANY_TYPE.SERVICE.NAME";
    const SERVICE_DESCRIPTION = "COMPANY_TYPE.SERVICE.DESCRIPTION";

    const RESTAURANT_ID = 3;
    const RESTAURANT_NAME = "COMPANY_TYPE.RESTAURANT.NAME";
    const RESTAURANT_DESCRIPTION = "COMPANY_TYPE.RESTAURANT.DESCRIPTION";

    const BRASSERIE_ID = 4;
    const BRASSERIE_NAME = "COMPANY_TYPE.BRASSERIE.NAME";
    const BRASSERIE_DESCRIPTION = "COMPANY_TYPE.BRASSERIE.DESCRIPTION";

    const EATERY_ID = 5;
    const EATERY_NAME = "COMPANY_TYPE.EATERY.NAME";
    const EATERY_DESCRIPTION = "COMPANY_TYPE.EATERY.DESCRIPTION";

    const CINEMA_ID = 6;
    const CINEMA_NAME = "COMPANY_TYPE.CINEMA.NAME";
    const CINEMA_DESCRIPTION = "COMPANY_TYPE.CINEMA.DESCRIPTION";

    const HOTEL_ID = 7;
    const HOTEL_NAME = "COMPANY_TYPE.HOTEL.NAME";
    const HOTEL_DESCRIPTION = "COMPANY_TYPE.HOTEL.DESCRIPTION";

    const NIGHTSPOT_ID = 8;
    const NIGHTSPOT_NAME = "COMPANY_TYPE.NIGHTSPOT.NAME";
    const NIGHTSPOT_DESCRIPTION = "COMPANY_TYPE.NIGHTSPOT.DESCRIPTION";

    const PARENT_ID = 9;
    const PARENT_NAME = "COMPANY_TYPE.PARENT.NAME";
    const PARENT_DESCRIPTION = "COMPANY_TYPE.PARENT.DESCRIPTION";

    /**
     * getCompanyTypeListArray()
     * @return mixed
     */
    static public function getCompanyTypeListArray()
    {
        $types = CompanyTypeQuery::create()
            ->orderByName('ASC')
            ->find();

        $typeArr['company_type'] = $types->toArray();
        return $typeArr;
    }
}
