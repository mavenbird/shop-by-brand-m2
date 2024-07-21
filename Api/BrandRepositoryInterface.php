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

namespace Mavenbird\Shopbybrand\Api;

interface BrandRepositoryInterface
{
    /**
     * Get brand list
     *
     * @return \Mavenbird\Shopbybrand\Api\Data\BrandInterface[]
     */
    public function getBrandList();

    /**
     * Get brand feature list
     *
     * @return \Mavenbird\Shopbybrand\Api\Data\BrandInterface[]
     */
    public function getFeatureBrand();

    /**
     * Brand By Name
     *
     * @param [type] $name
     * @return void
     */
    public function getBrandByName($name);

    /**
     * Product List
     *
     * @param [type] $optionId
     * @return void
     */
    public function getProductList($optionId);

    /**
     * Brand By Sku
     *
     * @param [type] $sku
     * @param [type] $storeId
     * @return void
     */
    public function getBrandBySku($sku, $storeId = null);

    /**
     * Set Product
     *
     * @param [type] $optionId
     * @param [type] $sku
     * @param [type] $storeId
     * @return void
     */
    public function setProduct($optionId, $sku, $storeId = null);

    /**
     * Delete Product
     *
     * @param [type] $sku
     * @return void
     */
    public function deleteProduct($sku);

    /**
     * Add
     *
     * @param [type] $option
     * @return void
     */
    public function add($option);

    /**
     * Update
     *
     * @param [type] $optionId
     * @param [type] $option
     * @return void
     */
    public function update($optionId, $option);

    /**
     * Delete
     *
     * @param [type] $optionId
     * @return void
     */
    public function delete($optionId);

    /**
     * Category
     *
     * @return void
     */
    public function getCategory();

    /**
     * Category By Id
     *
     * @param [type] $categoryId
     * @return void
     */
    public function getCategoryById($categoryId);

    /**
     * Add Category
     *
     * @param [type] $category
     * @return void
     */
    public function addCategory($category);

    /**
     * Update Category
     *
     * @param [type] $categoryId
     * @param [type] $category
     * @return void
     */
    public function updateCategory($categoryId, $category);

    /**
     * Delete Category
     *
     * @param [type] $categoryId
     * @return void
     */
    public function deleteCategory($categoryId);
}
