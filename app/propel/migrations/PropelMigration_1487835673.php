<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1487835673.
 * Generated on 2017-02-23 08:41:13 by jeroenvisser
 */
class PropelMigration_1487835673
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

ALTER TABLE `company_contact` DROP PRIMARY KEY;

DROP INDEX `company_contact_FI_2` ON `company_contact`;

ALTER TABLE `company_contact` CHANGE `user_id` `contact_id` INTEGER NOT NULL;

ALTER TABLE `company_contact` ADD PRIMARY KEY (`company_id`,`contact_id`);

CREATE INDEX `company_contact_FI_2` ON `company_contact` (`contact_id`);

ALTER TABLE `company_informant` DROP PRIMARY KEY;

DROP INDEX `company_informant_FI_2` ON `company_informant`;

ALTER TABLE `company_informant` CHANGE `user_id` `informant_id` INTEGER NOT NULL;

ALTER TABLE `company_informant` ADD PRIMARY KEY (`company_id`,`informant_id`);

CREATE INDEX `company_informant_FI_2` ON `company_informant` (`informant_id`);

ALTER TABLE `company_owner` DROP PRIMARY KEY;

DROP INDEX `company_owner_FI_2` ON `company_owner`;

ALTER TABLE `company_owner` CHANGE `user_id` `owner_id` INTEGER NOT NULL;

ALTER TABLE `company_owner` ADD PRIMARY KEY (`company_id`,`owner_id`);

CREATE INDEX `company_owner_FI_2` ON `company_owner` (`owner_id`);

CREATE TABLE `contact`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `contact_FI_1` (`user_id`)
) ENGINE=MyISAM;

CREATE TABLE `informant`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `informant_FI_1` (`user_id`)
) ENGINE=MyISAM;

CREATE TABLE `owner`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `owner_FI_1` (`user_id`)
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

DROP TABLE IF EXISTS `contact`;

DROP TABLE IF EXISTS `informant`;

DROP TABLE IF EXISTS `owner`;

ALTER TABLE `company_contact` DROP PRIMARY KEY;

DROP INDEX `company_contact_FI_2` ON `company_contact`;

ALTER TABLE `company_contact` CHANGE `contact_id` `user_id` INTEGER NOT NULL;

ALTER TABLE `company_contact` ADD PRIMARY KEY (`company_id`,`user_id`);

CREATE INDEX `company_contact_FI_2` ON `company_contact` (`user_id`);

ALTER TABLE `company_informant` DROP PRIMARY KEY;

DROP INDEX `company_informant_FI_2` ON `company_informant`;

ALTER TABLE `company_informant` CHANGE `informant_id` `user_id` INTEGER NOT NULL;

ALTER TABLE `company_informant` ADD PRIMARY KEY (`company_id`,`user_id`);

CREATE INDEX `company_informant_FI_2` ON `company_informant` (`user_id`);

ALTER TABLE `company_owner` DROP PRIMARY KEY;

DROP INDEX `company_owner_FI_2` ON `company_owner`;

ALTER TABLE `company_owner` CHANGE `owner_id` `user_id` INTEGER NOT NULL;

ALTER TABLE `company_owner` ADD PRIMARY KEY (`company_id`,`user_id`);

CREATE INDEX `company_owner_FI_2` ON `company_owner` (`user_id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}