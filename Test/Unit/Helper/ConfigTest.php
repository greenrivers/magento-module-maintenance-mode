<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\Test\Unit\Helper;

use GreenRivers\MaintenanceMode\Test\Unit\Traits\TraitObjectManager;
use GreenRivers\MaintenanceMode\Helper\Config;
use GreenRivers\MaintenanceMode\Test\Unit\Traits\TraitReflectionClass;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json as Serializer;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class ConfigTest extends TestCase
{
    use TraitObjectManager;

    /** @var Config */
    private $config;

    /** @var ScopeConfigInterface|PHPUnit_Framework_MockObject_MockObject */
    private $scopeConfigMock;

    /** @var Serializer|PHPUnit_Framework_MockObject_MockObject */
    private $serializerMock;

    protected function setUp()
    {
        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->serializerMock = $this->getMockBuilder(Serializer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->config = $this->getObjectManager()->getObject(
            Config::class,
            [
                'scopeConfig' => $this->scopeConfigMock,
                'serializer' => $this->serializerMock
            ]
        );
    }

    /**
     * @covers Config::getEnabledConfig
     */
    public function testGetEnabledConfig()
    {
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('maintenance_mode/general/enabled', 'store')
            ->willReturn(true);

        $this->assertTrue($this->config->getEnabledConfig());
    }

    /**
     * @covers Config::getFrontendConfig
     */
    public function testGetFrontendConfig()
    {
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('maintenance_mode/settings/frontend', 'store')
            ->willReturn(true);

        $this->assertTrue($this->config->getFrontendConfig());
    }

    /**
     * @covers Config::getBackendConfig
     */
    public function testGetBackendConfig()
    {
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('maintenance_mode/settings/backend', 'store')
            ->willReturn(true);

        $this->assertTrue($this->config->getBackendConfig());
    }

    /**
     * @covers Config::getWhitelistIpsConfig
     */
    public function testGetWhitelistIpsConfig()
    {
        $value = '[{"ip":"127.0.0.1"},{"ip":"127.0.0.2"},{"ip":"127.0.0.3"}]';
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('maintenance_mode/settings/whitelist_ips', 'store')
            ->willReturn($value);
        $this->serializerMock->expects(self::exactly(2))
            ->method('unserialize')
            ->with($value)
            ->willReturn(json_decode($value, true));

        $this->assertEquals('127.0.0.1,127.0.0.2,127.0.0.3', $this->config->getWhitelistIpsConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getWhitelistIpsConfig());
    }

    /**
     * @covers Config::getCustomPageEnabledConfig
     */
    public function testGetCustomPageEnabledConfig()
    {
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('maintenance_mode/custom_page/enabled', 'store')
            ->willReturn(true);

        $this->assertTrue($this->config->getCustomPageEnabledConfig());
    }

    /**
     * @covers Config::getCustomPageStylesConfig
     */
    public function testGetCustomPageStylesConfig()
    {
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('maintenance_mode/custom_page/styles', 'store')
            ->willReturn('body {background: whitesmoke;}');

        $this->assertEquals('body {background: whitesmoke;}', $this->config->getCustomPageStylesConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getCustomPageStylesConfig());
    }

    /**
     * @covers Config::getCustomPageContentConfig
     */
    public function testGetCustomPageContentConfig()
    {
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('maintenance_mode/custom_page/content', 'store')
            ->willReturn('<p>Maintenance mode unit test</p>');

        $this->assertEquals('<p>Maintenance mode unit test</p>', $this->config->getCustomPageContentConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getCustomPageContentConfig());
    }

    /**
     * @covers Config::getCronFrontendEnabledConfig
     */
    public function testGetCronFrontendEnabledConfig()
    {
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('maintenance_mode/cron/frontend/enabled', 'store')
            ->willReturn(true);

        $this->assertTrue($this->config->getCronFrontendEnabledConfig());
    }

    /**
     * @covers Config::getCronFrontendFrequencyConfig
     */
    public function testGetCronFrontendFrequencyConfig()
    {
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('maintenance_mode/cron/frontend/frequency', 'store')
            ->willReturn('D');

        $this->assertEquals('D', $this->config->getCronFrontendFrequencyConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getCronFrontendFrequencyConfig());
    }

    /**
     * @covers Config::getCronFrontendTimeStartConfig
     */
    public function testGetCronFrontendTimeStartConfig()
    {
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('maintenance_mode/cron/frontend/time_start', 'store')
            ->willReturn('11,30,00');

        $this->assertEquals('11,30,00', $this->config->getCronFrontendTimeStartConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getCronFrontendTimeStartConfig());
    }

    /**
     * @covers Config::getCronFrontendTimeEndConfig
     */
    public function testGetCronFrontendTimeEndConfig()
    {
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('maintenance_mode/cron/frontend/time_end', 'store')
            ->willReturn('12,00,00');

        $this->assertEquals('12,00,00', $this->config->getCronFrontendTimeEndConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getCronFrontendTimeEndConfig());
    }

    /**
     * @covers Config::getCronBackendEnabledConfig
     */
    public function testGetCronBackendEnabledConfig()
    {
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('maintenance_mode/cron/backend/enabled', 'store')
            ->willReturn(true);

        $this->assertTrue($this->config->getCronBackendEnabledConfig());
    }

    /**
     * @covers Config::getCronBackendFrequencyConfig
     */
    public function testGetCronBackendFrequencyConfig()
    {
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('maintenance_mode/cron/backend/frequency', 'store')
            ->willReturn('D');

        $this->assertEquals('D', $this->config->getCronBackendFrequencyConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getCronBackendFrequencyConfig());
    }

    /**
     * @covers Config::getCronBackendTimeStartConfig
     */
    public function testGetCronBackendTimeStartConfig()
    {
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('maintenance_mode/cron/backend/time_start', 'store')
            ->willReturn('14,00,00');

        $this->assertEquals('14,00,00', $this->config->getCronBackendTimeStartConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getCronBackendTimeStartConfig());
    }

    /**
     * @covers Config::getCronBackendTimeEndConfig
     */
    public function testGetCronBackendTimeEndConfig()
    {
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('maintenance_mode/cron/backend/time_end', 'store')
            ->willReturn('14,15,00');

        $this->assertEquals('14,15,00', $this->config->getCronBackendTimeEndConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getCronBackendTimeEndConfig());
    }

    /**
     * @covers Config::getCronWhitelistIpsConfig
     */
    public function testGetCronWhitelistIpsConfig()
    {
        $value = '[{"ip":"127.0.0.5"},{"ip":"127.0.0.6"}]';
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('maintenance_mode/cron/whitelist_ips', 'store')
            ->willReturn($value);
        $this->serializerMock->expects(self::exactly(2))
            ->method('unserialize')
            ->with($value)
            ->willReturn(json_decode($value, true));

        $this->assertEquals('127.0.0.5,127.0.0.6', $this->config->getCronWhitelistIpsConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getCronWhitelistIpsConfig());
    }
}
