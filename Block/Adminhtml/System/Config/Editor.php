<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Editor extends Field
{
    /** @var WysiwygConfig */
    private $wysiwygConfig;

    /**
     * Editor constructor.
     * @param Context $context
     * @param WysiwygConfig $wysiwygConfig
     * @param array $data
     */
    public function __construct(Context $context, WysiwygConfig $wysiwygConfig, array $data = [])
    {
        parent::__construct($context, $data);

        $this->wysiwygConfig = $wysiwygConfig;
    }

    /**
     * @inheritDoc
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $element->setWysiwyg(true);
        $element->setConfig($this->wysiwygConfig->getConfig($element));

        return parent::_getElementHtml($element);
    }
}
