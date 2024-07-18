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
 * Class BrandListStyle
 *
 * @package Mavenbird\Shopbybrand\Model\Config\Source
 */
class BrandListStyle implements OptionSourceInterface
{
    /**
     * Display listing
     */
    const DISPLAY_LISTING = '0';
    /**
     * Display alphabet listing
     */
    const DISPLAY_ALPHABET_LISTING = '1';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __('List View'),
                'value' => self::DISPLAY_LISTING
            ],
            [
                'label' => __('Alphabet View'),
                'value' => self::DISPLAY_ALPHABET_LISTING
            ],
        ];
    }
}
