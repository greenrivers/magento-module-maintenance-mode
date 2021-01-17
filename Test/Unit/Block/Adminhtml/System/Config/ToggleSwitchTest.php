<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_MaintenanceMode
 */

namespace Greenrivers\MaintenanceMode\Test\Unit\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Greenrivers\MaintenanceMode\Block\Adminhtml\System\Config\ToggleSwitch;
use Greenrivers\MaintenanceMode\Helper\Config;
use Greenrivers\MaintenanceMode\Test\Unit\Traits\TraitObjectManager;
use Greenrivers\MaintenanceMode\Test\Unit\Traits\TraitReflectionClass;

class ToggleSwitchTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var ToggleSwitch */
    private $toggleSwitch;

    /** @var Config|MockObject */
    private $configMock;

    /** @var AbstractElement|MockObject */
    private $elementMock;

    protected function setUp(): void
    {
        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->elementMock = $this->getMockBuilder(AbstractElement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->toggleSwitch = $this->getObjectManager()->getObject(ToggleSwitch::class);

        $properties = [$this->configMock, $this->elementMock];
        $this->toggleSwitch = $this->getObjectManager()->getObject(ToggleSwitch::class, $properties);
        $this->setAccessibleProperties($this->toggleSwitch, $properties);
    }

    /**
     * @covers ToggleSwitch::getComponent()
     */
    public function testGetComponent()
    {
        $this->elementMock->expects(self::exactly(3))
            ->method('getHtmlId')
            ->willReturn('maintenance_mode_custom_page_enabled');
        $this->elementMock->expects(self::exactly(3))
            ->method('getName')
            ->willReturn('groups[maintenance_mode][groups][custom_page][fields][enabled][value]');
        $this->configMock->expects(self::exactly(3))
            ->method('getCustomPageEnabledConfig')
            ->willReturn(true);

        $this->toggleSwitch->setElement($this->elementMock);

        $this->assertEquals(
            [
                'id' => 'maintenance-mode-custom-page-enabled',
                'name' => 'groups[maintenance_mode][groups][custom_page][fields][enabled][value]',
                'value' => true
            ],
            $this->toggleSwitch->getComponent()
        );
        $this->assertCount(3, $this->toggleSwitch->getComponent());
        $this->assertArrayHasKey('value', $this->toggleSwitch->getComponent());
    }
}
