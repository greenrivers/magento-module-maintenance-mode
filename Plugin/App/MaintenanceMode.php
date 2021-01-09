<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_MaintenanceMode
 */

namespace Greenrivers\MaintenanceMode\Plugin\App;

use Greenrivers\MaintenanceMode\Helper\Config;
use Greenrivers\MaintenanceMode\Helper\Status;
use Magento\Framework\App\MaintenanceMode as Subject;

class MaintenanceMode
{
    /** @var Config */
    private $config;

    /** @var Status */
    private $status;

    /**
     * MaintenanceMode constructor.
     * @param Config $config
     * @param Status $status
     */
    public function __construct(Config $config, Status $status)
    {
        $this->config = $config;
        $this->status = $status;
    }

    /**
     * @param Subject $subject
     * @param bool $result
     * @return bool
     */
    public function afterIsOn(Subject $subject, bool $result): bool
    {
        if ($this->config->getEnabledConfig()) {
            $result = $this->status->updateStatus($result);
        }

        return $result;
    }
}
