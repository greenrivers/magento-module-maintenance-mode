<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\Cron;

use GreenRivers\MaintenanceMode\Helper\Config;
use Magento\Framework\App\Cache\Manager;
use Magento\Framework\App\MaintenanceMode;

class Backend
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
        if ($this->config->getEnabledConfig() && $this->config->getCronBackendEnabledConfig()) {
            $this->config->setValueConfig(Config::XML_BACKEND_CONFIG_PATH, true);

            $this->maintenanceMode->setAddresses($this->config->getCronWhitelistIpsConfig());
            $this->maintenanceMode->set(true);

            $this->cacheManager->flush($this->cacheManager->getAvailableTypes());
        }
    }

    public function disable(): void
    {
        if ($this->config->getEnabledConfig() && $this->config->getCronBackendEnabledConfig()) {
            $this->config->setValueConfig(Config::XML_BACKEND_CONFIG_PATH, false);

            if (!$this->config->getFrontendConfig()) {
                $this->maintenanceMode->set(false);
            }

            $this->cacheManager->flush($this->cacheManager->getAvailableTypes());
        }
    }
}