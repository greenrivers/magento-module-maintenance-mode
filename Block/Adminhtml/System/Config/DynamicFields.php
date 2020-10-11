<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class DynamicFields extends AbstractFieldArray
{
    /**
     * @inheritDoc
     */
    protected function _prepareToRender()
    {
        $this->addColumn('ip', ['label' => __('IP'), 'class' => 'required-entry']);
        $this->_addAfter = false;
    }
}
