<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

namespace GreenRivers\MaintenanceMode\Test\Unit\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use GreenRivers\MaintenanceMode\Block\Adminhtml\System\Config\ToggleSwitch;
use GreenRivers\MaintenanceMode\Helper\Config;
use GreenRivers\MaintenanceMode\Test\Unit\Traits\TraitObjectManager;
use GreenRivers\MaintenanceMode\Test\Unit\Traits\TraitReflectionClass;

class ToggleSwitchTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var ToggleSwitch */
    private $toggleSwitch;

    /** @var Config|PHPUnit_Framework_MockObject_MockObject */
    private $configMock;

    /** @var AbstractElement|PHPUnit_Framework_MockObject_MockObject */
    private $elementMock;

    protected function setUp()
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
        $this->elementMock->expects(self::exactly(4))
            ->method('getHtmlId')
            ->willReturn('maintenance_mode_custom_page_enabled');
        $this->elementMock->expects(self::exactly(4))
            ->method('getName')
            ->willReturn('groups[maintenance_mode][groups][custom_page][fields][enabled][value]');
        $this->configMock->expects(self::exactly(4))
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
        $this->assertInternalType(IsType::TYPE_ARRAY, $this->toggleSwitch->getComponent());
    }
}
