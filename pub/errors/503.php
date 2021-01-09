<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_MaintenanceMode
 */

use Greenrivers\MaintenanceMode\Preference\App\ExceptionHandler;

/** @var ExceptionHandler $this */
require $this->getProcessorFactory();

$processorFactory = new \Greenrivers\MaintenanceMode\pub\errors\ProcessorFactory();
$processor = $processorFactory->createProcessor();
$response = $processor->process503();
$response->sendResponse();
