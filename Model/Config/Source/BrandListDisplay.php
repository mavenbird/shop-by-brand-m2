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
 * Class BrandListDisplay
 *
 * @package Mavenbird\Shopbybrand\Model\Config\Source
 */
class BrandListDisplay implements OptionSourceInterface
{
    /**
     * Display only logo
     */
    const DISPLAY_LOGO = '0';
    /**
     * Display logo and label
     */
    const DISPLAY_LOGO_AND_LABEL = '1';
    /**
     * Display label
     */
    const DISPLAY_LABEL = '2';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __('Logo Only'),
                'value' => self::DISPLAY_LOGO
            ],
            [
                'label' => __('Logo and Label'),
                'value' => self::DISPLAY_LOGO_AND_LABEL
            ],
            [
                'label' => __('Label Only'),
                'value' => self::DISPLAY_LABEL
            ],
        ];
    }
}
