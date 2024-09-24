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

use Magento\Eav\Api\Data\AttributeOptionLabelInterface;
use Magento\Eav\Model\Entity\Attribute as EavAttribute;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Mavenbird\Shopbybrand\Api\Data\BrandInterface;
use Mavenbird\Shopbybrand\Helper\Data as Helper;
use Zend_Db_Expr;

class Brand extends AbstractModel implements BrandInterface
{
    public const CACHE_TAG = 'mavenbird_shopbybrand_brand';

    /**
     * Cache tags
     *
     * @var string
     */
    protected $_cacheTag = 'mavenbird_shopbybrand_brand';

    /**
     * Events prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'mavenbird_shopbybrand_brand';

    /**
     * Stores manager
     *
     * @var [type]
     */
    protected $_storeManager;

    /**
     * Data
     *
     * @var [type]
     */
    protected $helper;

    /**
     * Factory for attribute option collection
     *
     * @var [type]
     */
    protected $_attrOptionCollectionFactory;

    /**
     * Core Registry
     *
     * @var [type]
     */
    protected $registry;

    /**
     * Eav Attributes
     *
     * @var [type]
     */
    protected $eavAttribute;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $registry
     * @param EavAttribute $eavAttribute
     * @param Helper $helper
     * @param CollectionFactory $attrOptionCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        EavAttribute $eavAttribute,
        Helper $helper,
        CollectionFactory $attrOptionCollectionFactory,
        StoreManagerInterface $storeManager,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->eavAttribute                 = $eavAttribute;
        $this->helper                       = $helper;
        $this->_storeManager                = $storeManager;
        $this->_attrOptionCollectionFactory = $attrOptionCollectionFactory;
        $this->registry                     = $registry;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Brand::class);
    }

    /**
     * Identities
     *
     * @return void
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Brand Collection
     *
     * @param [type] $storeId
     * @param array $conditions
     * @param [type] $sqlString
     * @return void
     */
    public function getBrandCollection($storeId = null, $conditions = [], $sqlString = null)
    {
        $storeId = ($storeId === null) ? $this->helper->getStoreId() : $storeId;

        $attributeId = $this->eavAttribute->getIdByCode('catalog_product', $this->helper->getAttributeCode($storeId));
        $collection  = $this->_attrOptionCollectionFactory->create()
            ->setPositionOrder('asc')
            ->setAttributeFilter($attributeId)
            ->setStoreFilter();

        $connection       = $collection->getConnection();
        $storeIdCondition = 0;
        if ($storeId) {
            $storeIdCondition = $connection->select()
                ->from(['ab' => $collection->getTable('mavenbird_brand')], 'MAX(ab.store_id)')
                ->where('ab.option_id = br.option_id AND ab.store_id IN (0, ' . $storeId . ')');
        }

        $collection->getSelect()
            ->joinLeft(
                ['br' => $collection->getTable('mavenbird_brand')],
                'main_table.option_id = br.option_id 
                AND br.store_id = (' . $storeIdCondition . ')' . (is_string($conditions) ? $conditions : ''),
                [
                    'brand_id' => new Zend_Db_Expr($connection->getCheckSql(
                        'br.store_id = ' . $storeId,
                        'br.brand_id',
                        'NULL'
                    )),
                    'brand_id',
                    'store_id' => new Zend_Db_Expr($storeId),
                    'page_title',
                    'url_key',
                    'short_description',
                    'description',
                    'is_featured',
                    'static_block',
                    'meta_title',
                    'meta_keywords',
                    'meta_description',
                    'image'
                ]
            )
            ->joinLeft(
                ['sw' => $collection->getTable('eav_attribute_option_swatch')],
                'main_table.option_id = sw.option_id',
                ['swatch_type' => 'type', 'swatch_value' => 'value']
            )
            ->group('main_table.option_id')->order('tdv.value');

        if (is_array($conditions)) {
            foreach ($conditions as $field => $condition) {
                $collection->addFieldToFilter($field, $condition);
            }
        }
        if ($sqlString) {
            $collection->getSelect()->where($sqlString);
        }

        return $collection;
    }

    /**
     * Load By Option
     *
     * @param [type] $optionId
     * @param [type] $store
     * @return void
     */
    public function loadByOption($optionId, $store = null)
    {
        $collection = $this->getBrandCollection($store, ['main_table.option_id' => $optionId]);

        return $collection->setPageSize(1)->setCurPage(1)->getFirstItem();
    }

    /**
     * Option Id
     *
     * @return void
     */
    public function getOptionId()
    {
        return $this->_getData(self::OPTION_ID);
    }

    /**
     * Page Title
     *
     * @return void
     */
    public function getPageTitle()
    {
        return $this->_getData(self::PAGE_TITLE);
    }

    /**
     * Url Key
     *
     * @return void
     */
    public function getUrlKey()
    {
        return $this->_getData(self::URL_KEY);
    }

    /**
     * Image
     *
     * @return void
     */
    public function getImage()
    {
        return $this->_getData(self::IMAGE);
    }

    /**
     * Short Description
     *
     * @return void
     */
    public function getShortDescription()
    {
        return $this->_getData(self::SHORT_DESCRIPTION);
    }

    /**
     * Description
     *
     * @return void
     */
    public function getDescription()
    {
        return $this->_getData(self::DESCRIPTION);
    }

    /**
     * Is Featured
     *
     * @return void
     */
    public function getIsFeatured()
    {
        return $this->_getData(self::IS_FEATURED);
    }

    /**
     * Static Block
     *
     * @return void
     */
    public function getStaticBlock()
    {
        return $this->_getData(self::STATIC_BLOCK);
    }

    /**
     * Meta Title
     *
     * @return void
     */
    public function getMetaTitle()
    {
        return $this->_getData(self::META_TITLE);
    }

    /**
     * Meta Keywords
     *
     * @return void
     */
    public function getMetaKeywords()
    {
        return $this->_getData(self::META_KEYWORDS);
    }

    /**
     * Meta Description
     *
     * @return void
     */
    public function getMetaDescription()
    {
        return $this->_getData(self::META_DESCRIPTION);
    }

    /**
     * Set Option Id
     *
     * @param [type] $id
     * @return void
     */
    public function setOptionId($id)
    {
        return $this->setData(self::OPTION_ID, $id);
    }

    /**
     * Set Page Title
     *
     * @param [type] $title
     * @return void
     */
    public function setPageTitle($title)
    {
        return $this->setData(self::PAGE_TITLE, $title);
    }

    /**
     * Set Image
     *
     * @param [type] $image
     * @return void
     */
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * Set Description
     *
     * @param [type] $value
     * @return void
     */
    public function setDescription($value)
    {
        return $this->setData(self::DESCRIPTION, $value);
    }

    /**
     * Set Short Description
     *
     * @param [type] $value
     * @return void
     */
    public function setShortDescription($value)
    {
        return $this->setData(self::SHORT_DESCRIPTION, $value);
    }

    /**
     * Set Is Featured
     *
     * @param [type] $value
     * @return void
     */
    public function setIsFeatured($value)
    {
        return $this->setData(self::IS_FEATURED, $value);
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
     * Set Static Block
     *
     * @param [type] $value
     * @return void
     */
    public function setStaticBlock($value)
    {
        return $this->setData(self::STATIC_BLOCK, $value);
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
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getData(self::LABEL);
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->getData(self::VALUE);
    }

    /**
     * @inheritdoc
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * @inheritdoc
     */
    public function getIsDefault()
    {
        return $this->getData(self::IS_DEFAULT);
    }

    /**
     * @inheritdoc
     */
    public function getStoreLabels()
    {
        return $this->getData(self::STORE_LABELS);
    }

    /**
     * Set option label
     *
     * @param [type] $label
     * @return void
     */
    public function setLabel($label)
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * Set option value
     *
     * @param [type] $value
     * @return void
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * Set sort order
     *
     * @param [type] $sortOrder
     * @return void
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * Set is default
     *
     * @param [type] $isDefault
     * @return void
     */
    public function setIsDefault($isDefault)
    {
        return $this->setData(self::IS_DEFAULT, $isDefault);
    }

    /**
     * Set store labels
     *
     * @param array|null $storeLabels
     * @return void
     */
    public function setStoreLabels(array $storeLabels = null)
    {
        return $this->setData(self::STORE_LABELS, $storeLabels);
    }
}
