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

class BrandListDisplay implements OptionSourceInterface
{
    public const DISPLAY_LOGO = '0';
    public const DISPLAY_LOGO_AND_LABEL = '1';
    public const DISPLAY_LABEL = '2';
    
    /**
     * Options Array
     *
     * @return void
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
