<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1485962935.
 * Generated on 2017-02-01 16:28:55 by jeroenvisser
 */
class PropelMigration_1485962935
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

CREATE TABLE `user_email`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    `email_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `user_email_FI_1` (`user_id`),
    INDEX `user_email_FI_2` (`email_id`)
) ENGINE=MyISAM;

CREATE TABLE `email`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255),
    `description` VARCHAR(255),
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

DROP TABLE IF EXISTS `user_email`;

DROP TABLE IF EXISTS `email`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}