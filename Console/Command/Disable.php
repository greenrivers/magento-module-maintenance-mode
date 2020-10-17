<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\Console\Command;

use GreenRivers\MaintenanceMode\Helper\Config;
use Magento\Framework\App\MaintenanceMode;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Disable extends Command
{
    const FRONTEND_FLAG_OPTION = 'frontend';
    const BACKEND_FLAG_OPTION = 'backend';

    /** @var Config */
    private $config;

    /** @var MaintenanceMode */
    private $maintenanceMode;

    /**
     * Disable constructor.
     * @param Config $config
     * @param MaintenanceMode $maintenanceMode
     * @param string|null $name
     */
    public function __construct(Config $config, MaintenanceMode $maintenanceMode, string $name = null)
    {
        parent::__construct($name);

        $this->config = $config;
        $this->maintenanceMode = $maintenanceMode;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('greenrivers:maintenance:disable');
        $this->setDescription('Disable maintenance mode in frontend/backend');
        $this->addOption(
            self::FRONTEND_FLAG_OPTION,
            null,
            InputOption::VALUE_NONE,
            'Disable in frontend only: --frontend'
        );
        $this->addOption(
            self::BACKEND_FLAG_OPTION,
            null,
            InputOption::VALUE_NONE,
            'Disable in backend only: --backend'
        );

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->config->getEnabledConfig()) {
            $frontend = $input->getOption(self::FRONTEND_FLAG_OPTION);
            $backend = $input->getOption(self::BACKEND_FLAG_OPTION);

            if ($frontend) {
                $this->config->setValueConfig(Config::XML_FRONTEND_CONFIG_PATH, false);
            }

            if ($backend) {
                $this->config->setValueConfig(Config::XML_BACKEND_CONFIG_PATH, false);
            }

            if ($frontend === $backend) {
                $this->config->setValueConfig(Config::XML_FRONTEND_CONFIG_PATH, false);
                $this->config->setValueConfig(Config::XML_BACKEND_CONFIG_PATH, false);
                $this->maintenanceMode->set(false);
            }
        }
    }
}
