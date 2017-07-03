<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1497944461.
 * Generated on 2017-06-20 09:41:01 by jeroenvisser
 */
class PropelMigration_1497944461
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

CREATE TABLE `collection_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `description` TEXT,
    `icon` VARCHAR(255),
    `style` VARCHAR(255),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE `collection`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `uid` VARCHAR(64),
    `type` INTEGER,
    `name` VARCHAR(255),
    `description` TEXT,
    `date` DATETIME,
    `is_published` TINYINT(1) DEFAULT 1,
    `is_deleted` TINYINT(1) DEFAULT 0,
    `collection_company` INTEGER,
    `collection_store` INTEGER,
    `created_by` INTEGER,
    `edited_by` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `collection_FI_1` (`type`),
    INDEX `collection_FI_2` (`collection_company`),
    INDEX `collection_FI_3` (`collection_store`),
    INDEX `collection_FI_4` (`created_by`),
    INDEX `collection_FI_5` (`edited_by`)
) ENGINE=MyISAM;

CREATE TABLE `collection_attachment`
(
    `collection_id` INTEGER NOT NULL,
    `attachment_id` INTEGER NOT NULL,
    PRIMARY KEY (`collection_id`,`attachment_id`),
    INDEX `collection_attachment_FI_2` (`attachment_id`)
) ENGINE=MyISAM;

CREATE TABLE `attachment`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `uid` VARCHAR(64),
    `original_name` VARCHAR(255),
    `name` VARCHAR(255),
    `filename` VARCHAR(255),
    `created_at` DATETIME,
    `updated_at` DATETIME,
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

DROP TABLE IF EXISTS `collection_type`;

DROP TABLE IF EXISTS `collection`;

DROP TABLE IF EXISTS `collection_attachment`;

DROP TABLE IF EXISTS `attachment`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}