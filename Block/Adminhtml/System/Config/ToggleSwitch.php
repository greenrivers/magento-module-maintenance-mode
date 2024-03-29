<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_MaintenanceMode
 */

namespace Greenrivers\MaintenanceMode\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Greenrivers\MaintenanceMode\Helper\Config;

class ToggleSwitch extends Field
{
    const MAINTENANCE_MODE_GENERAL_ENABLED = 'maintenance-mode-general-enabled';

    const MAINTENANCE_MODE_SETTINGS_FRONTEND = 'maintenance-mode-settings-frontend';
    const MAINTENANCE_MODE_SETTINGS_BACKEND = 'maintenance-mode-settings-backend';

    const MAINTENANCE_MODE_CUSTOM_PAGE_ENABLED = 'maintenance-mode-custom-page-enabled';

    const MAINTENANCE_MODE_CRON_FRONTEND_ENABLED = 'maintenance-mode-cron-frontend-enabled';
    const MAINTENANCE_MODE_CRON_BACKEND_ENABLED = 'maintenance-mode-cron-backend-enabled';

    /** @var string */
    protected $_template = 'Greenrivers_MaintenanceMode::system/config/toggle_switch.phtml';

    /** @var Config */
    private $config;

    /** @var AbstractElement */
    private $element;

    /**
     * ToggleSwitch constructor.
     * @param Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(Context $context, Config $config, array $data = [])
    {
        parent::__construct($context, $data);

        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getComponent(): array
    {
        $element = $this->getElement();
        $id = str_replace('_', '-', $element->getHtmlId());
        $name = $element->getName();
        $component = ['id' => $id, 'name' => $name];

        switch ($id) {
            case self::MAINTENANCE_MODE_GENERAL_ENABLED:
                $component['value'] = $this->config->getEnabledConfig();
                break;
            case self::MAINTENANCE_MODE_SETTINGS_FRONTEND:
                $component['value'] = $this->config->getFrontendConfig();
                break;
            case self::MAINTENANCE_MODE_SETTINGS_BACKEND:
                $component['value'] = $this->config->getBackendConfig();
                break;
            case self::MAINTENANCE_MODE_CUSTOM_PAGE_ENABLED:
                $component['value'] = $this->config->getCustomPageEnabledConfig();
                break;
            case self::MAINTENANCE_MODE_CRON_FRONTEND_ENABLED:
                $component['value'] = $this->config->getCronFrontendEnabledConfig();
                break;
            case self::MAINTENANCE_MODE_CRON_BACKEND_ENABLED:
                $component['value'] = $this->config->getCronBackendEnabledConfig();
                break;
        }

        return $component;
    }

    /**
     * @inheritDoc
     */
    public function render(AbstractElement $element): string
    {
        $this->setElement($element);
        $html = "<td class='label'>" . $element->getLabel() . '</td><td>' . $this->toHtml() . '</td><td></td>';
        return $this->_decorateRowHtml($element, $html);
    }

    /**
     * @return AbstractElement
     */
    public function getElement(): AbstractElement
    {
        return $this->element;
    }

    /**
     * @param AbstractElement $element
     */
    public function setElement(AbstractElement $element): void
    {
        $this->element = $element;
    }
}
