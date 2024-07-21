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

class ShowBrandInfo implements OptionSourceInterface
{
    public const BRAND_NAME = 'name';
    public const BRAND_LOGO = 'logo';
    public const NOT_SHOW   = 'not-show';

    /**
     * Options Array
     *
     * @return void
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
