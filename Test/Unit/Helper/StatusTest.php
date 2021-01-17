<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_MaintenanceMode
 */

namespace Greenrivers\MaintenanceMode\Test\Unit\Helper;

use Greenrivers\MaintenanceMode\Helper\Config;
use Greenrivers\MaintenanceMode\Helper\Status;
use Greenrivers\MaintenanceMode\Test\Unit\Traits\TraitObjectManager;
use Greenrivers\MaintenanceMode\Test\Unit\Traits\TraitReflectionClass;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class StatusTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var Status */
    private $status;

    /** @var Config|MockObject */
    private $configMock;

    /** @var DeploymentConfig|MockObject */
    private $deploymentConfigMock;

    /** @var UrlInterface|MockObject */
    private $urlBuilderMock;

    protected function setUp(): void
    {
        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->deploymentConfigMock = $this->getMockBuilder(DeploymentConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->urlBuilderMock = $this->getMockBuilder(UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $properties = [
            $this->configMock,
            $this->deploymentConfigMock,
            $this->urlBuilderMock
        ];
        $this->status = $this->getObjectManager()->getObject(Status::class, $properties);
        $this->setAccessibleProperties($this->status, $properties);
    }

    /**
     * @covers Status::updateStatus
     */
    public function testUpdateStatusFrontend()
    {
        $this->urlBuilderMock->expects(self::once())
            ->method('getCurrentUrl')
            ->willReturn('http://magento2.loc/test');
        $this->urlBuilderMock->expects(self::once())
            ->method('getBaseUrl')
            ->willReturn('http://magento2.loc/');
        $this->deploymentConfigMock->expects(self::once())
            ->method('get')
            ->with('backend/frontName')
            ->willReturn('admin');
        $this->configMock->expects(self::once())
            ->method('getFrontendConfig')
            ->willReturn(true);

        $this->assertTrue($this->status->updateStatus(true));
    }

    /**
     * @covers Status::updateStatus
     */
    public function testUpdateStatusBackend()
    {
        $this->urlBuilderMock->expects(self::once())
            ->method('getCurrentUrl')
            ->willReturn('http://magento2.loc/admin/catalog/product/index');
        $this->urlBuilderMock->expects(self::once())
            ->method('getBaseUrl')
            ->willReturn('http://magento2.loc/');
        $this->deploymentConfigMock->expects(self::once())
            ->method('get')
            ->with('backend/frontName')
            ->willReturn('admin');
        $this->configMock->expects(self::once())
            ->method('getBackendConfig')
            ->willReturn(true);

        $this->assertTrue($this->status->updateStatus(true));
    }
}
