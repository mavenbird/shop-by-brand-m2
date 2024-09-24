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

class BrandList extends Brand
{
    /**
     * Options Ids
     *
     * @var array
     */
    protected $optionIds = [];

    /**
     * Brand Collections
     *
     * @var [type]
     */
    protected $brandCollection;

    /**
     * Collection By Char
     *
     * @param [type] $char
     * @return void
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

    public function getBrandDescription($brand, $short = false)
    {
        if ($short) {
            return $brand->getShortDescription(); // Assuming this method exists for fetching short description
        }
        return $brand->getDescription(); // For full description
    }

    /**
     * Cat Filter Class
     *
     * @param [type] $optionId
     * @return void
     */
    public function getCatFilterClass($optionId)
    {
        return $this->helper->getCatFilterClass($optionId);
    }

    /**
     * Cat Name Filter
     *
     * @param [type] $catName
     * @return void
     */
    public function getCatNameFilter($catName)
    {
        return str_replace([' ', '*', '/', '\\'], '_', $catName);
    }

    /**
     * Option Id By Char
     *
     * @param [type] $char
     * @return void
     */
    public function getOptionIdByChar($char)
    {
        return $this->optionIds[$char];
    }

    /**
     * Option Ids To Filter
     *
     * @param [type] $collection
     * @return void
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
