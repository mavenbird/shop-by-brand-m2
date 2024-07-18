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

namespace Mavenbird\Shopbybrand\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mavenbird\Shopbybrand\Helper\Data as HelperData;

/**
 * Class Design
 * @package Mavenbird\Shopbybrand\Block
 */
class Design extends Template
{
    /**
     * @var HelperData
     */
    protected $_helperConfig;

    /**
     * Design constructor.
     *
     * @param Context $context
     * @param HelperData $helperConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        HelperData $helperConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->_helperConfig = $helperConfig;
    }

    /**
     * @return HelperData
     */
    public function helper()
    {
        return $this->_helperConfig;
    }

    /**
     * Retrieve custom css code
     * @return mixed
     */
    public function getCustomCss()
    {
        return $this->_helperConfig->getBrandConfig('custom_css');
    }

    /**
     * @return mixed
     */
    public function getLogoWidth()
    {
        return $this->_helperConfig->getModuleConfig('brandpage/brand_logo_width');
    }

    /**
     * @return mixed
     */
    public function getLogoHeight()
    {
        return $this->_helperConfig->getModuleConfig('brandpage/brand_logo_height');
    }
}
