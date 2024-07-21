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

use Exception;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Eav\Api\AttributeOptionManagementInterface;
use Magento\Eav\Api\Data\AttributeOptionInterface;
use Magento\Eav\Model\AttributeRepository;
use Magento\Eav\Model\Entity\Attribute as EavAttribute;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as EavCollectionFactory;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Filter\FilterManager;
use Magento\Store\Model\Store;
use Mavenbird\Shopbybrand\Api\BrandRepositoryInterface;
use Mavenbird\Shopbybrand\Api\Data\BrandCategoryInterface;
use Mavenbird\Shopbybrand\Api\Data\BrandInterface;
use Mavenbird\Shopbybrand\Helper\Data as Helper;
use Mavenbird\Shopbybrand\Model\ResourceModel\Brand as BrandResourceModel;
use Mavenbird\Shopbybrand\Model\ResourceModel\Category as BrandCategoryResource;
use Mavenbird\Shopbybrand\Model\ResourceModel\Category\Collection;

class BrandRepository implements BrandRepositoryInterface
{

    /**
     * Factory for attribute option collection
     *
     * @var [type]
     */
    protected $_attrOptionCollectionFactory;

    /**
     * Data
     *
     * @var [type]
     */
    protected $helper;

    /**
     * Repository for product
     *
     * @var [type]
     */
    protected $productRepository;

    /**
     * Option Management
     *
     * @var [type]
     */
    protected $eavOptionManagement;

    /**
     * Factory for Brand
     *
     * @var [type]
     */
    protected $brandFactory;

    /**
     * Model
     *
     * @var [type]
     */
    protected $resourceModel;

    /**
     * Repository for Attribute
     *
     * @var [type]
     */
    protected $attributeRepository;

    /**
     * Eav Model
     *
     * @var [type]
     */
    protected $eavResourceModel;

    /**
     * Factory for Brand Category
     *
     * @var [type]
     */
    protected $categoryFactory;

    /**
     * Filters
     *
     * @var [type]
     */
    protected $filter;

    /**
     * Eav Attributes
     *
     * @var [type]
     */
    protected $eavAttribute;

    /**
     * Factory for Product Collection
     *
     * @var [type]
     */
    protected $productCollectionFactory;

    /**
     * Visible Product
     *
     * @var [type]
     */
    protected $visibleProducts;

    /**
     * Brand Category
     *
     * @var [type]
     */
    protected $brandCategoryResource;

    /**
     *  Constructor
     *
     * @param EavAttribute $eavAttribute
     * @param EavCollectionFactory $attrOptionCollectionFactory
     * @param ProductRepositoryInterface $productRepository
     * @param AttributeOptionManagementInterface $eavOptionManagement
     * @param BrandFactory $brandFactory
     * @param BrandResourceModel $resourceModel
     * @param BrandCategoryResource $brandCategoryResource
     * @param AttributeRepository $attributeRepository
     * @param Attribute $eavResourceModel
     * @param CategoryFactory $categoryFactory
     * @param ProductCollectionFactory $productCollectionFactory
     * @param Visibility $visibleProducts
     * @param FilterManager $filter
     * @param Helper $helper
     */
    public function __construct(
        EavAttribute $eavAttribute,
        EavCollectionFactory $attrOptionCollectionFactory,
        ProductRepositoryInterface $productRepository,
        AttributeOptionManagementInterface $eavOptionManagement,
        BrandFactory $brandFactory,
        BrandResourceModel $resourceModel,
        BrandCategoryResource $brandCategoryResource,
        AttributeRepository $attributeRepository,
        Attribute $eavResourceModel,
        CategoryFactory $categoryFactory,
        ProductCollectionFactory $productCollectionFactory,
        Visibility $visibleProducts,
        FilterManager $filter,
        Helper $helper
    ) {
        $this->eavAttribute                 = $eavAttribute;
        $this->_attrOptionCollectionFactory = $attrOptionCollectionFactory;
        $this->productRepository            = $productRepository;
        $this->eavOptionManagement          = $eavOptionManagement;
        $this->brandFactory                 = $brandFactory;
        $this->resourceModel                = $resourceModel;
        $this->helper                       = $helper;
        $this->attributeRepository          = $attributeRepository;
        $this->eavResourceModel             = $eavResourceModel;
        $this->categoryFactory              = $categoryFactory;
        $this->filter                       = $filter;
        $this->productCollectionFactory     = $productCollectionFactory;
        $this->visibleProducts              = $visibleProducts;
        $this->brandCategoryResource        = $brandCategoryResource;
    }

    /**
     * @inheritdoc
     */
    public function getBrandList()
    {
        $collection = $this->helper->getBrandList();

        return $collection->getItems();
    }

