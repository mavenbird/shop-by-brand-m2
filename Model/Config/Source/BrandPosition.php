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

namespace Mavenbird\Shopbybrand\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class BrandPosition implements OptionSourceInterface
{
    public const TOPLINK = '0';
    public const FOOTERLINK = '1';
    public const CATEGORY = '2';
    
    /**
     * Options Array
     *
     * @return void
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
