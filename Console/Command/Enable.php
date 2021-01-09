<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_MaintenanceMode
 */

namespace Greenrivers\MaintenanceMode\Console\Command;

use Greenrivers\MaintenanceMode\Helper\Config;
use Magento\Framework\App\MaintenanceMode;
use Magento\Framework\Serialize\Serializer\Json as Serializer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Enable extends Command
{
    const WHITELIST_IPS_OPTION = 'whitelist-ips';
    const FRONTEND_FLAG_OPTION = 'frontend';
    const BACKEND_FLAG_OPTION = 'backend';

    /** @var Config */
    private $config;

    /** @var MaintenanceMode */
    private $maintenanceMode;

    /** @var Serializer */
    private $serializer;

    /**
     * Enable constructor.
     * @param Config $config
     * @param MaintenanceMode $maintenanceMode
     * @param Serializer $serializer
     * @param string|null $name
     */
    public function __construct(
        Config $config,
        MaintenanceMode $maintenanceMode,
        Serializer $serializer,
        string $name = null
    ) {
        parent::__construct($name);

        $this->config = $config;
        $this->maintenanceMode = $maintenanceMode;
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('greenrivers:maintenance:enable');
        $this->setDescription('Enable maintenance mode in frontend/backend');
        $this->addOption(
            self::WHITELIST_IPS_OPTION,
            null,
            InputOption::VALUE_OPTIONAL,
            'Whitelist ips: --whitelist-ips=127.0.0.1,127.0.0.2'
        );
        $this->addOption(
            self::FRONTEND_FLAG_OPTION,
            null,
            InputOption::VALUE_NONE,
            'Enable in frontend only: --frontend'
        );
        $this->addOption(
            self::BACKEND_FLAG_OPTION,
            null,
            InputOption::VALUE_NONE,
            'Enable in backend only: --backend'
        );

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->config->getEnabledConfig()) {
            $whitelistIps = $input->getOption(self::WHITELIST_IPS_OPTION);
            $frontend = $input->getOption(self::FRONTEND_FLAG_OPTION);
            $backend = $input->getOption(self::BACKEND_FLAG_OPTION);

            if ($frontend) {
                $this->config->setValueConfig(Config::XML_FRONTEND_CONFIG_PATH, true);
            }

            if ($backend) {
                $this->config->setValueConfig(Config::XML_BACKEND_CONFIG_PATH, true);
            }

            if ($frontend === $backend) {
                $this->config->setValueConfig(Config::XML_FRONTEND_CONFIG_PATH, true);
                $this->config->setValueConfig(Config::XML_BACKEND_CONFIG_PATH, true);
            }

            if ($whitelistIps) {
                $whitelistIpsArray = explode(',', $whitelistIps);
                $whitelistIpsArray = array_map(function ($ip) {
                    return ['ip' => $ip];
                }, $whitelistIpsArray);

                $this->config->setValueConfig(
                    Config::XML_WHITELIST_IPS_CONFIG_PATH,
                    $this->serializer->serialize($whitelistIpsArray)
                );
                $this->maintenanceMode->setAddresses($whitelistIps);
            }

            $this->maintenanceMode->set(true);
        }
    }
}
