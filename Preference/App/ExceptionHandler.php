<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_MaintenanceMode
 */

namespace Greenrivers\MaintenanceMode\Preference\App;

use Greenrivers\MaintenanceMode\Helper\Config;
use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\ExceptionHandler as BaseExceptionHandler;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\App\Response\Http as ResponseHttp;
use Magento\Framework\App\SetupInfo;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Filesystem;
use Psr\Log\LoggerInterface;

class ExceptionHandler extends BaseExceptionHandler
{
    /** @var Filesystem */
    private $filesystem;

    /** @var Config */
    private $config;

    /**
     * ExceptionHandler constructor.
     * @param EncryptorInterface $encryptor
     * @param Filesystem $filesystem
     * @param LoggerInterface $logger
     * @param Config $config
     */
    public function __construct(
        EncryptorInterface $encryptor,
        Filesystem $filesystem,
        LoggerInterface $logger,
        Config $config
    ) {
        parent::__construct($encryptor, $filesystem, $logger);

        $this->filesystem = $filesystem;
        $this->config = $config;
    }

    /**
     * Handles exception of HTTP web application
     *
     * @param Bootstrap $bootstrap
     * @param \Exception $exception
     * @param ResponseHttp $response
     * @param RequestHttp $request
     * @return bool
     */
    public function handle(
        Bootstrap $bootstrap,
        \Exception $exception,
        ResponseHttp $response,
        RequestHttp $request
    ): bool {
        return $this->handleBootstrapErrors($bootstrap, $exception, $response) ||
            parent::handle($bootstrap, $exception, $response, $request);
    }

    /**
     * @param Bootstrap $bootstrap
     * @param \Exception $exception
     * @param ResponseHttp $response
     * @return bool
     */
    private function handleBootstrapErrors(
        Bootstrap $bootstrap,
        \Exception &$exception,
        ResponseHttp $response
    ): bool {
        $bootstrapCode = $bootstrap->getErrorCode();
        if (Bootstrap::ERR_MAINTENANCE == $bootstrapCode) {
            // phpcs:ignore Magento2.Security.IncludeFile
            require $this->filesystem
                ->getDirectoryRead(DirectoryList::APP)
                ->getAbsolutePath('code/Greenrivers/MaintenanceMode/pub/errors/503.php');
            return true;
        }
        if (Bootstrap::ERR_IS_INSTALLED == $bootstrapCode) {
            try {
                $this->redirectToSetup($bootstrap, $exception, $response);
                return true;
            } catch (\Exception $e) {
                $exception = $e;
            }
        }
        return false;
    }

    /**
     * @param Bootstrap $bootstrap
     * @param \Exception $exception
     * @param ResponseHttp $response
     * @throws \Exception
     */
    private function redirectToSetup(Bootstrap $bootstrap, \Exception $exception, ResponseHttp $response)
    {
        $setupInfo = new SetupInfo($bootstrap->getParams());
        $projectRoot = $this->filesystem->getDirectoryRead(DirectoryList::ROOT)->getAbsolutePath();
        if ($setupInfo->isAvailable()) {
            $response->setRedirect($setupInfo->getUrl());
            $response->sendHeaders();
        } else {
            $newMessage = $exception->getMessage() . "\nNOTE: You cannot install Magento using the Setup Wizard "
                . "because the Magento setup directory cannot be accessed. \n"
                . 'You can install Magento using either the command line or you must restore access '
                . 'to the following directory: ' . $setupInfo->getDir($projectRoot) . "\n";
            // phpcs:ignore Magento2.Exceptions.DirectThrow
            throw new \Exception($newMessage, 0, $exception);
        }
    }

    /**
     * @return string
     */
    public function getProcessorFactory(): string
    {
        return $this->filesystem
            ->getDirectoryRead(DirectoryList::APP)
            ->getAbsolutePath('code/Greenrivers/MaintenanceMode/pub/errors/ProcessorFactory.php');
    }
}
