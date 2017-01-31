<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1484646088.
 * Generated on 2017-01-17 10:41:28 by jeroenvisser
 */
class PropelMigration_1484646088
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

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255),
    `firstname` VARCHAR(255),
    `lastname` VARCHAR(255),
    `email` VARCHAR(255),
    `password` VARCHAR(255),
    `secret` VARCHAR(255),
    `logins` INTEGER DEFAULT 3,
    `language` VARCHAR(5) DEFAULT \'en\',
    `is_enabled` TINYINT(1) DEFAULT 1,
    `is_external` TINYINT(1) DEFAULT 0,
    `is_locked` TINYINT(1) DEFAULT 0,
    `is_expired` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE `role`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `description` VARCHAR(255),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE `user_role`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    `role_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `user_role_FI_1` (`user_id`),
    INDEX `user_role_FI_2` (`role_id`)
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

DROP TABLE IF EXISTS `user`;

DROP TABLE IF EXISTS `role`;

DROP TABLE IF EXISTS `user_role`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}