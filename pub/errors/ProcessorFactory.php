<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace GreenRivers\MaintenanceMode\pub\errors;

use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Response\Http;

/**
 * Error processor factory
 */
class ProcessorFactory
{
    /**
     * Create Processor
     *
     * @return Processor
     */
    public function createProcessor()
    {
        $objectManagerFactory = Bootstrap::createObjectManagerFactory(BP, $_SERVER);
        $objectManager = $objectManagerFactory->create($_SERVER);
        $response = $objectManager->create(Http::class);
        return new Processor($response);
    }
}
