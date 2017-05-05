<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1493981864.
 * Generated on 2017-05-05 12:57:44 by jeroenvisser
 */
class PropelMigration_1493981864
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

ALTER TABLE `cb_input` CHANGE `data_colleced_at` `data_collected_at` DATETIME;

ALTER TABLE `controller_box` CHANGE `data_colleced_at` `data_collected_at` DATETIME;

ALTER TABLE `ds_temperature_sensor` CHANGE `data_colleced_at` `data_collected_at` DATETIME;

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

ALTER TABLE `cb_input` CHANGE `data_collected_at` `data_colleced_at` DATETIME;

ALTER TABLE `controller_box` CHANGE `data_collected_at` `data_colleced_at` DATETIME;

ALTER TABLE `ds_temperature_sensor` CHANGE `data_collected_at` `data_colleced_at` DATETIME;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}