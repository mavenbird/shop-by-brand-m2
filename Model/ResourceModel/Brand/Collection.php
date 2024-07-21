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

namespace Mavenbird\Shopbybrand\Model\ResourceModel\Brand;

use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mavenbird\Shopbybrand\Model\ResourceModel\Brand;

class Collection extends AbstractCollection
{
    /**
     * ID Fields Name
     *
     * @var string
     */
    protected $_idFieldName = 'brand_id';

    /**
     * Events prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'mavenbird_shopbybrand_brand_collection';

    /**
     * Events object
     *
     * @var string
     */
    protected $_eventObject = 'brand_collection';

    /**
     * Construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Mavenbird\Shopbybrand\Model\Brand::class, Brand::class);
    }

    /**
     * Selecet Count SQL
     *
     * @return void
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Select::GROUP);

        return $countSelect;
    }

    /**
     * Option Array
     *
     * @param [type] $valueField
     * @param string $labelField
     * @param array $additional
     * @return void
     */
    protected function _toOptionArray($valueField = null, $labelField = 'name', $additional = [])
    {
        return parent::_toOptionArray('brand_id', $labelField, $additional);
    }
}
