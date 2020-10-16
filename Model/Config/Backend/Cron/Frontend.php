<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\Model\Config\Backend\Cron;

use GreenRivers\MaintenanceMode\Model\Config\Backend\Cron;

class Frontend extends Cron
{
    const CRON_TIME_START_STRING_PATH = 'crontab/greenrivers/jobs/greenrivers_maintenance_mode_frontend_enable/schedule/cron_expr';
    const CRON_TIME_START_MODEL_PATH = 'crontab/greenrivers/jobs/greenrivers_maintenance_mode_frontend_enable/run/model';

    const CRON_TIME_END_STRING_PATH = 'crontab/greenrivers/jobs/greenrivers_maintenance_mode_frontend_disable/schedule/cron_expr';
    const CRON_TIME_END_MODEL_PATH = 'crontab/greenrivers/jobs/greenrivers_maintenance_mode_frontend_disable/run/model';

    const CRON_ENABLED_CONFIG_PATH = 'groups/cron/groups/frontend/fields/enabled/value';
    const TIME_START_CONFIG_PATH = 'groups/cron/groups/frontend/fields/time_start/value';
    const TIME_END_CONFIG_PATH = 'groups/cron/groups/frontend/fields/time_end/value';
    const FREQUENCY_CONFIG_PATH = 'groups/cron/groups/frontend/fields/frequency/value';

    /**
     * @inheritDoc
     */
    public function afterSave()
    {
        if ($this->getData(self::ENABLED_CONFIG_PATH) && $this->getData(self::CRON_ENABLED_CONFIG_PATH)) {
            $timeStart = $this->getData(self::TIME_START_CONFIG_PATH);
            $timeEnd = $this->getData(self::TIME_END_CONFIG_PATH);
            $frequency = $this->getData(self::FREQUENCY_CONFIG_PATH);

            $this->saveConfig(
                $timeStart,
                $frequency,
                self::CRON_TIME_START_STRING_PATH,
                self::CRON_TIME_START_MODEL_PATH
            );
            $this->saveConfig(
                $timeEnd,
                $frequency,
                self::CRON_TIME_END_STRING_PATH,
                self::CRON_TIME_END_MODEL_PATH
            );
        }

        return parent::afterSave();
    }
}
