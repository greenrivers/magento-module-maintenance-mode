<?php

/** @var ExceptionHandler $this */

use GreenRivers\MaintenanceMode\Preference\App\ExceptionHandler;

require $this->getProcessorFactory();

$processorFactory = new \GreenRivers\MaintenanceMode\pub\errors\ProcessorFactory();
$processor = $processorFactory->createProcessor();
$response = $processor->process503();
$response->sendResponse();
