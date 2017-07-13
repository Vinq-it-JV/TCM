<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1499945207.
 * Generated on 2017-07-13 13:26:47 by jeroenvisser
 */
class PropelMigration_1499945207
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

CREATE TABLE `ds_temperature_sensor_log`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `sensor` INTEGER,
    `low_limit` VARCHAR(10) DEFAULT \'0\',
    `temperature` VARCHAR(10) DEFAULT \'0\',
    `high_limit` VARCHAR(10) DEFAULT \'30\',
    `raw_data` VARCHAR(10) DEFAULT \'0\',
    `data_collected_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `ds_temperature_sensor_log_FI_1` (`sensor`)
) ENGINE=MyISAM;

CREATE TABLE `cb_input_log`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `input` INTEGER,
    `switch_state` TINYINT(1) DEFAULT 0,
    `switch_when` TINYINT(1) DEFAULT 1,
    `raw_data` VARCHAR(10) DEFAULT \'0\',
    `data_collected_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `cb_input_log_FI_1` (`input`)
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

DROP TABLE IF EXISTS `ds_temperature_sensor_log`;

DROP TABLE IF EXISTS `cb_input_log`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}