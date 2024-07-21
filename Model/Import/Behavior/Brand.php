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

namespace Mavenbird\Shopbybrand\Model\Import\Behavior;

use Magento\ImportExport\Model\Import;
use Magento\ImportExport\Model\Source\Import\AbstractBehavior;

class Brand extends AbstractBehavior
{
    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            Import::BEHAVIOR_APPEND => __('Add/Update'),
            Import::BEHAVIOR_DELETE => __('Delete')
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCode()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function getNotes($entityCode)
    {
        $messages = [
            'mavenbird_brand' => [
                Import::BEHAVIOR_APPEND => __('Note: Please select the brand attribute in Mavenbird_Shopbybrand configuration first.'),
                Import::BEHAVIOR_DELETE => __('Note: Please select the brand attribute in Mavenbird_Shopbybrand configuration first.'),
            ]
        ];

        return isset($messages[$entityCode]) ? $messages[$entityCode] : [];
    }
}
