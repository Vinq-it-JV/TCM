<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1493906722.
 * Generated on 2017-05-04 16:05:22 by jeroenvisser
 */
class PropelMigration_1493906722
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

ALTER TABLE `cb_input`
    ADD `notify_after` INTEGER DEFAULT 0 AFTER `position`;

ALTER TABLE `controller_box`
    ADD `state` INTEGER AFTER `position`,
    ADD `notify_after` INTEGER DEFAULT 0 AFTER `state`;

ALTER TABLE `ds_temperature_sensor`
    ADD `notify_after` INTEGER DEFAULT 0 AFTER `position`;

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

ALTER TABLE `cb_input` DROP `notify_after`;

ALTER TABLE `controller_box` DROP `state`;

ALTER TABLE `controller_box` DROP `notify_after`;

ALTER TABLE `ds_temperature_sensor` DROP `notify_after`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}