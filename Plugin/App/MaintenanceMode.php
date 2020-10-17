<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\Plugin\App;

use GreenRivers\MaintenanceMode\Helper\Config;
use GreenRivers\MaintenanceMode\Helper\Status;
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
