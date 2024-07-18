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
 * Class BrandList
 * @package Mavenbird\Shopbybrand\Block\Brand
 */
class BrandList extends Brand
{
    protected $optionIds = [];

    /**
     * @var Collection
     */
    protected $brandCollection;

    /**
     * Get Brand List by First Character
     *
     * @param $char
     *
     * @return Collection|mixed
     */
    public function getCollectionByChar($char)
    {
        if (!$this->brandCollection) {
            $this->brandCollection = $this->getCollection(Data::BRAND_FIRST_CHAR);
        }
        $collection = clone $this->brandCollection;
        $sqlString  = $this->helper->checkCharacter($char);
        $collection->getSelect()->where($sqlString);
        $this->optionIds[$char] = $this->getOptionIdsToFilter($collection);

        return $collection;
    }

    /**
     * Get Category Filter Class for Mixitup
     *
     * @param $optionId
     *
     * @return string
     */
    public function getCatFilterClass($optionId)
    {
        return $this->helper->getCatFilterClass($optionId);
    }

    /**
     * @param $catName
     *
     * @return mixed
     */
    public function getCatNameFilter($catName)
    {
        return str_replace([' ', '*', '/', '\\'], '_', $catName);
    }

    /**
     * @param string $char
     *
     * @return mixed
     */
    public function getOptionIdByChar($char)
    {
        return $this->optionIds[$char];
    }

    /**
     * @param Collection $collection
     *
     * @return string
     */
    public function getOptionIdsToFilter($collection)
    {
        $optionIds = [];

        foreach ($collection as $brand) {
            $optionIds [] = $brand->getId();
        }
        $result = implode(',', $optionIds);
        unset($optionIds);

        return $result;
    }
}
