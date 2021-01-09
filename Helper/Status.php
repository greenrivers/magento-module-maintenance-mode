<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_MaintenanceMode
 */

namespace Greenrivers\MaintenanceMode\Helper;

use Magento\Backend\Setup\ConfigOptionsList as BackendConfigOptionsList;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\UrlInterface;

class Status
{
    /** @var Config */
    private $config;

    /** @var DeploymentConfig */
    private $deploymentConfig;

    /** @var UrlInterface */
    private $urlBuilder;

    /**
     * Status constructor.
     * @param Config $config
     * @param DeploymentConfig $deploymentConfig
     * @param UrlInterface $urlBuilder
     */
    public function __construct(Config $config, DeploymentConfig $deploymentConfig, UrlInterface $urlBuilder)
    {
        $this->config = $config;
        $this->deploymentConfig = $deploymentConfig;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param bool $status
     * @return bool
     */
    public function updateStatus(bool $status): bool
    {
        $url = $this->urlBuilder->getCurrentUrl();
        $baseUrl = $this->urlBuilder->getBaseUrl();
        $adminUri = $this->deploymentConfig->get(BackendConfigOptionsList::CONFIG_PATH_BACKEND_FRONTNAME);

        return $status && (
                $this->enabledOnFrontend($url, $baseUrl, $adminUri) ||
                $this->enabledOnBackend($url, $baseUrl, $adminUri)
            );
    }

    /**
     * @param string $url
     * @param string $baseUrl
     * @param string $adminUri
     * @return bool
     */
    private function enabledOnFrontend(string $url, string $baseUrl, string $adminUri): bool
    {
        return $this->config->getFrontendConfig()
            && ($this->startsWith($url, $baseUrl) || $this->startsWith($url, $baseUrl . UrlInterface::URL_TYPE_STATIC))
            && !$this->startsWith($url, $baseUrl . $adminUri);
    }

    /**
     * @param string $url
     * @param string $baseUrl
     * @param string $adminUri
     * @return bool
     */
    private function enabledOnBackend(string $url, string $baseUrl, string $adminUri): bool
    {
        return $this->config->getBackendConfig()
            && ($this->startsWith($url, $baseUrl . $adminUri) || $this->startsWith($url, $baseUrl . UrlInterface::URL_TYPE_STATIC));
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
