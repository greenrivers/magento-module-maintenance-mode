<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\Observer;

use GreenRivers\MaintenanceMode\Helper\Config;
use Magento\Framework\App\MaintenanceMode;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SaveConfig implements ObserverInterface
{
    /** @var MaintenanceMode */
    private $maintenanceMode;

    /** @var Config */
    private $config;

    /**
     * SaveConfig constructor.
     * @param MaintenanceMode $maintenanceMode
     * @param Config $config
     */
    public function __construct(MaintenanceMode $maintenanceMode, Config $config)
    {
        $this->maintenanceMode = $maintenanceMode;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $changedPaths = (array)$observer->getEvent()->getChangedPaths();

        if ($changedPaths && $this->config->getEnabledConfig()) {
            $this->maintenanceMode->setAddresses($this->config->getWhitelistIpsConfig());
            $this->maintenanceMode->set($this->config->getFrontendConfig() || $this->config->getBackendConfig());
        }
    }
}
