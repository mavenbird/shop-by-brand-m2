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

namespace Mavenbird\Shopbybrand\Model\Config\Source\Widget;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class ShowType
 * @package Mavenbird\Shopbybrand\Model\Config\Source\Widget
 */
class ShowType implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'option_id', 'label' => __('Brand By OptionID Widget')],
            ['value' => 'featured', 'label' => __('Featured Brand Widget')]
        ];
    }
}
