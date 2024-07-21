<?php
/**
 * Mavenbird
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mavenbird.com license that is
 * available through the world-wide-web at this URL:
 * https://www.Mavenbird.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mavenbird
 * @package     Mavenbird_Shopbybrand
 * @copyright   Copyright (c) Mavenbird (https://www.Mavenbird.com/)
 * @license     https://www.Mavenbird.com/LICENSE.txt
 */

namespace Mavenbird\Shopbybrand\Block\Sidebar;

use Magento\Framework\Exception\NoSuchEntityException;
use Mavenbird\Shopbybrand\Block\Brand;
use Mavenbird\Shopbybrand\Model\ResourceModel\Category\Collection;

class BrandCategory extends Brand
{
    /**
     * Templates
     *
     * @var string
     */
    protected $_template = 'Mavenbird_Shopbybrand::sidebar/category.phtml';

    public const TITLE = 'Brand Category';
    public const LIMIT = '7';

    /**
     * Cat Url
     *
     * @param [type] $cat
     * @return void
     */
    public function getCatUrl($cat)
    {
        return $this->helper()->getCatUrl($cat);
    }

    /**
     * Title
     *
     * @return void
     */
    public function getTitle()
    {
        return $this->helper->getSidebarConfig('category_brand/title') ?: self::TITLE;
    }

    /**
     * Categories
     *
     * @return void
     */
    public function getCategories()
    {
        $categories = parent::getCategories();
        $limit      = $this->helper->getSidebarConfig('category_brand/limit_categories') ?: self::LIMIT;
        $size       = $categories->getSize();
        if ($size && $size > $limit) {
            $categories->setPageSize($limit);
        }

        return $categories;
    }

    /**
     * Show Brand Qty
     *
     * @return void
     */
    public function showBrandQty()
    {
        return $this->helper->getSidebarConfig('category_brand/show_brand_qty');
    }
}
