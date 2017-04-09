<?php

namespace CompanyBundle\Model;

use CompanyBundle\Model\om\BaseRegions;

class Regions extends BaseRegions
{
    /**
     * getRegionDataArray()
     * @return array
     */
    public function getRegionDataArray()
    {   $data = [];
        $data['region'] = $this->toArray();
        $data['region']['Name'] = $this->getName();

        return $data;
    }

    /**
     * getRegionsListArray()
     * @return mixed
     */
    static public function getRegionsListArray()
    {
        $regions = RegionsQuery::create()
            ->orderByName('ASC')
            ->find();

        $regionsArr['regions'] = $regions->toArray();
        return $regionsArr;
    }

    /**
     * getRegionTemplateArray()
     * @return array
     */
    public function getRegionTemplateArray()
    {
        $region = new Regions();
        return $region->getRegionDataArray();
    }

    /**
     * getFullRegionTemplateArray()
     * @return array
     */
    public function getFullRegionTemplateArray()
    {
        $data = [];
        $data = array_merge($data, $this->getRegionTemplateArray());
        return $data;
    }

}
