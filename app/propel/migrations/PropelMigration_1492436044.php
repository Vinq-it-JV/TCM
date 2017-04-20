<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1492436044.
 * Generated on 2017-04-17 15:34:04 by jeroenvisser
 */
class PropelMigration_1492436044
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `cb_input` CHANGE `position` `position` INTEGER DEFAULT 0;

ALTER TABLE `controller_box` CHANGE `name` `name` VARCHAR(255) DEFAULT \'Controller box\';

ALTER TABLE `controller_box` CHANGE `position` `position` INTEGER DEFAULT 0;

ALTER TABLE `device_group` CHANGE `position` `position` INTEGER DEFAULT 0;

ALTER TABLE `ds_temperature_sensor` CHANGE `position` `position` INTEGER DEFAULT 0;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `cb_input` CHANGE `position` `position` INTEGER;

ALTER TABLE `controller_box` CHANGE `name` `name` VARCHAR(255) DEFAULT \'Controller\';

ALTER TABLE `controller_box` CHANGE `position` `position` INTEGER;

ALTER TABLE `device_group` CHANGE `position` `position` INTEGER;

ALTER TABLE `ds_temperature_sensor` CHANGE `position` `position` INTEGER;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}