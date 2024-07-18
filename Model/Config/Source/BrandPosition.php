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

namespace Mavenbird\Shopbybrand\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class BrandPosition
 * @package Mavenbird\Shopbybrand\Model\Config\Source
 */
class BrandPosition implements OptionSourceInterface
{
    /**
     * Show on Toplink
     */
    const TOPLINK = '0';
    /**
     * Show on Footer link
     */
    const FOOTERLINK = '1';
    /**
     * Show on Menubar
     */
    const CATEGORY = '2';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __('-- Please select --'),
                'value' => '',
            ],
            [
                'label' => __('Toplink'),
                'value' => self::TOPLINK,
            ],
            [
                'label' => __('Footer link'),
                'value' => self::FOOTERLINK,
            ],
            [
                'label' => __('Category'),
                'value' => self::CATEGORY,
            ],
        ];
    }
}
