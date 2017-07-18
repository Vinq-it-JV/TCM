<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1500372661.
 * Generated on 2017-07-18 12:11:01 by jeroenvisser
 */
class PropelMigration_1500372661
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

ALTER TABLE `company`
    ADD `uid` VARCHAR(64) AFTER `id`;

ALTER TABLE `store`
    ADD `uid` VARCHAR(64) AFTER `id`,
    ADD `image` INTEGER AFTER `description`;

CREATE INDEX `store_FI_4` ON `store` (`image`);

CREATE TABLE `store_image`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `uid` VARCHAR(64),
    `original_name` VARCHAR(255),
    `name` VARCHAR(255),
    `link_url` VARCHAR(255),
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

DROP TABLE IF EXISTS `store_image`;

ALTER TABLE `company` DROP `uid`;

DROP INDEX `store_FI_4` ON `store`;

ALTER TABLE `store` DROP `uid`;

ALTER TABLE `store` DROP `image`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}