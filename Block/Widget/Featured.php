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

namespace Mavenbird\Shopbybrand\Block\Widget;

use Magento\Framework\View\Element\Template\Context;
use Mavenbird\Shopbybrand\Helper\Data as Helper;
use Mavenbird\Shopbybrand\Model\BrandFactory;

/**
 * Class Featured
 *
 * @package Mavenbird\Shopbybrand\Block\Brand
 */
class Featured extends AbstractBrand
{
    /**
     * @var string
     */
    protected $_template = 'Mavenbird_Shopbybrand::widget/brandlist.phtml';

    /**
     * @type BrandFactory
     */
    protected $_brandFactory;

    /**
     * Featured constructor.
     *
     * @param Context $context
     * @param BrandFactory $brandFactory
     * @param Helper $helper
     */
    public function __construct(
        Context $context,
        BrandFactory $brandFactory,
        Helper $helper
    ) {
        $this->_brandFactory = $brandFactory;

        parent::__construct($context, $helper);
    }

    /**
     * get feature brand
     *
     * @return array
     */
    public function getCollection()
    {
        $featureBrands = [];
        $collection    = $this->_brandFactory->create()->getBrandCollection();
        foreach ($collection as $brand) {
            if ($brand->getIsFeatured()) {
                $featureBrands[] = $brand;
            }
        }

        return $featureBrands;
    }
}
