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

namespace Mavenbird\Shopbybrand\Plugin\Link;

use Mavenbird\Shopbybrand\Block\Link\CategoryMenu;
use Mavenbird\Shopbybrand\Helper\Data;
use Mavenbird\Shopbybrand\Model\Config\Source\BrandPosition;

/**
 * Class TopMenuPorto
 * @package Mavenbird\Shopbybrand\Plugin\Link
 */
class TopMenuPorto
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * TopMenuPorto constructor.
     *
     * @param Data $helperData
     */
    public function __construct(Data $helperData)
    {
        $this->helperData = $helperData;
    }

    /**
     * @param $topmenu
     * @param $html
     *
     * @return string
     */
    public function afterGetMegamenuHtml($topmenu, $html)
    {
        if (!$this->helperData->isEnabled() || !$this->helperData->canShowBrandLink(BrandPosition::CATEGORY)) {
            return $html;
        }
        $brandHtml = $topmenu->getLayout()->createBlock(CategoryMenu::class)
            ->setTemplate('Mavenbird_Shopbybrand::position/topmenuporto.phtml')->toHtml();

        return $html . $brandHtml;
    }
}
