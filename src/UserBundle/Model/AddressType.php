<?php

namespace UserBundle\Model;

use UserBundle\Model\om\BaseAddressType;

class AddressType extends BaseAddressType
{
    const POST_NAME = "ADDRESS_TYPE.POST.NAME";
    const POST_DESCRIPTION = "ADDRESS_TYPE.POST.DESCRIPTION";

    const VISIT_NAME = "ADDRESS_TYPE.VISIT.NAME";
    const VISIT_DESCRIPTION = "ADDRESS_TYPE.VISIT.DESCRIPTION";

    const INVOICE_NAME = "ADDRESS_TYPE.INVOICE.NAME";
    const INVOICE_DESCRIPTION = "ADDRESS_TYPE.INVOICE.DESCRIPTION";

    /**
     * getAddressTypeListArray()
     * @return mixed
     */
    static public function getAddressTypeListArray()
    {
        $types = AddressTypeQuery::create()
            ->orderByName('ASC')
            ->find();

        $typeArr['address_type'] = $types->toArray();
        return $typeArr;
    }
}
