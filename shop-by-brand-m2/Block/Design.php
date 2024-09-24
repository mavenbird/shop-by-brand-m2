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

namespace Mavenbird\Shopbybrand\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mavenbird\Shopbybrand\Helper\Data as HelperData;

class Design extends Template
{
    /**
     * Data
     *
     * @var [type]
     */
    protected $_helperConfig;

    /**
     * Constructor
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
     * Helper
     *
     * @return void
     */
    public function helper()
    {
        return $this->_helperConfig;
    }

    /**
     * Custom Css
     *
     * @return void
     */
    public function getCustomCss()
    {
        return $this->_helperConfig->getBrandConfig('custom_css');
    }

    /**
     * Logo Width
     *
     * @return void
     */
    public function getLogoWidth()
    {
        return $this->_helperConfig->getModuleConfig('brandpage/brand_logo_width');
    }

    /**
     * Logo Height
     *
     * @return void
     */
    public function getLogoHeight()
    {
        return $this->_helperConfig->getModuleConfig('brandpage/brand_logo_height');
    }
}
