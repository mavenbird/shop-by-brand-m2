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

namespace Mavenbird\Shopbybrand\Block\Link;

use Magento\Framework\Exception\NoSuchEntityException;
use Mavenbird\Shopbybrand\Block\Brand;

/**
 * Class CategoryMenu
 *
 * @package Mavenbird\Shopbybrand\Block\Link
 */
class CategoryMenu extends Brand
{

    /**
     * @return array
     */
    public function getBrands()
    {
        $brands = $this->getCollection();
        $limit  = $this->getLimit();
        $result = [];
        $i      = 0;
        $count  = 0;
        foreach ($brands as $brand) {
            $count++;
            $result[$i][] = $brand;
            if ($count === $limit) {
                $count = 0;
                $i++;
            }
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function enableDropdownMenu()
    {
        return $this->helper->getModuleConfig('general/show_dropdown');
    }

    /**
     * @return string
     */
    public function getBrandTitle()
    {
        return $this->helper->getBrandTitle();
    }

    /**
     * @param \Mavenbird\Shopbybrand\Model\Brand $brand
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getBrandUrl($brand = null)
    {
        return $this->helper->getBrandUrl($brand);
    }

    /**
     * @param \Mavenbird\Shopbybrand\Model\Brand $brand
     *
     * @return string
     */
    public function getBrandImageUrl($brand)
    {
        return $this->helper->getBrandImageUrl($brand);
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return (int) $this->helper->getConfigGeneral('limit_brands');
    }
}
