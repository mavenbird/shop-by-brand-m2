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

namespace Mavenbird\Shopbybrand\Block\Link;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Html\Link;
use Magento\Framework\View\Element\Template\Context;
use Mavenbird\Shopbybrand\Helper\Data;
use Mavenbird\Shopbybrand\Model\Config\Source\BrandPosition;

class Top extends Link
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
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;

        parent::__construct($context, $data);
    }

    /**
     * To Html
     *
     * @return void
     */
    protected function _toHtml()
    {
        if (!$this->helper->canShowBrandLink(BrandPosition::TOPLINK)) {
            return '';
        }

        return parent::_toHtml();
    }

    /**
     * Href
     *
     * @return void
     */
    public function getHref()
    {
        return $this->helper->getBrandUrl();
    }

    /**
     * Label
     *
     * @return void
     */
    public function getLabel()
    {
        return $this->helper->getBrandTitle();
    }

    /**
     * Sort Order
     *
     * @return void
     */
    public function getSortOrder()
    {
        return $this->getData('sortOrder');
    }
}
