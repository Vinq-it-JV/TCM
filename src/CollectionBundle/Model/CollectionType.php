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

    const TYPE_BEER_TECH_ID = 3;
    const TYPE_BEER_TECH_NAME = "COLLECTION_TYPE.BEER_TECH.NAME";
    const TYPE_BEER_TECH_ICON = "fa fa-fw fa-beer";
    const TYPE_BEER_TECH_STYLE = "panel-default";

    const TYPE_WINE_TECH_ID = 4;
    const TYPE_WINE_TECH_NAME = "COLLECTION_TYPE.WINE_TECH.NAME";
    const TYPE_WINE_TECH_ICON = "fa fa-fw fa-glass";
    const TYPE_WINE_TECH_STYLE = "panel-danger";

    const TYPE_POSTMIX_TECH_ID = 5;
    const TYPE_POSTMIX_TECH_NAME = "COLLECTION_TYPE.POSTMIX_TECH.NAME";
    const TYPE_POSTMIX_TECH_ICON = "fa fa-fw fa-share";
    const TYPE_POSTMIX_TECH_STYLE = "panel-primary";

    const TYPE_COOL_TECH_ID = 6;
    const TYPE_COOL_TECH_NAME = "COLLECTION_TYPE.COOL_TECH.NAME";
    const TYPE_COOL_TECH_ICON = "fa fa-fw fa-snowflake-o";
    const TYPE_COOL_TECH_STYLE = "panel-info";
}
