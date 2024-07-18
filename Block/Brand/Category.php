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

namespace Mavenbird\Shopbybrand\Block\Brand;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection;
use Mavenbird\Shopbybrand\Block\Brand;
use Mavenbird\Shopbybrand\Helper\Data;

/**
 * Class Category
 * @package Mavenbird\Shopbybrand\Block\Brand
 */
class Category extends Brand
{
    /**
     * @var Collection
     */
    protected $brandCategoryCollection;

    /**
     * @param $char
     *
     * @return Collection|mixed
     */
    public function getCollectionByChar($char)
    {
        if (!$this->brandCategoryCollection) {
            $this->brandCategoryCollection = $this->getCollection();
        }

        $collection = clone $this->brandCategoryCollection;
        $sqlString  = $this->helper->checkCharacter($char);
        $collection->getSelect()->where($sqlString);

        return $collection;
    }

    /**
     * @inheritdoc
     */
    public function getCollection($type = null, $option = null)
    {
        return parent::getCollection(Data::CATEGORY, $this->getOptionIds());
    }
}
