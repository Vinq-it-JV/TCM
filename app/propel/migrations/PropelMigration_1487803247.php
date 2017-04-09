<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1487803247.
 * Generated on 2017-02-22 23:40:47 by jeroenvisser
 */
class PropelMigration_1487803247
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

CREATE TABLE `company`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `description` TEXT,
    `type` INTEGER,
    `code` VARCHAR(25),
    `website` VARCHAR(255),
    `region` INTEGER,
    `remarks` TEXT,
    `payment_method` INTEGER,
    `vat_number` VARCHAR(255),
    `coc_number` VARCHAR(255),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `company_FI_1` (`type`),
    INDEX `company_FI_2` (`region`)
) ENGINE=MyISAM;

CREATE TABLE `company_address`
(
    `company_id` INTEGER NOT NULL,
    `address_id` INTEGER NOT NULL,
    PRIMARY KEY (`company_id`,`address_id`),
    INDEX `company_address_FI_2` (`address_id`)
) ENGINE=MyISAM;

CREATE TABLE `company_email`
(
    `company_id` INTEGER NOT NULL,
    `email_id` INTEGER NOT NULL,
    PRIMARY KEY (`company_id`,`email_id`),
    INDEX `company_email_FI_2` (`email_id`)
) ENGINE=MyISAM;

CREATE TABLE `company_phone`
(
    `company_id` INTEGER NOT NULL,
    `phone_id` INTEGER NOT NULL,
    PRIMARY KEY (`company_id`,`phone_id`),
    INDEX `company_phone_FI_2` (`phone_id`)
) ENGINE=MyISAM;

CREATE TABLE `company_contact`
(
    `company_id` INTEGER NOT NULL,
    `user_id` INTEGER NOT NULL,
    PRIMARY KEY (`company_id`,`user_id`),
    INDEX `company_contact_FI_2` (`user_id`)
) ENGINE=MyISAM;

CREATE TABLE `company_informant`
(
    `company_id` INTEGER NOT NULL,
    `user_id` INTEGER NOT NULL,
    PRIMARY KEY (`company_id`,`user_id`),
    INDEX `company_informant_FI_2` (`user_id`)
) ENGINE=MyISAM;

CREATE TABLE `company_owner`
(
    `company_id` INTEGER NOT NULL,
    `user_id` INTEGER NOT NULL,
    PRIMARY KEY (`company_id`,`user_id`),
    INDEX `company_owner_FI_2` (`user_id`)
) ENGINE=MyISAM;

CREATE TABLE `company_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name_short` VARCHAR(25),
    `name` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE `payment_method`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `description` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE `regions`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(25),
    `name` VARCHAR(255),
    `description` VARCHAR(255),
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

DROP TABLE IF EXISTS `company`;

DROP TABLE IF EXISTS `company_address`;

DROP TABLE IF EXISTS `company_email`;

DROP TABLE IF EXISTS `company_phone`;

DROP TABLE IF EXISTS `company_contact`;

DROP TABLE IF EXISTS `company_informant`;

DROP TABLE IF EXISTS `company_owner`;

DROP TABLE IF EXISTS `company_type`;

DROP TABLE IF EXISTS `payment_method`;

DROP TABLE IF EXISTS `regions`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}