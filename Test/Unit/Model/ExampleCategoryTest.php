<?php

namespace Nosto\Tagging\Test\Unit\Model;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Nosto\Tagging\Model\Category\Builder as NostoCategoryBuilder;

/**
 * Class CategoryTest
 * Note that this is just a dummy test for an example
 * @package Nosto\Tagging\Test\Unit\Model
 */
class ExampleCategoryTest extends \PHPUnit_Framework_TestCase
{
    const CATEGORY_PATH = '1/2/15'; // /Men/Tops/Hoodies

    /**
     * @var NostoCategoryBuilder
     */
    protected $nostoCategoryBuilder;

    /**
     * @var \NostoCategory
     */
    protected $nostoCategory;

    /**
     * @var CategoryRepository
     */

    protected $mageCategoryRepository;

    /**
     * @var Category
     */
    protected $mageCategory;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    public function setUp()
    {
        $mockNames = [
            1 => 'Men',
            2 => 'Tops',
            15 => 'Hoodies',
        ];

        $category = $this->getMockBuilder('Magento\Catalog\Api\Data\CategoryInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $category->expects($this->any())
            ->method('getPath')
            ->willReturn(self::CATEGORY_PATH);

        $mockCategory = $this->getMockBuilder('Magento\Catalog\Api\Data\CategoryInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $mockCategory->expects($this->at(1))
            ->method('getName')
            ->willReturn('Men');
        $mockCategory->expects($this->at(3))
            ->method('getName')
            ->willReturn('Tops');
        $mockCategory->expects($this->at(5))
            ->method('getName')
            ->willReturn('Hoodies');
        $mockCategory->expects($this->any())
            ->method('getLevel')
            ->willReturn(2);

        $mageCategoryRepository = $this->getMockBuilder('Magento\Catalog\Api\CategoryRepositoryInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $mageCategoryRepository->expects($this->any())
            ->method('get')
            ->willReturn($mockCategory);

        $logger = $this->getMockBuilder('Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $logger->expects($this->any())
            ->method('error')
            ->willReturn($this->returnValue(true));

        $this->nostoCategoryBuilder = (new ObjectManager($this))->getObject(
            'Nosto\Tagging\Model\Category\Builder',
            [
                'categoryRepository' => $mageCategoryRepository,
                'logger' => $logger
            ]
        );

        $this->nostoCategory = $this->nostoCategoryBuilder->build($category);
    }

    public function testValidCategoryName()
    {
        $this->assertEquals('/Men/Tops/Hoodies', $this->nostoCategory->getPath());
    }
}
