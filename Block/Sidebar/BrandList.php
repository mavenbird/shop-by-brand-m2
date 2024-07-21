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

class BrandList extends Brand
{
    /**
     * Templates
     *
     * @var string
     */
    protected $_template = 'Mavenbird_Shopbybrand::sidebar/list.phtml';

    public const TITLE = 'Brand List';
    public const LIMIT = '7';

    /**
     * Title
     *
     * @return void
     */
    public function getTitle()
    {
        return $this->helper->getModuleConfig('sidebar/brand_thumbnail/title') ?: self::TITLE;
    }

    /**
     * @inheritDoc
     */
    public function getCollection($type = null, $option = null)
    {
        $collection = parent::getCollection($type, $option);
        $brands     = [];
        $limit      = $this->helper->getModuleConfig('sidebar/brand_thumbnail/limit_brands') ?: self::LIMIT;
        foreach ($collection as $brand) {
            if ($this->getProductQuantity($brand->getOptionId())) {
                $brands[] = $brand;
            }
            if (count($brands) >= $limit) {
                break;
            }
        }

        return $brands;
    }

    /**
     * Brand Url
     *
     * @param [type] $brand
     * @return void
     */
    public function getBrandUrl($brand = null)
    {
        return $this->helper->getBrandUrl($brand);
    }

    /**
     * Brand Image Url
     *
     * @param [type] $brand
     * @return void
     */
    public function getBrandImageUrl($brand)
    {
        return $this->helper->getBrandImageUrl($brand);
    }
}
