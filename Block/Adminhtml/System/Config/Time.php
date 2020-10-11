<?php

namespace GreenRivers\MaintenanceMode\Block\Adminhtml\System\Config;

use GreenRivers\MaintenanceMode\Helper\Config;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use GreenRivers\MaintenanceMode\Utils\Form\Element\Time as TimeElement;

class Time extends Field
{
    const MAINTENANCE_MODE_CRON_FRONTEND_TIME_START = 'maintenance-mode-cron-frontend-time-start';
    const MAINTENANCE_MODE_CRON_FRONTEND_TIME_END = 'maintenance-mode-cron-frontend-time-end';

    const MAINTENANCE_MODE_CRON_BACKEND_TIME_START = 'maintenance-mode-cron-backend-time-start';
    const MAINTENANCE_MODE_CRON_BACKEND_TIME_END = 'maintenance-mode-cron-backend-time-end';

    /** @var string */
    protected $_template = 'GreenRivers_MaintenanceMode::system/config/time.phtml';

    /** @var Config */
    private $config;

    /** @var TimeElement */
    private $timeElement;

    /** @var AbstractElement */
    private $element;

    /**
     * Time constructor.
     * @param Context $context
     * @param Config $config
     * @param TimeElement $timeElement
     * @param array $data
     */
    public function __construct(Context $context, Config $config, TimeElement $timeElement, array $data = [])
    {
        parent::__construct($context, $data);

        $this->config = $config;
        $this->timeElement = $timeElement;
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
            case self::MAINTENANCE_MODE_CRON_FRONTEND_TIME_START:
                $component['value'] = $this->config->getCronFrontendTimeStartConfig();
                break;
            case self::MAINTENANCE_MODE_CRON_FRONTEND_TIME_END:
                $component['value'] = $this->config->getCronFrontendTimeEndConfig();
                break;
            case self::MAINTENANCE_MODE_CRON_BACKEND_TIME_START:
                $component['value'] = $this->config->getCronBackendTimeStartConfig();
                break;
            case self::MAINTENANCE_MODE_CRON_BACKEND_TIME_END:
                $component['value'] = $this->config->getCronBackendTimeEndConfig();
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
     * @return string
     */
    public function getTimeElementHtml(): string
    {
        return $this->timeElement->getElementHtml();
    }

    /**
     * @return string
     */
    public function getTimeElementName(): string
    {
        return $this->timeElement->getName();
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