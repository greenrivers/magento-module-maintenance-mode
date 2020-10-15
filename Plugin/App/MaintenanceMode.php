<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\Plugin\App;

use GreenRivers\MaintenanceMode\Helper\Config;
use Magento\Backend\Setup\ConfigOptionsList as BackendConfigOptionsList;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\MaintenanceMode as Subject;
use Magento\Framework\UrlInterface;

class MaintenanceMode
{
    /** @var DeploymentConfig */
    private $deploymentConfig;

    /** @var Config */
    private $config;

    /** @var UrlInterface */
    private $urlBuilder;

    /**
     * MaintenanceMode constructor.
     * @param DeploymentConfig $deploymentConfig
     * @param Config $config
     * @param UrlInterface $urlBuilder
     */
    public function __construct(DeploymentConfig $deploymentConfig, Config $config, UrlInterface $urlBuilder)
    {
        $this->deploymentConfig = $deploymentConfig;
        $this->config = $config;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param Subject $subject
     * @param bool $result
     * @return bool
     */
    public function afterIsOn(Subject $subject, bool $result): bool
    {
        $url = $this->urlBuilder->getCurrentUrl();
        $baseUrl = $this->urlBuilder->getBaseUrl();
        $adminUri = $this->deploymentConfig->get(BackendConfigOptionsList::CONFIG_PATH_BACKEND_FRONTNAME);
        if ($this->config->getEnabledConfig()) {
            $result = $result && (($this->startsWith($url, $baseUrl)
                    && !$this->startsWith($url, $baseUrl . $adminUri)
                    && $this->config->getFrontendConfig())
                || ($this->startsWith($url, $baseUrl . $adminUri) && $this->config->getBackendConfig()));
        }

        return $result;
    }

    /**
     * @param string $text
     * @param string $search
     * @return bool
     */
    private function startsWith(string $text, string $search): bool
    {
        return strpos($text, $search) === 0;
    }
}
