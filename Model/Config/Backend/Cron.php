<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_MaintenanceMode
 */

namespace Greenrivers\MaintenanceMode\Model\Config\Backend;

use Exception;
use Magento\Cron\Model\Config\Source\Frequency;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\App\Config\ValueFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class Cron extends Value
{
    const ENABLED_CONFIG_PATH = 'groups/general/fields/enabled/value';

    /** @var ValueFactory */
    private $configValueFactory;

    /** @var string */
    private $runModelPath = '';

    /**
     * Frontend constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param ValueFactory $configValueFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param string $runModelPath
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        ValueFactory $configValueFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        string $runModelPath = '',
        array $data = []
    ) {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);

        $this->configValueFactory = $configValueFactory;
        $this->runModelPath = $runModelPath;
    }

    /**
     * @param array $time
     * @param string $frequency
     * @param string $cronStringPath
     * @param string $cronModelPath
     */
    protected function saveConfig(array $time, string $frequency, string $cronStringPath, string $cronModelPath)
    {
        try {
            $this->configValueFactory->create()->load(
                $cronStringPath,
                'path'
            )->setValue(
                $this->getCronExprString($time, $frequency)
            )->setPath(
                $cronStringPath
            )->save();
            $this->configValueFactory->create()->load(
                $cronModelPath,
                'path'
            )->setValue(
                $this->runModelPath
            )->setPath(
                $cronModelPath
            )->save();
        } catch (Exception $e) {
            $this->_logger->error($e->getMessage());
        }
    }

    /**
     * @param array $time
     * @param string $frequency
     * @return string
     */
    private function getCronExprString(array $time, string $frequency): string
    {
        $cronExprArray = [
            intval($time[1]), //Minute
            intval($time[0]), //Hour
            $frequency == Frequency::CRON_MONTHLY ? '1' : '*', //Day of the Month
            '*', //Month of the Year
            $frequency == Frequency::CRON_WEEKLY ? '1' : '*', //Day of the Week
        ];

        return join(' ', $cronExprArray);
    }
}
