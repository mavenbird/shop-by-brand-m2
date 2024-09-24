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

namespace Mavenbird\Shopbybrand\Block\Widget;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection;
use Magento\Framework\View\Element\Template\Context;
use Mavenbird\Shopbybrand\Helper\Data as Helper;
use Mavenbird\Shopbybrand\Model\BrandFactory;
use Mavenbird\Shopbybrand\Model\CategoryFactory;

class CategoryId extends AbstractBrand
{
    /**
     * Templates
     *
     * @var string
     */
    protected $_template = 'Mavenbird_Shopbybrand::widget/brandlist.phtml';

    /**
     * Factory for Brand
     *
     * @var [type]
     */
    protected $_brandFactory;

    /**
     * Factory for Category
     *
     * @var [type]
     */
    protected $_categoryFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param BrandFactory $brandFactory
     * @param CategoryFactory $categoryFactory
     * @param Helper $helper
     */
    public function __construct(
        Context $context,
        BrandFactory $brandFactory,
        CategoryFactory $categoryFactory,
        Helper $helper
    ) {
        $this->_categoryFactory = $categoryFactory;
        $this->_brandFactory    = $brandFactory;

        parent::__construct($context, $helper);
    }

    /**
     * Option Ids
     *
     * @return void
     */
    public function getOptionIds()
    {
        $str    = $this->getData('category_id');
        $sql    = 'main_table.cat_id IN (' . $str . ')';
        $result = [];
        $brands = $this->_categoryFactory->create()->getCategoryCollection($sql, null)->getData();
        foreach ($brands as $brand => $item) {
            $result[] = $item['option_id'];
        }

        return implode(',', array_unique($result));
    }

    /**
     * Collection
     *
     * @return void
     */
    public function getCollection()
    {
        $collection = $this->_brandFactory->create()->getBrandCollection(
            null,
            ['main_table.option_id' => ['in' => $this->getOptionIds()]]
        );

        return $collection;
    }
}
