<?php

namespace UserBundle\Model;

use UserBundle\Model\om\BaseCountries;

class Countries extends BaseCountries
{
    const COUNTRY_GB = 8;

    /**
     * getCountryListArray()
     * @return mixed
     */
    static public function getCountryListArray()
    {
        $countries = CountriesQuery::create()
            ->orderByName('ASC')
            ->find();

        $countriesArr['countries'] = $countries->toArray();
        return $countriesArr;
    }
}
