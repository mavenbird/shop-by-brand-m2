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

namespace Mavenbird\Shopbybrand\Plugin\Block;

use Magento\Catalog\Model\Product;
use Mavenbird\Shopbybrand\Helper\Data;

/**
 * Class ListProduct
 * @package Mavenbird\Shopbybrand\Plugin\Block
 */
class ListProduct
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * ListProduct constructor.
     *
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Catalog\Block\Product\ListProduct $listProduct
     * @param callable $proceed
     * @param Product $product
     *
     * @return string
     */
    public function aroundGetProductPrice(
        \Magento\Catalog\Block\Product\ListProduct $listProduct,
        callable $proceed,
        Product $product
    ) {
        return $this->helper->getConfigGeneral('show_brand_name')
            ? $this->helper->getBrandTextFromProduct($product) . $proceed($product)
            : $proceed($product);
    }
}
