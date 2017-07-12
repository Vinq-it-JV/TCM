<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1499866195.
 * Generated on 2017-07-12 15:29:55 by jeroenvisser
 */
class PropelMigration_1499866195
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

DROP INDEX `store_maintenance_log_FI_1` ON `store_maintenance_log`;

DROP INDEX `store_maintenance_log_FI_2` ON `store_maintenance_log`;

ALTER TABLE `store_maintenance_log`
    ADD `type` INTEGER AFTER `id`;

CREATE INDEX `store_maintenance_log_FI_1` ON `store_maintenance_log` (`type`);

CREATE INDEX `store_maintenance_log_FI_2` ON `store_maintenance_log` (`maintenance_store`);

CREATE INDEX `store_maintenance_log_FI_3` ON `store_maintenance_log` (`maintenance_by`);

CREATE TABLE `maintenance_type`
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

DROP TABLE IF EXISTS `maintenance_type`;

DROP INDEX `store_maintenance_log_FI_3` ON `store_maintenance_log`;

DROP INDEX `store_maintenance_log_FI_1` ON `store_maintenance_log`;

DROP INDEX `store_maintenance_log_FI_2` ON `store_maintenance_log`;

ALTER TABLE `store_maintenance_log` DROP `type`;

CREATE INDEX `store_maintenance_log_FI_1` ON `store_maintenance_log` (`maintenance_store`);

CREATE INDEX `store_maintenance_log_FI_2` ON `store_maintenance_log` (`maintenance_by`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}