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

namespace Mavenbird\Shopbybrand\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mavenbird\Shopbybrand\Helper\Data as Helper;
use Mavenbird\Shopbybrand\Model\Config\Source\ShowBrandInfo;

/**
 * Class Logo
 * @package Mavenbird\Shopbybrand\Block\Product
 */
class Logo extends Template
{
    /**
     * @var Helper
     */
    protected $helper;

    /**
     * Logo constructor.
     *
     * @param Context $context
     * @param Helper $helper
     */
    public function __construct(
        Context $context,
        Helper $helper
    ) {
        $this->helper = $helper;

        parent::__construct($context);
    }

    /**
     * @return mixed|null
     */
    public function getBrandObject()
    {
        if (!$this->helper->isEnabled() || $this->showBrandInfo() === ShowBrandInfo::NOT_SHOW) {
            return null;
        }

        return $this->helper->getBrandObject();
    }

    /**
     * @return Helper
     */
    public function helper()
    {
        return $this->helper;
    }

    /**
     * @return mixed
     */
    public function showBrandInfo()
    {
        return $this->helper->getConfigGeneral('show_brand_info');
    }

    /**
     * @return mixed
     */
    public function getLogoWidth()
    {
        return $this->helper->getConfigGeneral('logo_width_on_product_page');
    }

    /**
     * @return mixed
     */
    public function getLogoHeight()
    {
        return $this->helper->getConfigGeneral('logo_height_on_product_page');
    }
}
