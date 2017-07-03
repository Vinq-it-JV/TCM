<?php

namespace CollectionBundle\Model;

use CollectionBundle\Model\om\BaseCollectionType;

class CollectionType extends BaseCollectionType
{
    const TYPE_MAINTENANCE_ID = 1;
    const TYPE_MAINTENANCE_NAME = "COLLECTION_TYPE.MAINTENANCE.NAME";
    const TYPE_MAINTENANCE_ICON = "fa fa-fw fa-wrench";
    const TYPE_MAINTENANCE_STYLE = "panel-warning";

    const TYPE_INVENTORY_ID = 2;
    const TYPE_INVENTORY_NAME = "COLLECTION_TYPE.INVENTORY.NAME";
    const TYPE_INVENTORY_ICON = "fa fa-fw fa-list";
    const TYPE_INVENTORY_STYLE = "panel-succes";
}
