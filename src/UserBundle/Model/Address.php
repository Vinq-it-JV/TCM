<?php

namespace UserBundle\Model;

use UserBundle\Model\om\BaseAddress;

class Address extends BaseAddress
{
    /**
     * getAddressDataArray()
     * @return array
     */
    public function getAddressDataArray()
    {   $data = [];
        $data['address'] = $this->toArray();

        unset($data['address']['CreatedAt']);
        unset($data['address']['UpdatedAt']);

        return $data;
    }

    /**
     * getType()
     * @return array|int|AddressType
     */
    public function getType()
    {
        $type = parent::getType();
        if (is_numeric($type))
            $type = AddressTypeQuery::create()->findOneById($type);
        if (empty($type))
            $type = AddressTypeQuery::create()->findOneById(AddressType::POST_NAME);
        if (!empty($type))
            $type = $type->toArray();
        return $type;
    }

    /**
     * getCountry()
     * @return mixed
     */
    public function getCountry()
    {
        $country = parent::getCountry();
        if (is_numeric($country))
            $country = CountriesQuery::create()->findOneById($country);
        if (empty($country))
            $country = CountriesQuery::create()->findOneById(Countries::COUNTRY_GB);
        if (!empty($country))
            $country = $country->toArray();
        return $country;
    }

    /**
     * getAddressTemplateArray()
     * @return array
     */
    public function getAddressTemplateArray()
    {
        $address = new Address();
        return $address->getAddressDataArray();
    }
}
