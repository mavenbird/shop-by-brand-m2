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

namespace Mavenbird\Shopbybrand\Block\Product;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;
use Mavenbird\Shopbybrand\Helper\Data as Helper;

class TabProduct extends ListProduct
{
    public const TITLE = 'Products from the same brand';
    public const LIMIT = 5;

    /**
     * Data
     *
     * @var [type]
     */
    protected $_helper;

    /**
     * Visible Product
     *
     * @var [type]
     */
    protected $visibleProducts;

    /**
     * Factory for Product Collection
     *
     * @var [type]
     */
    protected $_productCollectionFactory;

    /**
     * Brand Products Collections
     *
     * @var [type]
     */
    protected $brandProductCollection;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Data $urlHelper
     * @param CollectionFactory $productCollectionFactory
     * @param Helper $helper
     * @param Visibility $visibleProducts
     * @param array $data
     */
    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        CollectionFactory $productCollectionFactory,
        Helper $helper,
        Visibility $visibleProducts,
        array $data = []
    ) {
        $this->_helper                   = $helper;
        $this->visibleProducts           = $visibleProducts;
        $this->_productCollectionFactory = $productCollectionFactory;

        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);

        $this->setTabTitle();
        $this->setData('sort_order', 100);
    }

    /**
     * Tab Title
     *
     * @return void
     */
    public function setTabTitle()
    {
        $collection = $this->_getProductCollection();
        if ($collection) {
            $products = $collection->getPageSize();
            $title = __('More from this Brand (%1)', $products);
        } else {
            $title = __('More from this Brand');
        }
        $this->setTitle($title);
    }
    

    /**
     * Product Collection
     *
     * @return void
     */
    protected function _getProductCollection()
    {
        $product = $this->getProduct();
        if (($product instanceof Product) && $product->getId()) {
            if (!$this->brandProductCollection) {
                $attCode  = $this->_helper->getAttributeCode();
                $optionId = $product->getData($attCode);

                /** @var Collection $collection */
                $collection = $this->_productCollectionFactory->create()
                    ->setVisibility($this->visibleProducts->getVisibleInCatalogIds())
                    ->addAttributeToSelect('*')->addAttributeToFilter($attCode, ['eq' => $optionId])
                    ->addFieldToFilter('entity_id', ['neq' => $product->getId()]);

                $limit = min($collection->getSize(), $this->getLimitProductConfig());

                $collection->setPageSize($limit);

                $this->brandProductCollection = $collection;
            }

            return $this->brandProductCollection;
        }

        return null;
    }

    /**
     * Related Title
     *
     * @return void
     */
    public function getRelatedTitle()
    {
        $title = $this->_helper->getBrandConfig('related_products/title');

        return $title ?: self::TITLE;
    }

    /**
     * Limit Product Config
     *
     * @return void
     */
    public function getLimitProductConfig()
    {
        return (int) $this->_helper->getBrandConfig('related_products/limit_product') ?: self::LIMIT;
    }

    /**
     * Toolbar Html
     *
     * @return void
     */
    public function getToolbarHtml()
    {
        return null;
    }

    /**
     * Additional Html
     *
     * @return void
     */
    public function getAdditionalHtml()
    {
        return null;
    }

    /**
     * Enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->_helper->isEnabled();
    }
}