    /**
     * @inheritdoc
     */
    public function getFeatureBrand()
    {
        $collection = $this->helper->getBrandList();
        $collection->addFieldToFilter('is_featured', 1);

        return $collection->getItems();
    }

    /**
     * @inheritDoc
     */
    public function getBrandByName($name)
    {
        $collection = $this->helper->getBrandList();
        $collection->addFieldToFilter('tdv.value', ['like' => $name . '%']);

        return $collection->getItems();
    }

    /**
     * @inheritDoc
     */
    public function getProductList($optionId)
    {
        $attCode    = $this->helper->getAttributeCode();
        $collection = $this->productCollectionFactory->create()
            ->setVisibility($this->visibleProducts->getVisibleInCatalogIds())
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter($attCode, $optionId);

        return $collection->getItems();
    }

    /**
     * @inheritdoc
     */
    public function getBrandBySku($sku, $storeId = null)
    {
        $product  = $this->productRepository->get($sku, false, $storeId);
        $optionId = $product->getData($this->helper->getAttributeCode($storeId));
        $brand    = $this->brandFactory->create();
        $this->resourceModel->load($brand, $optionId, 'option_id');

        return $brand;
    }

    /**
     * @inheritdoc
     */
    public function setProduct($optionId, $sku, $storeId = null)
    {
        $product = $this->productRepository->get($sku, false, $storeId);
        $product->setData($this->helper->getAttributeCode($storeId), $optionId);
        $this->productRepository->save($product);

        return $this->productRepository->get($sku, false, $storeId);
    }

    /**
     * @inheritdoc
     */
    public function deleteProduct($sku)
    {
        $product = $this->productRepository->get($sku);
        $product->setData($this->helper->getAttributeCode(), '');
        $this->productRepository->save($product);

        return true;
    }

    /**
     * Get Items
     *
     * @return void
     */
    public function getItems()
    {
        return $this->eavOptionManagement->getItems(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            $this->helper->getAttributeCode()
        );
    }

