<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\Helper;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Json as Serializer;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const XML_ENABLED_CONFIG_PATH = 'maintenance_mode/general/enabled';

    const XML_FRONTEND_CONFIG_PATH = 'maintenance_mode/settings/frontend';
    const XML_BACKEND_CONFIG_PATH = 'maintenance_mode/settings/backend';
    const XML_WHITELIST_IPS_CONFIG_PATH = 'maintenance_mode/settings/whitelist_ips';

    const XML_CUSTOM_PAGE_ENABLED_CONFIG_PATH = 'maintenance_mode/custom_page/enabled';
    const XML_CUSTOM_PAGE_STYLES_CONFIG_PATH = 'maintenance_mode/custom_page/styles';
    const XML_CUSTOM_PAGE_CONTENT_CONFIG_PATH = 'maintenance_mode/custom_page/content';

    const XML_CRON_FRONTEND_ENABLED_CONFIG_PATH = 'maintenance_mode/cron/frontend/enabled';
    const XML_CRON_FRONTEND_FREQUENCY_CONFIG_PATH = 'maintenance_mode/cron/frontend/frequency';
    const XML_CRON_FRONTEND_TIME_START_CONFIG_PATH = 'maintenance_mode/cron/frontend/time_start';
    const XML_CRON_FRONTEND_TIME_END_CONFIG_PATH = 'maintenance_mode/cron/frontend/time_end';

    const XML_CRON_BACKEND_ENABLED_CONFIG_PATH = 'maintenance_mode/cron/backend/enabled';
    const XML_CRON_BACKEND_FREQUENCY_CONFIG_PATH = 'maintenance_mode/cron/backend/frequency';
    const XML_CRON_BACKEND_TIME_START_CONFIG_PATH = 'maintenance_mode/cron/backend/time_start';
    const XML_CRON_BACKEND_TIME_END_CONFIG_PATH = 'maintenance_mode/cron/backend/time_end';

    const XML_CRON_WHITELIST_IPS_CONFIG_PATH = 'maintenance_mode/cron/whitelist_ips';

    /** @var WriterInterface */
    private $configWriter;

    /** @var Serializer */
    private $serializer;

    /**
     * Config constructor.
     * @param Context $context
     * @param WriterInterface $configWriter
     * @param Serializer $serializer
     */
    public function __construct(Context $context, WriterInterface $configWriter, Serializer $serializer)
    {
        parent::__construct($context);

        $this->configWriter = $configWriter;
        $this->serializer = $serializer;
    }

    /**
     * @return bool
     */
    public function getEnabledConfig(): bool
    {
        return $this->scopeConfig->getValue(self::XML_ENABLED_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function getFrontendConfig(): bool
    {
        return $this->scopeConfig->getValue(self::XML_FRONTEND_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function getBackendConfig(): bool
    {
        return $this->scopeConfig->getValue(self::XML_BACKEND_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getWhitelistIpsConfig(): string
    {
        $whitelistIps = $this->serializer->unserialize(
            $this->scopeConfig->getValue(self::XML_WHITELIST_IPS_CONFIG_PATH, ScopeInterface::SCOPE_STORE)
        );

        return implode(',', array_column($whitelistIps, 'ip'));
    }

    /**
     * @return bool
     */
    public function getCustomPageEnabledConfig(): bool
    {
        return $this->scopeConfig->getValue(
            self::XML_CUSTOM_PAGE_ENABLED_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCustomPageStylesConfig(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_CUSTOM_PAGE_STYLES_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCustomPageContentConfig(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_CUSTOM_PAGE_CONTENT_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function getCronFrontendEnabledConfig(): bool
    {
        return $this->scopeConfig->getValue(
            self::XML_CRON_FRONTEND_ENABLED_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCronFrontendFrequencyConfig(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_CRON_FRONTEND_FREQUENCY_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCronFrontendTimeStartConfig(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_CRON_FRONTEND_TIME_START_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCronFrontendTimeEndConfig(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_CRON_FRONTEND_TIME_END_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function getCronBackendEnabledConfig(): bool
    {
        return $this->scopeConfig->getValue(
            self::XML_CRON_BACKEND_ENABLED_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCronBackendFrequencyConfig(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_CRON_BACKEND_FREQUENCY_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCronBackendTimeStartConfig(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_CRON_BACKEND_TIME_START_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCronBackendTimeEndConfig(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_CRON_BACKEND_TIME_END_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCronWhitelistIpsConfig(): string
    {
        $whitelistIps = $this->serializer->unserialize(
            $this->scopeConfig->getValue(
                self::XML_CRON_WHITELIST_IPS_CONFIG_PATH,
                ScopeInterface::SCOPE_STORE
            )
        );

        return implode(',', array_column($whitelistIps, 'ip'));
    }

    /**
     * @param string $path
     * @param $value
     */
    public function setValueConfig(string $path, $value): void
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }
        $this->configWriter->save($path, $value);
    }
}
