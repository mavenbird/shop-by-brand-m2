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

namespace Mavenbird\Shopbybrand\Model\ResourceModel\Category;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mavenbird\Shopbybrand\Model\ResourceModel\Category;

class Collection extends AbstractCollection
{
    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Mavenbird\Shopbybrand\Model\Category::class, Category::class);
    }

    /**
     * Add Store Filter
     *
     * @param array $storeIds
     * @param boolean $withDefaultStore
     * @return void
     */
    public function addStoreFilter($storeIds = [], $withDefaultStore = true)
    {
        if (!is_array($storeIds)) {
            $storeIds = [$storeIds];
        }
        if ($withDefaultStore && !in_array('0', $storeIds, false)) {
            array_unshift($storeIds, 0);
        }
        $where = [];
        foreach ($storeIds as $storeId) {
            $where[] = $this->_getConditionSql('store_ids', ['finset' => $storeId]);
        }

        $this->_select->where(implode(' OR ', $where));

        return $this;
    }

    /**
     * Add Visible Filter
     *
     * @return void
     */
    public function addVisibleFilter()
    {
        $this->addFieldToFilter('status', 1);

        return $this;
    }
}
