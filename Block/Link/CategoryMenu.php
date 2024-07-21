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

 namespace Mavenbird\Shopbybrand\Block\Link;

use Magento\Framework\Exception\NoSuchEntityException;
use Mavenbird\Shopbybrand\Block\Brand;

class CategoryMenu extends Brand
{
    /**
     * Get Brand
     *
     * @return void
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
     * Enable Dropdown Menu
     *
     * @return void
     */
    public function enableDropdownMenu()
    {
        return $this->helper->getModuleConfig('general/show_dropdown');
    }

    /**
     * Brand Title
     *
     * @return void
     */
    public function getBrandTitle()
    {
        return $this->helper->getBrandTitle();
    }

    /**
     * Brand Url
     *
     * @param [type] $brand
     * @return void
     */
    public function getBrandUrl($brand = null)
    {
        return $this->helper->getBrandUrl($brand);
    }

    /**
     * Brand Image Url
     *
     * @param [type] $brand
     * @return void
     */
    public function getBrandImageUrl($brand)
    {
        return $this->helper->getBrandImageUrl($brand);
    }

    /**
     * Limit
     *
     * @return void
     */
    public function getLimit()
    {
        return (int) $this->helper->getConfigGeneral('limit_brands');
    }
}
