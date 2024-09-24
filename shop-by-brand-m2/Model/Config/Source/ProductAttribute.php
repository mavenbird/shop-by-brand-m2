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

use Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class ProductAttribute implements OptionSourceInterface
{
    /**
     * Factory for Collection
     *
     * @var [type]
     */
    protected $_collectionFactory;

    /**
     * Construct
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * Options Array
     *
     * @return void
     */
    public function toOptionArray()
    {
        /** @var Collection $attributes */
        $attributes   = $this->_collectionFactory->create()->addVisibleFilter();
        $arrAttribute = [
            [
                'label' => __('-- Please select --'),
                'value' => '',
            ],
        ];

        foreach ($attributes as $attribute) {
            if ($attribute->getIsUserDefined()
                && in_array(
                    $attribute->getData('frontend_input'),
                    ['select', 'swatch_visual', 'swatch_text'],
                    true
                )
            ) {
                $arrAttribute[] = [
                    'label' => $attribute->getFrontendLabel(),
                    'value' => $attribute->getAttributeCode()
                ];
            }
        }

        return $arrAttribute;
    }
}
