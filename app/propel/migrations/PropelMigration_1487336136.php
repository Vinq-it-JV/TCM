<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1487336136.
 * Generated on 2017-02-17 13:55:36 by jeroenvisser
 */
class PropelMigration_1487336136
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

ALTER TABLE `user_address` CHANGE `user_id` `user_id` INTEGER NOT NULL;

ALTER TABLE `user_address` CHANGE `address_id` `address_id` INTEGER NOT NULL;

ALTER TABLE `user_address` ADD PRIMARY KEY (`id`,`user_id`,`address_id`);

ALTER TABLE `user_email` DROP PRIMARY KEY;

ALTER TABLE `user_email` CHANGE `user_id` `user_id` INTEGER NOT NULL;

ALTER TABLE `user_email` CHANGE `email_id` `email_id` INTEGER NOT NULL;

ALTER TABLE `user_email` ADD PRIMARY KEY (`id`,`user_id`,`email_id`);

ALTER TABLE `user_phone` DROP PRIMARY KEY;

ALTER TABLE `user_phone` CHANGE `user_id` `user_id` INTEGER NOT NULL;

ALTER TABLE `user_phone` CHANGE `phone_id` `phone_id` INTEGER NOT NULL;

ALTER TABLE `user_phone` ADD PRIMARY KEY (`id`,`user_id`,`phone_id`);

ALTER TABLE `user_role` DROP PRIMARY KEY;

ALTER TABLE `user_role` CHANGE `user_id` `user_id` INTEGER NOT NULL;

ALTER TABLE `user_role` CHANGE `role_id` `role_id` INTEGER NOT NULL;

ALTER TABLE `user_role` ADD PRIMARY KEY (`id`,`user_id`,`role_id`);

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

ALTER TABLE `user_address` CHANGE `user_id` `user_id` INTEGER;

ALTER TABLE `user_address` CHANGE `address_id` `address_id` INTEGER;

ALTER TABLE `user_address` ADD PRIMARY KEY (`id`);

ALTER TABLE `user_email` DROP PRIMARY KEY;

ALTER TABLE `user_email` CHANGE `user_id` `user_id` INTEGER;

ALTER TABLE `user_email` CHANGE `email_id` `email_id` INTEGER;

ALTER TABLE `user_email` ADD PRIMARY KEY (`id`);

ALTER TABLE `user_phone` DROP PRIMARY KEY;

ALTER TABLE `user_phone` CHANGE `user_id` `user_id` INTEGER;

ALTER TABLE `user_phone` CHANGE `phone_id` `phone_id` INTEGER;

ALTER TABLE `user_phone` ADD PRIMARY KEY (`id`);

ALTER TABLE `user_role` DROP PRIMARY KEY;

ALTER TABLE `user_role` CHANGE `user_id` `user_id` INTEGER;

ALTER TABLE `user_role` CHANGE `role_id` `role_id` INTEGER;

ALTER TABLE `user_role` ADD PRIMARY KEY (`id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}