<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1493910054.
 * Generated on 2017-05-04 17:00:54 by jeroenvisser
 */
class PropelMigration_1493910054
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
    ADD `notification` INTEGER AFTER `notify_after`;

CREATE INDEX `cb_input_FI_4` ON `cb_input` (`notification`);

ALTER TABLE `controller_box`
    ADD `notification` INTEGER AFTER `notify_after`;

CREATE INDEX `controller_box_FI_3` ON `controller_box` (`notification`);

ALTER TABLE `ds_temperature_sensor`
    ADD `notification` INTEGER AFTER `notify_after`;

CREATE INDEX `ds_temperature_sensor_FI_4` ON `ds_temperature_sensor` (`notification`);

CREATE TABLE `device_notification`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `temperature` VARCHAR(10) DEFAULT \'0\',
    `switch_state` TINYINT(1) DEFAULT 0,
    `is_handled` TINYINT(1) DEFAULT 0,
    `handled_by` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `device_notification_FI_1` (`handled_by`)
) ENGINE=MyISAM;

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

DROP TABLE IF EXISTS `device_notification`;

DROP INDEX `cb_input_FI_4` ON `cb_input`;

ALTER TABLE `cb_input` DROP `notification`;

DROP INDEX `controller_box_FI_3` ON `controller_box`;

ALTER TABLE `controller_box` DROP `notification`;

DROP INDEX `ds_temperature_sensor_FI_4` ON `ds_temperature_sensor`;

ALTER TABLE `ds_temperature_sensor` DROP `notification`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}