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

use Magento\Framework\Exception\NoSuchEntityException;
use Mavenbird\Shopbybrand\Block\Brand;
use Mavenbird\Shopbybrand\Helper\Data;

class Search extends Brand
{
    /**
     * Search Data
     *
     * @return void
     */
    public function getSearchData()
    {
        $searchData = [];
        foreach ($this->getCollection() as $brand) {
            $searchData[] = [
                'value'    => $brand->getValue(),
                'desc'     => $this->helper->getBrandDescription($brand, true),
                'image'    => $brand->getImage()
                    ? $this->getBrandThumbnail($brand)
                    : $this->helper->getBrandImageUrl($brand),
                'brandUrl' => $this->helper->getBrandUrl($brand)
            ];
        }

        return Data::jsonEncode($searchData);
    }

    /**
     * Max Query Result
     *
     * @return void
     */
    public function getMaxQueryResult()
    {
        return $this->helper->getSearchConfig('max_query_results') ?: 10;
    }

    /**
     * Min Search Char
     *
     * @return void
     */
    public function getMinSearchChar()
    {
        return $this->helper->getSearchConfig('min_search_chars') ?: 1;
    }

    /**
     * Visible Image
     *
     * @return boolean
     */
    public function isVisibleImage()
    {
        return $this->helper->getSearchConfig('visible_images');
    }
}
