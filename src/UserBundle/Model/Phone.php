<?php

namespace UserBundle\Model;

use UserBundle\Model\om\BasePhone;

class Phone extends BasePhone
{
    /**
     * getPhoneDataArray
     * @return array
     */
    public function getPhoneDataArray()
    {   $data = [];
        $data['phone'] = $this->toArray();

        unset($data['phone']['CreatedAt']);
        unset($data['phone']['UpdatedAt']);

        return $data;
    }

    /**
     * getPhoneTemplateArray
     * @return array
     */
    public function getPhoneTemplateArray()
    {
        $phone = new Phone();
        return $phone->getPhoneDataArray();
    }
}
