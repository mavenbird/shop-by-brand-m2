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
 * Class FeatureStyle
 *
 * @package Mavenbird\Shopbybrand\Model\Config\Source
 */
class FeatureStyle implements OptionSourceInterface
{
    /**
     * Display slider
     */
    const DISPLAY_SLIDER = '0';
    /**
     * Display simple
     */
    const DISPLAY_SIMPLE = '1';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __('Slider View'),
                'value' => self::DISPLAY_SLIDER
            ],
            [
                'label' => __('Simple View'),
                'value' => self::DISPLAY_SIMPLE
            ]
        ];
    }
}
