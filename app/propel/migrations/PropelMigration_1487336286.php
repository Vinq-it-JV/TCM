<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1487336286.
 * Generated on 2017-02-17 13:58:06 by jeroenvisser
 */
class PropelMigration_1487336286
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

ALTER TABLE `user_address` DROP PRIMARY KEY;

DROP INDEX `user_address_FI_1` ON `user_address`;

ALTER TABLE `user_address` DROP `id`;

ALTER TABLE `user_address` ADD PRIMARY KEY (`user_id`,`address_id`);

ALTER TABLE `user_email` DROP PRIMARY KEY;

DROP INDEX `user_email_FI_1` ON `user_email`;

ALTER TABLE `user_email` DROP `id`;

ALTER TABLE `user_email` ADD PRIMARY KEY (`user_id`,`email_id`);

ALTER TABLE `user_phone` DROP PRIMARY KEY;

DROP INDEX `user_phone_FI_1` ON `user_phone`;

ALTER TABLE `user_phone` DROP `id`;

ALTER TABLE `user_phone` ADD PRIMARY KEY (`user_id`,`phone_id`);

ALTER TABLE `user_role` DROP PRIMARY KEY;

DROP INDEX `user_role_FI_1` ON `user_role`;

ALTER TABLE `user_role` DROP `id`;

ALTER TABLE `user_role` ADD PRIMARY KEY (`user_id`,`role_id`);

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

ALTER TABLE `user_address` DROP PRIMARY KEY;

ALTER TABLE `user_address`
    ADD `id` INTEGER NOT NULL AUTO_INCREMENT FIRST;

ALTER TABLE `user_address` ADD PRIMARY KEY (`id`);

CREATE INDEX `user_address_FI_1` ON `user_address` (`user_id`);

ALTER TABLE `user_email` DROP PRIMARY KEY;

ALTER TABLE `user_email`
    ADD `id` INTEGER NOT NULL AUTO_INCREMENT FIRST;

ALTER TABLE `user_email` ADD PRIMARY KEY (`id`);

CREATE INDEX `user_email_FI_1` ON `user_email` (`user_id`);

ALTER TABLE `user_phone` DROP PRIMARY KEY;

ALTER TABLE `user_phone`
    ADD `id` INTEGER NOT NULL AUTO_INCREMENT FIRST;

ALTER TABLE `user_phone` ADD PRIMARY KEY (`id`);

CREATE INDEX `user_phone_FI_1` ON `user_phone` (`user_id`);

ALTER TABLE `user_role` DROP PRIMARY KEY;

ALTER TABLE `user_role`
    ADD `id` INTEGER NOT NULL AUTO_INCREMENT FIRST;

ALTER TABLE `user_role` ADD PRIMARY KEY (`id`);

CREATE INDEX `user_role_FI_1` ON `user_role` (`user_id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}