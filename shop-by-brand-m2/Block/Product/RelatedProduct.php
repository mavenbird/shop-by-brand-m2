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
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Mavenbird\Shopbybrand\Helper\Data as BrandHelper;

class RelatedProduct extends ListProduct
{
    /**
     * Data
     *
     * @var [type]
     */
    protected $_helper;

    /**
     * Factory for Collection Product
     *
     * @var [type]
     */
    protected $_productCollectionFactory;

    /**
     * Visible Product
     *
     * @var [type]
     */
    protected $visibleProducts;

    /**
     * Repository of Product
     *
     * @var [type]
     */
    protected $productRepository;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param UrlHelper $urlHelper
     * @param ProductRepositoryInterface $productRepository
     * @param BrandHelper $helper
     * @param Visibility $visibleProducts
     * @param ProductCollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        UrlHelper $urlHelper,
        ProductRepositoryInterface $productRepository,
        BrandHelper $helper,
        Visibility $visibleProducts,
        ProductCollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->productRepository         = $productRepository;
        $this->_helper                   = $helper;
        $this->_productCollectionFactory = $collectionFactory;
        $this->visibleProducts           = $visibleProducts;

        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }

    /**
     * Product Collection
     *
     * @return void
     */
    protected function _getProductCollection()
    {
        if (($this->_request->getFullActionName() === 'mmbrand_index_view')
            && $this->getRequest()->isAjax()
            && $this->getRequest()->getParam('title')
        ) {
            $title   = $this->getRequest()->getParam('title');
            $product = $this->productRepository->get($title);
            if (($product instanceof Product) && $product->getId()) {
                $attCode    = $this->_helper->getAttributeCode();
                $optionId   = $product->getData($attCode);
                $collection = $this->_productCollectionFactory->create()
                    ->setVisibility($this->visibleProducts->getVisibleInCatalogIds())
                    ->addAttributeToSelect('*')->addAttributeToFilter($attCode, ['eq' => $optionId])
                    ->addFieldToFilter('entity_id', ['neq' => $product->getId()]);

                return $collection;
            }

            return null;
        }

        return parent::_getProductCollection();
    }
}
