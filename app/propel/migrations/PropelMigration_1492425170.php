<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1492425170.
 * Generated on 2017-04-17 12:32:50 by jeroenvisser
 */
class PropelMigration_1492425170
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

ALTER TABLE `cb_input` CHANGE `name` `name` VARCHAR(255) DEFAULT \'Input\';

ALTER TABLE `controller_box` CHANGE `name` `name` VARCHAR(255) DEFAULT \'Controller\';

ALTER TABLE `ds_temperature_sensor` CHANGE `name` `name` VARCHAR(255) DEFAULT \'Temperature\';

ALTER TABLE `ds_temperature_sensor`
    ADD `controller` INTEGER AFTER `group`,
    ADD `output_number` INTEGER AFTER `main_store`;

CREATE INDEX `ds_temperature_sensor_FI_3` ON `ds_temperature_sensor` (`controller`);

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

ALTER TABLE `cb_input` CHANGE `name` `name` VARCHAR(255);

ALTER TABLE `controller_box` CHANGE `name` `name` VARCHAR(255);

DROP INDEX `ds_temperature_sensor_FI_3` ON `ds_temperature_sensor`;

ALTER TABLE `ds_temperature_sensor` CHANGE `name` `name` VARCHAR(255);

ALTER TABLE `ds_temperature_sensor` DROP `controller`;

ALTER TABLE `ds_temperature_sensor` DROP `output_number`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}