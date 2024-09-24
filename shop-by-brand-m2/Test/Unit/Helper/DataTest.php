<?php
/**
 * Mavenbird Technologies Private Limited
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mavenbird.com/Mavenbird-Module-License.txt
 *
 * =================================================================
 *
 * @category   Mavenbird
 * @package    Mavenbird_Shopbybrand
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */

namespace Mavenbird\Shopbybrand\Test\Unit\Helper;

use Mavenbird\Shopbybrand\Helper\Data;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Cms\Model\Template\Filter;
use Mavenbird\Shopbybrand\Model\Brand;

class DataTest extends TestCase
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * @var FilterProvider|\PHPUnit\Framework\MockObject\MockObject
     */
    private $filterProviderMock;

    /**
     * @var Filter|\PHPUnit\Framework\MockObject\MockObject
     */
    private $filterMock;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->filterProviderMock = $this->createMock(FilterProvider::class);
        $this->filterMock = $this->createMock(Filter::class);

        $this->filterProviderMock->expects($this->any())
            ->method('getBlockFilter')
            ->willReturn($this->filterMock);

        $this->helper = $objectManager->getObject(Data::class, [
            'filterProvider' => $this->filterProviderMock
        ]);
    }

    /**
     * Test get brand description
     *
     * @return void
     */
    public function testGetBrandDescriptionShort()
    {
        $brand = $this->createMock(Brand::class);
        $brand->expects($this->once())
            ->method('getShortDescription')
            ->willReturn('Short description');

        $this->filterMock->expects($this->once())
            ->method('filter')
            ->with('Short description')
            ->willReturn('Filtered short description');

        $result = $this->helper->getBrandDescription($brand, true);
        $this->assertEquals('Filtered short description', $result);
    }

    /**
     * Test get brand description long
     *
     * @return void
     */
    public function testGetBrandDescriptionLong()
    {
        $brand = $this->createMock(Brand::class);
        $brand->expects($this->once())
            ->method('getDescription')
            ->willReturn('Long description');

        $this->filterMock->expects($this->once())
            ->method('filter')
            ->with('Long description')
            ->willReturn('Filtered long description');

        $result = $this->helper->getBrandDescription($brand, false);
        $this->assertEquals('Filtered long description', $result);
    }

    /**
     * Test get brand description empty
     *
     * @return void
     */
    public function testGetBrandDescriptionEmptyShort()
    {
        $brand = $this->createMock(Brand::class);
        $brand->expects($this->once())
            ->method('getShortDescription')
            ->willReturn('');

        $this->filterMock->expects($this->once())
            ->method('filter')
            ->with('')
            ->willReturn('');

        $result = $this->helper->getBrandDescription($brand, true);
        $this->assertEquals('', $result);
    }
    
    /**
     * Test get brand description empty long
     *
     * @return void
     */
    public function testGetBrandDescriptionEmptyLong()
    {
        $brand = $this->createMock(Brand::class);
        $brand->expects($this->once())
            ->method('getDescription')
            ->willReturn('');

        $this->filterMock->expects($this->once())
            ->method('filter')
            ->with('')
            ->willReturn('');

        $result = $this->helper->getBrandDescription($brand, false);
        $this->assertEquals('', $result);
    }

    /**
     * Test get brand description exception
     *
     * @return void
     */
    public function testGetBrandDescriptionException()
    {
        $brand = $this->createMock(Brand::class);
        $brand->expects($this->once())
            ->method('getDescription')
            ->willReturn('Description');

        $this->filterMock->expects($this->once())
            ->method('filter')
            ->willThrowException(new \Exception('Filter error'));

        $result = $this->helper->getBrandDescription($brand, false);
        $this->assertEquals('', $result);
        $this->filterMock->expects($this->once())
        ->method('filter')
        ->willThrowException(new \Exception('Filter error'));

        $result = $this->helper->getBrandDescription($brand, false);
        $this->assertEquals('', $result);
    }
}
