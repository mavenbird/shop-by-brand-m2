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

namespace Mavenbird\Shopbybrand\Model;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Mavenbird\Shopbybrand\Api\Data\BrandCategoryInterface;
use Mavenbird\Shopbybrand\Model\ResourceModel\Category\CollectionFactory;

class Category extends AbstractModel implements BrandCategoryInterface
{
    /**
     * Factory for Collection Category
     *
     * @var [type]
     */
    protected $categoryCollectionFactory;

    /**
     * Brand Category Table
     *
     * @var [type]
     */
    protected $tableBrandCategory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $registry
     * @param ResourceConnection $resourceConnection
     * @param CollectionFactory $categoryCollectionFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ResourceConnection $resourceConnection,
        CollectionFactory $categoryCollectionFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->tableBrandCategory = $resourceConnection->getTableName('mavenbird_shopbybrand_brand_category');
    }

    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(ResourceModel\Category::class);
    }

    /**
     * Enable
     *
     * @return boolean
     */
    public function isEnable()
    {
        return $this->getData('enable');
    }

    /**
     * Category Collection
     *
     * @param [type] $whereCond
     * @param [type] $groupCond
     * @return void
     */
    public function getCategoryCollection($whereCond = null, $groupCond = null)
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->getSelect()->joinInner(
            ['brand_cat_tbl' => $this->tableBrandCategory],
            'main_table.cat_id = brand_cat_tbl.cat_id'
        );
        if ($whereCond !== null) {
            $collection->getSelect()->where($whereCond);
        }
        if ($groupCond !== null) {
            $collection->getSelect()->group($groupCond);
        }

        return $collection;
    }

    /**
     * Store Ids
     *
     * @return void
     */
    public function getStoreIds()
    {
        return $this->getData(self::STORE_IDS);
    }

    /**
     * Set Store Ids
     *
     * @param [type] $store
     * @return void
     */
    public function setStoreIds($store)
    {
        return $this->setData(self::STORE_IDS, $store);
    }

    /**
     * Get Name
     *
     * @return void
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set Name
     *
     * @param [type] $name
     * @return void
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get Url Key
     *
     * @return void
     */
    public function getUrlKey()
    {
        return $this->getData(self::URL_KEY);
    }

    /**
     * Set Url Key
     *
     * @param [type] $url
     * @return void
     */
    public function setUrlKey($url)
    {
        return $this->setData(self::URL_KEY, $url);
    }

    /**
     * Get Status
     *
     * @return void
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set Status
     *
     * @param [type] $status
     * @return void
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get Meta Title
     *
     * @return void
     */
    public function getMetaTitle()
    {
        return $this->getData(self::META_TITLE);
    }

    /**
     * Set Meta Title
     *
     * @param [type] $value
     * @return void
     */
    public function setMetaTitle($value)
    {
        return $this->setData(self::META_TITLE, $value);
    }

    /**
     * Get Meta Keywords
     *
     * @return void
     */
    public function getMetaKeywords()
    {
        return $this->getData(self::META_KEYWORDS);
    }

    /**
     * Get Meta Description
     *
     * @return void
     */
    public function getMetaDescription()
    {
        return $this->getData(self::META_DESCRIPTION);
    }

    /**
     * Set Meta Description
     *
     * @param [type] $value
     * @return void
     */
    public function setMetaDescription($value)
    {
        return $this->setData(self::META_DESCRIPTION, $value);
    }

    /**
     * Set Meta Keywords
     *
     * @param [type] $value
     * @return void
     */
    public function setMetaKeywords($value)
    {
        return $this->setData(self::META_KEYWORDS, $value);
    }

    /**
     * Get Meta Robots
     *
     * @return void
     */
    public function getMetaRobots()
    {
        return $this->getData(self::META_ROBOTS);
    }

    /**
     * Set Meta Robots
     *
     * @param [type] $value
     * @return void
     */
    public function setMetaRobots($value)
    {
        return $this->setData(self::META_ROBOTS, $value);
    }

    /**
     * Get Created At
     *
     * @return void
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Get Updated At
     *
     * @return void
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set Created At
     *
     * @param [type] $createdAt
     * @return void
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Set Updated At
     *
     * @param [type] $updatedAt
     * @return void
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
