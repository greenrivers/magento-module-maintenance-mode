<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_MaintenanceMode
 */

namespace Greenrivers\MaintenanceMode\Model\Config\Backend\Cron;

use Greenrivers\MaintenanceMode\Model\Config\Backend\Cron;

class Backend extends Cron
{
    const CRON_TIME_START_STRING_PATH = 'crontab/greenrivers/jobs/greenrivers_maintenance_mode_backend_enable/schedule/cron_expr';
    const CRON_TIME_START_MODEL_PATH = 'crontab/greenrivers/jobs/greenrivers_maintenance_mode_backend_enable/run/model';

    const CRON_TIME_END_STRING_PATH = 'crontab/greenrivers/jobs/greenrivers_maintenance_mode_backend_disable/schedule/cron_expr';
    const CRON_TIME_END_MODEL_PATH = 'crontab/greenrivers/jobs/greenrivers_maintenance_mode_backend_disable/run/model';

    const CRON_ENABLED_CONFIG_PATH = 'groups/cron/groups/backend/fields/enabled/value';
    const TIME_START_CONFIG_PATH = 'groups/cron/groups/backend/fields/time_start/value';
    const TIME_END_CONFIG_PATH = 'groups/cron/groups/backend/fields/time_end/value';
    const FREQUENCY_CONFIG_PATH = 'groups/cron/groups/backend/fields/frequency/value';

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
