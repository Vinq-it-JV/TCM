<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1491988871.
 * Generated on 2017-04-12 11:21:11 by jeroenvisser
 */
class PropelMigration_1491988871
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

ALTER TABLE `device_group` DROP `animation`;

ALTER TABLE `ds_temperature_sensor` CHANGE `low_limit` `low_limit` VARCHAR(10) DEFAULT \'0\';

ALTER TABLE `ds_temperature_sensor` CHANGE `temperature` `temperature` VARCHAR(10) DEFAULT \'0\';

ALTER TABLE `ds_temperature_sensor` CHANGE `high_limit` `high_limit` VARCHAR(10) DEFAULT \'30\';

CREATE TABLE `cb_input`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `uid` VARCHAR(32),
    `group` INTEGER,
    `controller` INTEGER,
    `main_store` INTEGER,
    `name` VARCHAR(255),
    `description` TEXT,
    `state` INTEGER,
    `switch_when` TINYINT(1) DEFAULT 1,
    `switch_state` TINYINT(1) DEFAULT 0,
    `position` INTEGER,
    `is_enabled` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `cb_input_FI_1` (`main_store`),
    INDEX `cb_input_FI_2` (`controller`),
    INDEX `cb_input_FI_3` (`group`)
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

DROP TABLE IF EXISTS `cb_input`;

ALTER TABLE `device_group`
    ADD `animation` VARCHAR(255) AFTER `state`;

ALTER TABLE `ds_temperature_sensor` CHANGE `low_limit` `low_limit` VARCHAR(10);

ALTER TABLE `ds_temperature_sensor` CHANGE `temperature` `temperature` VARCHAR(10);

ALTER TABLE `ds_temperature_sensor` CHANGE `high_limit` `high_limit` VARCHAR(10);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}