    /**
     * Add Option
     *
     * @param [type] $option
     * @return void
     */
    public function addOption($option)
    {
        $attributeCode = $this->helper->getAttributeCode();
        $attribute     = $this->attributeRepository->get(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            $attributeCode
        );
        if (!$attribute->usesSource()) {
            throw new StateException(__('The "%1" attribute doesn\'t work with options.', $attributeCode));
        }

        $optionLabel                    = $option->getLabel();
        $optionId                       = 'id_' . ($option->getValue() ?: 'new_option');
        $options                        = [];
        $options['value'][$optionId][0] = $optionLabel;
        $options['order'][$optionId]    = $option->getSortOrder();

        if (is_array($option->getStoreLabels())) {
            foreach ($option->getStoreLabels() as $label) {
                $options['value'][$optionId][$label->getStoreId()] = $label->getLabel();
            }
        }

        if ($option->getIsDefault()) {
            $attribute->setDefault([$optionId]);
        }

        $attribute->setOption($options);
        try {
            $this->eavResourceModel->save($attribute);
            $attributeSource = $attribute->getSource();
            if (!$attributeSource) {
                return false;
            }
            if ($optionLabel && $attribute->getAttributeCode()) {
                $optionId = $attributeSource->getOptionId($optionLabel);
                if ($optionId) {
                    $option->setValue($attributeSource->getOptionId($optionId));
                } elseif (is_array($option->getStoreLabels())) {
                    foreach ($option->getStoreLabels() as $label) {
                        if ($optionId = $attributeSource->getOptionId($label->getLabel())) {
                            $option->setValue($attributeSource->getOptionId($optionId));
                            break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            throw new StateException(__('The "%1" attribute can\'t be saved.', $attributeCode));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function add($option)
    {
        /** @var BrandInterface[] $currentOptions */
        $currentOptions = $this->getItems();
        if (is_array($currentOptions)) {
            array_walk($currentOptions, function (&$attributeOption) {
                /** @var BrandInterface $attributeOption */
                $attributeOption = $attributeOption->getLabel();
            });
            if (in_array($option->getLabel(), $currentOptions, true)) {
                return false;
            }
        }
        if ($this->addOption($option)) {
            $defaultStore = Store::DEFAULT_STORE_ID;
            $optionId     = $option->getValue();
            $option->setOptionId($option->getValue());
            $option = Helper::jsonDecode(Helper::jsonEncode($option));
            try {
                /** @var Brand $brand */
                $brand = $this->brandFactory->create();
                $this->resourceModel->load($brand, $optionId, 'option_id');
                if (!$brand->getId()) {
                    $brand->addData($option)
                        ->setStoreId($defaultStore);
                    $this->resourceModel->save($brand);
                }
                $brand->addData($option)->setId($brand->getId());
                $this->resourceModel->save($brand);
            } catch (Exception $e) {
                throw new StateException(__('The brand can\'t be saved.'));
            }

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function update($optionId, $option)
    {
        if (empty($optionId)) {
            throw new InputException(__('Invalid option id %1', $optionId));
        }

        try {
            /** @var Brand $brand */
            $brand = $this->brandFactory->create();
            $this->resourceModel->load($brand, $optionId, 'option_id');
            if (!$brand->getId()) {
                throw new NoSuchEntityException(
                    __(
                        'The "%1" brand doesn\'t exist.',
                        $optionId
                    )
                );
            }
            $option = Helper::jsonDecode(Helper::jsonEncode($option));
            $brand->addData($option)->setId($brand->getId());
            $this->resourceModel->save($brand);
        } catch (Exception $e) {
            throw new StateException(__('The brand can\'t be updated.'));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function delete($optionId)
    {
        if (empty($optionId)) {
            throw new InputException(__('Invalid option id %1', $optionId));
        }

        return $this->eavOptionManagement->delete(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            $this->helper->getAttributeCode(),
            $optionId
        );
    }

    /**
     * @inheritdoc
     */
    public function getCategory()
    {
        /** @var Collection $collection */
        $collection = $this->categoryFactory->create()->getCollection();

        return $collection->getItems();
    }

    /**
     * @inheritdoc
     */
    public function getCategoryById($categoryId)
    {
        $categoryModel = $this->categoryFactory->create();
        $this->brandCategoryResource->load($categoryModel, $categoryId);
        if (!$categoryModel->getId()) {
            throw new NoSuchEntityException(
                __("The brand category that was requested doesn't exist. Verify the category and try again.")
            );
        }

        return $categoryModel;
    }

    /**
     * @inheritdoc
     */
    public function addCategory($category)
    {
        $category->setUrlKey($this->filter->translitUrl($category->getUrlKey()));
        $this->validateData($category);
        if ($category->getId()) {
            $categoryModel = $this->getCategoryById($category->getId());
        } else {
            $categoryModel = $this->categoryFactory->create();
        }
        $categoryModel->addData(Helper::jsonDecode(Helper::jsonEncode($category)));

        try {
            $this->brandCategoryResource->save($categoryModel);
        } catch (Exception $e) {
            throw new StateException(__('The brand category can\'t be saved.'));
        }

        return $categoryModel;
    }

    /**
     * @inheritdoc
     */
    public function updateCategory($categoryId, $category)
    {
        if (empty($categoryId)) {
            throw new InputException(__('Invalid option id %1', $categoryId));
        }
        $this->validateData($category);
        $categoryModel = $this->getCategoryById($categoryId);
        $categoryModel->addData(Helper::jsonDecode(Helper::jsonEncode($category)));
        try {
            $this->brandCategoryResource->save($categoryModel);
        } catch (Exception $e) {
            throw new StateException(__('The brand category can\'t be updated.'));
        }

        return $categoryModel;
    }

    /**
     * @inheritdoc
     */
    public function deleteCategory($categoryId)
    {
        if (empty($categoryId)) {
            throw new InputException(__('Invalid option id %1', $categoryId));
        }
        /** @var Category $categoryModel */
        $categoryModel = $this->categoryFactory->create();
        $this->brandCategoryResource->load($categoryModel, $categoryId);
        if (!$categoryModel->getId()) {
            throw new NoSuchEntityException(__('The "%1" brand category doesn\'t exist.', $categoryId));
        }
        try {
            $this->brandCategoryResource->delete($categoryModel);
        } catch (Exception $e) {
            throw new StateException(__('The brand category can\'t be delete.'));
        }

        return true;
    }

    /**
     * Validate data
     *
     * @param [type] $category
     * @return void
     */
    public function validateData($category)
    {
        if (!$category->getName()) {
            throw new InputException(__('Missing category name.'));
        }

        if ($urlKey = $category->getUrlKey()) {
            /** @var Collection $pages */
            $pages = $this->categoryFactory->create()->getCollection()
                ->addFieldToFilter('url_key', $urlKey);
            if ($pages->getSize()) {
                if ($category->getId()) {
                    foreach ($pages as $page) {
                        if ((int) $page->getId() !== $category->getId()) {
                            throw new NoSuchEntityException(__('The url key has been used.', $urlKey));
                        }
                    }
                } else {
                    throw new NoSuchEntityException(__('The url key has been used.', $urlKey));
                }
            }
        } else {
            throw new NoSuchEntityException(__('Missing category url key'));
        }

        return true;
    }
}
