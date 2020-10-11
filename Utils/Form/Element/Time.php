<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\Utils\Form\Element;

class Time extends \Magento\Framework\Data\Form\Element\Time
{
    /**
     * @inheritDoc
     */
    public function getHtmlId()
    {
        return 'time';
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'time';
    }
}
