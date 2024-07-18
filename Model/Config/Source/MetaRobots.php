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
 * Class MetaRobots
 * @package Mavenbird\Shopbybrand\Model\Config\Source
 */
class MetaRobots implements OptionSourceInterface
{
    const INDEXFOLLOW     = 'INDEX,FOLLOW';
    const NOINDEXNOFOLLOW = 'NOINDEX,NOFOLLOW';
    const NOINDEXFOLLOW   = 'NOINDEX,FOLLOW';
    const INDEXNOFOLLOW   = 'INDEX,NOFOLLOW';

    /**
     * to option array
     *
     * @return array
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
     * @return array
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
