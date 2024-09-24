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

class MetaRobots implements OptionSourceInterface
{
    public const INDEXFOLLOW     = 'INDEX,FOLLOW';
    public const NOINDEXNOFOLLOW = 'NOINDEX,NOFOLLOW';
    public const NOINDEXFOLLOW   = 'NOINDEX,FOLLOW';
    public const INDEXNOFOLLOW   = 'INDEX,NOFOLLOW';

    /**
     * Options Array
     *
     * @return void
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => self::INDEXFOLLOW,
                'label' => __('INDEX,FOLLOW')
            ],
            [
                'value' => self::NOINDEXNOFOLLOW,
                'label' => __('NOINDEX,NOFOLLOW')
            ],
            [
                'value' => self::NOINDEXFOLLOW,
                'label' => __('NOINDEX,FOLLOW')
            ],
            [
                'value' => self::INDEXNOFOLLOW,
                'label' => __('INDEX,NOFOLLOW')
            ]
        ];

        return $options;
    }

    /**
     * Get Options Array
     *
     * @return void
     */
    public function getOptionArray()
    {
        return [
            self::INDEXFOLLOW     => 'INDEX,FOLLOW',
            self::NOINDEXNOFOLLOW => 'NOINDEX,NOFOLLOW',
            self::NOINDEXFOLLOW   => 'NOINDEX,FOLLOW',
            self::INDEXNOFOLLOW   => 'INDEX,NOFOLLOW'
        ];
    }
}
