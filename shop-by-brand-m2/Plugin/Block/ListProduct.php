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

namespace Mavenbird\Shopbybrand\Plugin\Block;

use Magento\Catalog\Model\Product;
use Mavenbird\Shopbybrand\Helper\Data;

class ListProduct
{
    /**
     * Data
     *
     * @var [type]
     */
    protected $helper;

    /**
     * Construct
     *
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * Around get product price
     *
     * @param \Magento\Catalog\Block\Product\ListProduct $listProduct
     * @param callable $proceed
     * @param Product $product
     * @return void
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
