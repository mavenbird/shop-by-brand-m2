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
 * Class MegaMenu
 *
 * @package Mavenbird\Shopbybrand\Model\Config\Source
 */
class MegaMenu implements OptionSourceInterface
{
    /**
     * Display listing
     */
    const MENU_LISTING = 0;
    /**
     * Display mega listing
     */
    const MENU_MEGA_LISTING = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __('Listing'),
                'value' => self::MENU_LISTING
            ],
            [
                'label' => __('Mega Listing'),
                'value' => self::MENU_MEGA_LISTING
            ]
        ];
    }
}
