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
 * Class ShowBrandInfo
 * @package Mavenbird\Shopbybrand\Model\Config\Source
 */
class ShowBrandInfo implements OptionSourceInterface
{
    const BRAND_NAME = 'name';
    const BRAND_LOGO = 'logo';
    const NOT_SHOW   = 'not-show';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::NOT_SHOW, 'label' => __('Not show')],
            ['value' => self::BRAND_NAME, 'label' => __('Brand Name')],
            ['value' => self::BRAND_LOGO, 'label' => __('Brand Logo')],
        ];
    }
}
