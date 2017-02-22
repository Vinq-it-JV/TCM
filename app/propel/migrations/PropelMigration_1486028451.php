<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1486028451.
 * Generated on 2017-02-02 10:40:51 by jeroenvisser
 */
class PropelMigration_1486028451
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

CREATE TABLE `user_address`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    `address_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `user_address_FI_1` (`user_id`),
    INDEX `user_address_FI_2` (`address_id`)
) ENGINE=MyISAM;

CREATE TABLE `address`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `type` INTEGER,
    `street_name` VARCHAR(255),
    `house_number` VARCHAR(25),
    `house_number_suffix` VARCHAR(25),
    `extra_info` VARCHAR(255),
    `postal_code` VARCHAR(25),
    `city` VARCHAR(255),
    `country` INTEGER,
    `map_url` VARCHAR(255),
    PRIMARY KEY (`id`),
    INDEX `address_FI_1` (`country`)
) ENGINE=MyISAM;

CREATE TABLE `countries`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `country_code` VARCHAR(25),
    `flag` VARCHAR(25),
    PRIMARY KEY (`id`)
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

DROP TABLE IF EXISTS `user_address`;

DROP TABLE IF EXISTS `address`;

DROP TABLE IF EXISTS `countries`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}