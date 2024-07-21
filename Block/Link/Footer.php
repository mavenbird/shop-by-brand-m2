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

use Magento\Framework\App\DefaultPathInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Html\Link\Current;
use Magento\Framework\View\Element\Template\Context;
use Mavenbird\Shopbybrand\Helper\Data;
use Mavenbird\Shopbybrand\Model\Config\Source\BrandPosition;

class Footer extends Current
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
     * @param DefaultPathInterface $defaultPath
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        DefaultPathInterface $defaultPath,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $data);

        $this->helper = $helper;
    }

    /**
     * To Html
     *
     * @return void
     */
    protected function _toHtml()
    {
        if (!$this->helper->canShowBrandLink(BrandPosition::FOOTERLINK)) {
            return '';
        }

        $this->setData([
            'label' => $this->helper->getBrandTitle(),
            'path'  => $this->helper->getRoute()
        ]);

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
}
