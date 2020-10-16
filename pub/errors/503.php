<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

use GreenRivers\MaintenanceMode\Preference\App\ExceptionHandler;

/** @var ExceptionHandler $this */
require $this->getProcessorFactory();

$processorFactory = new \GreenRivers\MaintenanceMode\pub\errors\ProcessorFactory();
$processor = $processorFactory->createProcessor();
$response = $processor->process503();
$response->sendResponse();
