<?php

namespace StoreBundle\Model;

use StoreBundle\Model\om\BaseStoreImage;

class StoreImage extends BaseStoreImage
{
    /**
     * getStoreImageDataArray()
     * @return array
     */
    public function getStoreImageDataArray()
    {
        $data = [];
        $data['storeimage'] = $this->toArray();

        unset($data['storeimage']['CreatedAt']);
        unset($data['storeimage']['UpdatedAt']);

        return $data;
    }

}
