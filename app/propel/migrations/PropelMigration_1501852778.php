<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1501852778.
 * Generated on 2017-08-04 15:19:38 by jeroenvisser
 */
class PropelMigration_1501852778
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

CREATE TABLE `device_copy`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `copy_of_input` INTEGER,
    `copy_of_sensor` INTEGER,
    `group` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `device_copy_FI_1` (`copy_of_input`),
    INDEX `device_copy_FI_2` (`copy_of_sensor`),
    INDEX `device_copy_FI_3` (`group`)
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

DROP TABLE IF EXISTS `device_copy`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}