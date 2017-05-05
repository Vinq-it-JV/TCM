<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1493911440.
 * Generated on 2017-05-04 17:24:00 by jeroenvisser
 */
class PropelMigration_1493911440
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

DROP TABLE IF EXISTS `device_notification`;

DROP INDEX `cb_input_FI_4` ON `cb_input`;

DROP INDEX `controller_box_FI_3` ON `controller_box`;

CREATE TABLE `ds_temperature_notification`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `sensor` INTEGER,
    `temperature` VARCHAR(10) DEFAULT \'0\',
    `is_handled` TINYINT(1) DEFAULT 0,
    `handled_by` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `ds_temperature_notification_FI_1` (`handled_by`),
    INDEX `ds_temperature_notification_FI_2` (`sensor`)
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

DROP TABLE IF EXISTS `ds_temperature_notification`;

CREATE INDEX `cb_input_FI_4` ON `cb_input` (`notification`);

CREATE INDEX `controller_box_FI_3` ON `controller_box` (`notification`);

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

}