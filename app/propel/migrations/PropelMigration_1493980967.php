<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1493980967.
 * Generated on 2017-05-05 12:42:47 by jeroenvisser
 */
class PropelMigration_1493980967
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

ALTER TABLE `cb_input` CHANGE `state` `state` INTEGER DEFAULT 0;

ALTER TABLE `controller_box` CHANGE `state` `state` INTEGER DEFAULT 0;

ALTER TABLE `device_group` CHANGE `state` `state` INTEGER DEFAULT 0;

ALTER TABLE `ds_temperature_sensor` CHANGE `state` `state` INTEGER DEFAULT 0;

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

ALTER TABLE `cb_input` CHANGE `state` `state` INTEGER;

ALTER TABLE `controller_box` CHANGE `state` `state` INTEGER;

ALTER TABLE `device_group` CHANGE `state` `state` INTEGER;

ALTER TABLE `ds_temperature_sensor` CHANGE `state` `state` INTEGER;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}