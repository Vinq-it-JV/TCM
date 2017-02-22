<?php

namespace UserBundle\Model;

use UserBundle\Model\om\BaseUserGender;

class UserGender extends BaseUserGender
{
    const MALE_ID = 1;
    const MALE = "GENDER.MALE";
    const MALE_SHORT = "GENDER.MALE_SHORT";

    const FEMALE_ID = 2;
    const FEMALE = "GENDER.FEMALE";
    const FEMALE_SHORT = "GENDER.FEMALE_SHORT";

    const OTHER_ID = 3;
    const OTHER = "GENDER.OTHER";
    const OTHER_SHORT = "GENDER.OTHER_SHORT";

    /**
     * getGenderListArray()
     * @return mixed
     */
    static public function getGenderListArray()
    {
        $genders = UserGenderQuery::create()->find();

        $genderArr['gender'] = $genders->toArray();
        return $genderArr;
    }
}
