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

namespace Mavenbird\Shopbybrand\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Mavenbird\Shopbybrand\Controller\Adminhtml\Category\Save;
use Mavenbird\Shopbybrand\Helper\Data;

class Category extends AbstractDb
{
    /**
     * Hepler
     *
     * @var [type]
     */
    protected $helperData;

    /**
     * Dates
     *
     * @var [type]
     */
    protected $date;

    /**
     * Forms Data
     *
     * @var [type]
     */
    protected $formData;

    /**
     * Constructor
     *
     * @param Data $helperData
     * @param DateTime $date
     * @param Save $formData
     * @param Context $context
     */
    public function __construct(
        Data $helperData,
        DateTime $date,
        Save $formData,
        Context $context
    ) {
        $this->helperData = $helperData;
        $this->date       = $date;
        $this->formData   = $formData;

        parent::__construct($context);
    }

    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('mavenbird_shopbybrand_category', 'cat_id');
    }

    /**
     * Before Save
     *
     * @param AbstractModel $object
     * @return void
     */
    protected function _beforeSave(AbstractModel $object)
    {
        parent::_beforeSave($object);

        if (is_array($object->getStoreIds())) {
            $object->setStoreIds(implode(',', $object->getStoreIds()));
        }
        $object->setUpdatedAt($this->date->date());
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->date->date());
        }

        //Check Url Key
        if ($object->isObjectNew()) {
            $count   = 0;
            $objName = $object->getName();
            if ($object->getUrlKey()) {
                $urlKey = $object->getUrlKey();
            } else {
                $urlKey = $this->generateUrlKey($objName, $count);
            }
            while ($this->checkUrlKey($urlKey)) {
                $count++;
                $urlKey = $this->generateUrlKey($urlKey, $count);
            }
            $object->setUrlKey($urlKey);
        } else {
            $objectId = $object->getId();
            $count    = 0;
            $objName  = $object->getName();
            if ($object->getUrlKey()) {
                $urlKey = $object->getUrlKey();
            } else {
                $urlKey = $this->generateUrlKey($objName, $count);
            }
            while ($this->checkUrlKey($urlKey, $objectId)) {
                $count++;
                $urlKey = $this->generateUrlKey($urlKey, $count);
            }

            $object->setUrlKey($urlKey);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function _afterSave(AbstractModel $object)
    {
        $oldOptionIds       = [];
        $brandCategoryTable = $this->getTable('mavenbird_shopbybrand_brand_category');
        $adapter            = $this->getConnection();
        $data               = $this->formData->getData();
        $id                 = $object->getCatId();
        $selectOldRecord    = $this->getConnection()->select()->from($brandCategoryTable)->where('cat_id = :cat_id');
        $bindOldRecord      = ['cat_id' => (int) $id];
        $oldRecord          = $this->getConnection()->fetchAll($selectOldRecord, $bindOldRecord);
        foreach ($oldRecord as $item) {
            $oldOptionIds[] = $item['option_id'];
        }
        if (!empty($data['brand_category'])) {
            $optionIds = $data['brand_category'];
            if (empty($oldRecord)) {
                $insert = [];
                foreach ($optionIds as $optionId) {
                    $insert[] = [
                        'cat_id'    => $id,
                        'option_id' => $optionId
                    ];
                }
                $adapter->insertMultiple($brandCategoryTable, $insert);
            }
            if (!empty($oldRecord)) {
                $updateOptionIds = array_diff($optionIds, $oldOptionIds);
                $deleteOptionIds = array_diff($oldOptionIds, $optionIds);
                if (!empty($updateOptionIds)) {
                    $update = [];
                    foreach ($updateOptionIds as $optionId) {
                        $update[] = [
                            'cat_id'    => $id,
                            'option_id' => $optionId
                        ];
                    }
                    $adapter->insertMultiple($brandCategoryTable, $update);
                }
                if (!empty($deleteOptionIds)) {
                    $condition = ['option_id IN(?)' => $deleteOptionIds, 'cat_id=?' => $id];
                    $adapter->delete($brandCategoryTable, $condition);
                }
            }
        } elseif (!empty($oldRecord) && !isset($data['brand_category'])) {
            $condition = ['option_id IN(?)' => $oldOptionIds, 'cat_id=?' => $id];
            $adapter->delete($brandCategoryTable, $condition);
        }

        return parent::_afterSave($object);
    }

    /**
     * Generate Url Key
     *
     * @param [type] $name
     * @param [type] $count
     * @return void
     */
    public function generateUrlKey($name, $count)
    {
        return $this->helperData->generateUrlKey($name, $count);
    }

    /**
     * Check Url Key
     *
     * @param [type] $url
     * @param [type] $id
     * @return void
     */
    public function checkUrlKey($url, $id = null)
    {
        $adapter = $this->getConnection();
        if ($id) {
            $select = $adapter->select()
                ->from($this->getMainTable())
                ->where('url_key = :url_key')
                ->where('cat_id != :cat_id');
            $binds  = [
                'url_key' => $url,
                'cat_id'  => (int) $id
            ];
        } else {
            $select = $adapter->select()
                ->from($this->getMainTable())
                ->where('url_key = :url_key');
            $binds  = ['url_key' => (string) $url];
        }

        return $adapter->fetchOne($select, $binds);
    }
}
