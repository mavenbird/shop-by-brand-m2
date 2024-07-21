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

class Logo extends Template
{
    /**
     * Data
     *
     * @var [type]
     */
    protected $helper;

    /**
     * Constructor
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
     * Brand Object
     *
     * @return void
     */
    public function getBrandObject()
    {
        if (!$this->helper->isEnabled() || $this->showBrandInfo() === ShowBrandInfo::NOT_SHOW) {
            return null;
        }

        return $this->helper->getBrandObject();
    }

    /**
     * Helper
     *
     * @return void
     */
    public function helper()
    {
        return $this->helper;
    }

    /**
     * Show Brand Info
     *
     * @return void
     */
    public function showBrandInfo()
    {
        return $this->helper->getConfigGeneral('show_brand_info');
    }

    /**
     * Logo Width
     *
     * @return void
     */
    public function getLogoWidth()
    {
        return $this->helper->getConfigGeneral('logo_width_on_product_page');
    }

    /**
     * Logo Height
     *
     * @return void
     */
    public function getLogoHeight()
    {
        return $this->helper->getConfigGeneral('logo_height_on_product_page');
    }
}
