<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\pub\errors;

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
