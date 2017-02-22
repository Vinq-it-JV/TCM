<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1485958714.
 * Generated on 2017-02-01 15:18:34 by jeroenvisser
 */
class PropelMigration_1485958714
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

ALTER TABLE `user`
    ADD `middlename` VARCHAR(255) AFTER `firstname`,
    ADD `gender` INTEGER AFTER `lastname`,
    ADD `title` INTEGER AFTER `gender`,
    ADD `birth_date` DATETIME AFTER `title`;

CREATE INDEX `user_FI_1` ON `user` (`gender`);

CREATE INDEX `user_FI_2` ON `user` (`title`);

ALTER TABLE `user_title`
    ADD `name_short` VARCHAR(25) AFTER `id`;

ALTER TABLE `user_title` DROP `description`;

CREATE TABLE `user_gender`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name_short` VARCHAR(25),
    `name` VARCHAR(255),
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

DROP TABLE IF EXISTS `user_gender`;

DROP INDEX `user_FI_1` ON `user`;

DROP INDEX `user_FI_2` ON `user`;

ALTER TABLE `user` DROP `middlename`;

ALTER TABLE `user` DROP `gender`;

ALTER TABLE `user` DROP `title`;

ALTER TABLE `user` DROP `birth_date`;

ALTER TABLE `user_title`
    ADD `description` VARCHAR(255) AFTER `name`;

ALTER TABLE `user_title` DROP `name_short`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}