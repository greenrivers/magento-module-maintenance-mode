<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_MaintenanceMode
 */

namespace Greenrivers\MaintenanceMode\Cron;

use Greenrivers\MaintenanceMode\Helper\Config;
use Magento\Framework\App\Cache\Manager;
use Magento\Framework\App\MaintenanceMode;

class Frontend
{
    /** @var Config */
    private $config;

    /** @var MaintenanceMode */
    private $maintenanceMode;

    /** @var Manager */
    private $cacheManager;

    /**
     * Frontend constructor.
     * @param Config $config
     * @param MaintenanceMode $maintenanceMode
     * @param Manager $cacheManager
     */
    public function __construct(Config $config, MaintenanceMode $maintenanceMode, Manager $cacheManager)
    {
        $this->config = $config;
        $this->maintenanceMode = $maintenanceMode;
        $this->cacheManager = $cacheManager;
    }

    public function enable(): void
    {
        if ($this->config->getEnabledConfig() && $this->config->getCronFrontendEnabledConfig()) {
            $this->config->setValueConfig(Config::XML_FRONTEND_CONFIG_PATH, true);

            $this->maintenanceMode->setAddresses($this->config->getCronWhitelistIpsConfig());
            $this->maintenanceMode->set(true);

            $this->cacheManager->flush($this->cacheManager->getAvailableTypes());
        }
    }

    public function disable(): void
    {
        if ($this->config->getEnabledConfig() && $this->config->getCronFrontendEnabledConfig()) {
            $this->config->setValueConfig(Config::XML_FRONTEND_CONFIG_PATH, false);

            if (!$this->config->getBackendConfig()) {
                $this->maintenanceMode->set(false);
            }

            $this->cacheManager->flush($this->cacheManager->getAvailableTypes());
        }
    }
}
