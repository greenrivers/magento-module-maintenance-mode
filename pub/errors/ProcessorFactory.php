<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_MaintenanceMode
 */

namespace Greenrivers\MaintenanceMode\pub\errors;

use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Response\Http;

class ProcessorFactory
{
    /**
     * @return Processor
     */
    public function createProcessor(): Processor
    {
        $objectManagerFactory = Bootstrap::createObjectManagerFactory(BP, $_SERVER);
        $objectManager = $objectManagerFactory->create($_SERVER);
        $response = $objectManager->create(Http::class);
        return new Processor($response);
    }
}
