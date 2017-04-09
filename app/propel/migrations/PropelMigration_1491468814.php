<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1491468814.
 * Generated on 2017-04-06 10:53:34 by jeroenvisser
 */
class PropelMigration_1491468814
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

CREATE TABLE `store`
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
    `bank_account_number` VARCHAR(50),
    `vat_number` VARCHAR(255),
    `coc_number` VARCHAR(255),
    `is_enabled` TINYINT(1) DEFAULT 1,
    `is_deleted` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `store_FI_1` (`type`),
    INDEX `store_FI_2` (`region`)
) ENGINE=MyISAM;

CREATE TABLE `store_address`
(
    `store_id` INTEGER NOT NULL,
    `address_id` INTEGER NOT NULL,
    PRIMARY KEY (`store_id`,`address_id`),
    INDEX `store_address_FI_2` (`address_id`)
) ENGINE=MyISAM;

CREATE TABLE `store_email`
(
    `store_id` INTEGER NOT NULL,
    `email_id` INTEGER NOT NULL,
    PRIMARY KEY (`store_id`,`email_id`),
    INDEX `store_email_FI_2` (`email_id`)
) ENGINE=MyISAM;

CREATE TABLE `store_phone`
(
    `store_id` INTEGER NOT NULL,
    `phone_id` INTEGER NOT NULL,
    PRIMARY KEY (`store_id`,`phone_id`),
    INDEX `store_phone_FI_2` (`phone_id`)
) ENGINE=MyISAM;

CREATE TABLE `store_contact`
(
    `store_id` INTEGER NOT NULL,
    `contact_id` INTEGER NOT NULL,
    PRIMARY KEY (`store_id`,`contact_id`),
    INDEX `store_contact_FI_2` (`contact_id`)
) ENGINE=MyISAM;

CREATE TABLE `store_informant`
(
    `store_id` INTEGER NOT NULL,
    `informant_id` INTEGER NOT NULL,
    PRIMARY KEY (`store_id`,`informant_id`),
    INDEX `store_informant_FI_2` (`informant_id`)
) ENGINE=MyISAM;

CREATE TABLE `store_owner`
(
    `store_id` INTEGER NOT NULL,
    `owner_id` INTEGER NOT NULL,
    PRIMARY KEY (`store_id`,`owner_id`),
    INDEX `store_owner_FI_2` (`owner_id`)
) ENGINE=MyISAM;

CREATE TABLE `store_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
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

DROP TABLE IF EXISTS `store`;

DROP TABLE IF EXISTS `store_address`;

DROP TABLE IF EXISTS `store_email`;

DROP TABLE IF EXISTS `store_phone`;

DROP TABLE IF EXISTS `store_contact`;

DROP TABLE IF EXISTS `store_informant`;

DROP TABLE IF EXISTS `store_owner`;

DROP TABLE IF EXISTS `store_type`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}