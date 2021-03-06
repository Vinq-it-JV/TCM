<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1493911845.
 * Generated on 2017-05-04 17:30:45 by jeroenvisser
 */
class PropelMigration_1493911845
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

CREATE INDEX `ds_temperature_notification_FI_2` ON `ds_temperature_notification` (`sensor`);

DROP INDEX `ds_temperature_sensor_FI_4` ON `ds_temperature_sensor`;

ALTER TABLE `ds_temperature_sensor` DROP `notification`;

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

DROP INDEX `ds_temperature_notification_FI_2` ON `ds_temperature_notification`;

ALTER TABLE `ds_temperature_sensor`
    ADD `notification` INTEGER AFTER `notify_after`;

CREATE INDEX `ds_temperature_sensor_FI_4` ON `ds_temperature_sensor` (`notification`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}