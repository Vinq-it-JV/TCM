<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1491735339.
 * Generated on 2017-04-09 12:55:39 by jeroenvisser
 */
class PropelMigration_1491735339
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

CREATE TABLE `controller_box`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `group` INTEGER,
    `main_store` INTEGER,
    `version` INTEGER,
    `name` VARCHAR(255),
    `description` TEXT,
    `inputs` INTEGER,
    `internal_temperature` VARCHAR(10),
    `uid` VARCHAR(32),
    `position` INTEGER,
    `is_enabled` TINYINT(1) DEFAULT 1,
    `is_deleted` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `controller_box_FI_1` (`main_store`),
    INDEX `controller_box_FI_2` (`group`)
) ENGINE=MyISAM;

CREATE TABLE `device_group`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `main_store` INTEGER,
    `name` VARCHAR(255),
    `description` TEXT,
    `state` INTEGER,
    `animation` VARCHAR(255),
    `position` INTEGER,
    `is_enabled` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `tree_left` INTEGER,
    `tree_right` INTEGER,
    `tree_level` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `device_group_FI_1` (`main_store`)
) ENGINE=MyISAM;

CREATE TABLE `ds_temperature_sensor`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `uid` VARCHAR(32),
    `group` INTEGER,
    `main_store` INTEGER,
    `name` VARCHAR(255),
    `description` TEXT,
    `state` INTEGER,
    `low_limit` VARCHAR(10),
    `temperature` VARCHAR(10),
    `high_limit` VARCHAR(10),
    `position` INTEGER,
    `is_enabled` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `ds_temperature_sensor_FI_1` (`main_store`),
    INDEX `ds_temperature_sensor_FI_2` (`group`)
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

DROP TABLE IF EXISTS `controller_box`;

DROP TABLE IF EXISTS `device_group`;

DROP TABLE IF EXISTS `ds_temperature_sensor`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}