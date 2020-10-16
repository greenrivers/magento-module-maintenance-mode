<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\Cron;

use GreenRivers\MaintenanceMode\Helper\Config;
use Magento\Framework\App\MaintenanceMode;

class Frontend
{
    /** @var Config */
    private $config;

    /** @var MaintenanceMode */
    private $maintenanceMode;

    /**
     * Frontend constructor.
     * @param Config $config
     * @param MaintenanceMode $maintenanceMode
     */
    public function __construct(Config $config, MaintenanceMode $maintenanceMode)
    {
        $this->config = $config;
        $this->maintenanceMode = $maintenanceMode;
    }

    public function enable(): void
    {
        if ($this->config->getEnabledConfig() && $this->config->getCronFrontendEnabledConfig()) {
            $this->maintenanceMode->setAddresses($this->config->getCronWhitelistIpsConfig());
            $this->maintenanceMode->set(true);
        }
    }

    public function disable(): void
    {
        if ($this->config->getEnabledConfig() && $this->config->getCronFrontendEnabledConfig()) {
            $this->maintenanceMode->set(false);
        }
    }
}