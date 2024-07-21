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

namespace Mavenbird\Shopbybrand\Block;

use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Cms\Model\BlockFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use Mavenbird\Shopbybrand\Helper\Data as BrandHelper;
use Mavenbird\Shopbybrand\Model\BrandFactory;
use Mavenbird\Shopbybrand\Model\Category;
use Mavenbird\Shopbybrand\Model\CategoryFactory;
use Mavenbird\Shopbybrand\Model\Config\Source\MetaRobots;

class Brand extends Template
{
    /**
     * Robots
     *
     * @var [type]
     */
    protected $mmRobots;

    /**
     * Data
     *
     * @var [type]
     */
    protected $helper;

    /**
     * Factory for Product Collection
     *
     * @var [type]
     */
    protected $_productCollectionFactory;

    /**
     * Factory for Category
     *
     * @var [type]
     */
    protected $_categoryFactory;

    /**
     * Factory for Brand
     *
     * @var [type]
     */
    protected $_brandFactory;

    /**
     * Character
     *
     * @var array
     */
    protected $_char = [];

    /**
     * Registry
     *
     * @var [type]
     */
    protected $_coreRegistry;

    /**
     * Factory for Block
     *
     * @var [type]
     */
    protected $_blockFactory;

    /**
     * Factory for Image
     *
     * @var [type]
     */
    protected $_imageFactory;

    /**
     * Connections
     *
     * @var [type]
     */
    protected $_connection;

    /**
     * Constructor
     *
     * @param Context $context
     * @param CollectionFactory $productCollectionFactory
     * @param CategoryFactory $categoryFactory
     * @param BrandFactory $brandFactory
     * @param Registry $coreRegistry
     * @param BlockFactory $blockFactory
     * @param AdapterFactory $imageFactory
     * @param BrandHelper $helper
     * @param ResourceConnection $connection
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        CategoryFactory $categoryFactory,
        BrandFactory $brandFactory,
        Registry $coreRegistry,
        BlockFactory $blockFactory,
        AdapterFactory $imageFactory,
        BrandHelper $helper,
        ResourceConnection $connection,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_categoryFactory          = $categoryFactory;
        $this->_brandFactory             = $brandFactory;
        $this->_coreRegistry             = $coreRegistry;
        $this->_blockFactory             = $blockFactory;
        $this->_imageFactory             = $imageFactory;
        $this->helper                    = $helper;
        $this->_connection               = $connection;

        parent::__construct($context, $data);
    }

    /**
     * Prepare Layout
     *
     * @return void
     */
    protected function _prepareLayout()
    {
        $objectManager  = ObjectManager::getInstance();
        $this->mmRobots = $objectManager->create(MetaRobots::class);
        $action         = $this->getRequest()->getFullActionName();

        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            if ($action === 'mmbrand_index_index'
                || $action === 'mmbrand_index_view'
                || $action === 'mmbrand_category_view'
            ) {
                $breadcrumbsBlock->addCrumb('home', [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link'  => $this->_storeManager->getStore()->getBaseUrl()
                ]);

                $this->additionCrumb($breadcrumbsBlock);
            }
            if ($action === 'mmbrand_category_view') {
                $category     = $this->_coreRegistry->registry('current_brand_category');
                $categoryName = $category->getName();
                if ($category->getId()) {
                    $breadcrumbsBlock->addCrumb('brand', [
                        'label' => __($this->getPageTitle()),
                        'link'  => $this->helper()->getBrandUrl()
                    ])
                        ->addCrumb($category->getUrlKey(), [
                            'label' => $categoryName,
                            'title' => $categoryName
                        ]);
                }
                $this->pageConfig->getTitle()->set($categoryName);
                $this->applySeoCode($category);
            } elseif ($action === 'mmbrand_index_view') {
                $breadcrumbsBlock->addCrumb('brand', [
                    'label' => __($this->getPageTitle()),
                    'title' => __($this->getPageTitle()),
                    'link'  => $this->helper()->getBrandUrl()
                ]);
                $this->pageConfig->getTitle()->set($this->getMetaTitle());
            } else {
                $this->pageConfig->getTitle()->set($this->getMetaTitle());
            }
        }

        return parent::_prepareLayout();
    }

    /**
     * Addition Crumb
     *
     * @param [type] $block
     * @return void
     */
    protected function additionCrumb($block)
    {
        $title = $this->getPageTitle();
        $block->addCrumb('brand', ['label' => $title]);

        return $this;
    }

    /**
     * Helper
     *
     * @return void
     */
    public function helper()
    {
        return $this->helper;
    }

    /**
     * Title Separator
     *
     * @param [type] $store
     * @return void
     */
    public function getTitleSeparator($store = null)
    {
        $separator = (string) $this->_scopeConfig->getValue(
            'catalog/seo/title_separator',
            ScopeInterface::SCOPE_STORE,
            $store
        );

        return ' ' . $separator . ' ';
    }

    /**
     * Filter Class
     *
     * @param [type] $brand
     * @return void
     */
    public function getFilterClass($brand)
    {
        return $this->helper()->getFilterClass($brand);
    }

    /**
     * Show Description
     *
     * @return void
     */
    public function showDescription()
    {
        return $this->helper()->getBrandConfig('show_description');
    }

    /**
     * Show Product Qty
     *
     * @return void
     */
    public function showProductQty()
    {
        return $this->helper()->getBrandConfig('show_product_qty');
    }

    /**
     * Show Quick View
     *
     * @return void
     */
    public function showQuickView()
    {
        return $this->helper()->showQuickView();
    }

    /**
     * Collection
     *
     * @param [type] $type
     * @param [type] $option
     * @return void
     */
    public function getCollection($type = null, $option = null)
    {
        return $this->helper()->getBrandList($type, $option);
    }

    /**
     * Apply Seo Code
     *
     * @param [type] $category
     * @return void
     */
    public function applySeoCode($category)
    {
        $title = $category->getMetaTitle();
        $this->pageConfig->getTitle()->set($title ?: $category->getName());

        $description = $category->getMetaDescription();
        $this->pageConfig->setDescription($description);

        $keywords = $category->getMetaKeywords();
        $this->pageConfig->setKeywords($keywords);

        $robot = $category->getMetaRobots();
        $array = $this->mmRobots->getOptionArray();
        $this->pageConfig->setRobots($array[$robot]);

        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($category->getName());
        }
    }

    /**
     * Page Title
     *
     * @return void
     */
    public function getPageTitle()
    {
        return $this->helper()->getBrandConfig('name') ?: __('Brands');
    }

    /**
     * Meta Title
     *
     * @return void
     */
    public function getMetaTitle()
    {
        return $this->getPageTitle();
    }

    /**
     * Product Quantity
     *
     * @param [type] $optionId
     * @return void
     */
    public function getProductQuantity($optionId)
    {
        if (!$this->hasData('product_qty_option_' . $optionId)) {
            $productCollection = $this->_productCollectionFactory->create();
            $productCollection
                ->setVisibility(
                    [
                        Visibility::VISIBILITY_IN_CATALOG,
                        Visibility::VISIBILITY_BOTH
                    ]
                )
                ->addAttributeToFilter('status', 1)
                ->addAttributeToFilter($this->helper->getAttributeCode(), $optionId);

            $this->setData('product_qty_option_' . $optionId, $productCollection->getSize());
        }

        return $this->getData('product_qty_option_' . $optionId);
    }

    /**
     * First Char
     *
     * @return void
     */
    public function getFirstChar()
    {
        $char       = [];
        $collection = $this->isInBrandCategoryView() ? $this->getCollection(
            BrandHelper::CATEGORY,
            $this->getOptionIds()
        ) : $this->getCollection();

        foreach ($collection as $brand => $item) {
            if ($this->helper()->getBrandConfig('brand_filter/encode_key')) {
                $char [] = mb_substr($item['value'], 0, 1, $this->helper()->getBrandConfig('brand_filter/encode_key'));
            } else {
                $char [] = mb_substr($item['value'], 0, 1, 'UTF-8');
            }
        }

        $char = array_unique($char);
        sort($char);

        return $char;
    }

    /**
     * Alphabet
     *
     * @return void
     */
    public function getAlphaBet()
    {
        $collection = $this->isInBrandCategoryView()
            ? $this->getCollection(BrandHelper::CATEGORY, $this->getOptionIds())
            : $this->getCollection();

        $this->_char = array_unique(
            explode(',', str_replace(' ', '', $this->helper()->getBrandConfig('brand_filter/alpha_bet')))
        );

        /*
         * remove empty  field in array
         */
        foreach ($this->_char as $offset => $row) {
            if (trim($row) === '') {
                unset($this->_char[$offset]);
            }
        }

        /*
         * set default alphabet if leave alphabet config blank
         */
        if (empty($this->_char)) {
            $this->_char = [
                'A',
                'B',
                'C',
                'D',
                'E',
                'F',
                'G',
                'H',
                'I',
                'J',
                'K',
                'L',
                'M',
                'N',
                'O',
                'P',
                'Q',
                'R',
                'S',
                'T',
                'U',
                'V',
                'W',
                'X',
                'Y',
                'Z'
            ];
        }

        $alphaBet    = [];
        $activeChars = [];

        foreach ($collection as $brand) {
            if ($encodeKey = $this->helper()->getBrandConfig('brand_filter/encode_key')) {
                $firstChar = mb_substr($brand->getValue(), 0, 1, $encodeKey);
            } else {
                $firstChar = mb_substr($brand->getValue(), 0, 1, 'UTF-8');
            }
            if (!in_array($firstChar, $activeChars, true)) {
                $activeChars[] = $firstChar;
            }
        }

        $activeChars = $this->helper()->convertUppercase($activeChars);

        foreach ($this->_char as $item) {
            $alphaBet[] = [
                'char'   => $item,
                'active' => in_array($item, $activeChars, true)
            ];
        }

        return $alphaBet;
    }

    /**
     * Option Ids
     *
     * @return void
     */
    public function getOptionIds()
    {
        $catId  = $this->getRequest()->getParam('cat_id');
        $result = [];
        $sql    = 'main_table.cat_id IN (' . $catId . ')';
        $brands = $this->_categoryFactory->create()->getCategoryCollection($sql, null)->getData();
        foreach ($brands as $brand => $item) {
            $result[] = $item['option_id'];
        }

        return $result;
    }

    /**
     * Image Url
     *
     * @param [type] $brand
     * @return void
     */
    public function getImageUrl($brand)
    {
        return $this->helper->getBrandImageUrl($brand);
    }

    /**
     * Brand Thumbnail
     *
     * @param [type] $brand
     * @return void
     */
    public function getBrandThumbnail($brand)
    {
        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
            . 'Mavenbird/resized/80/' . $brand->getImage();

        return $resizedURL;
    }

    /**
     * Categories
     *
     * @return void
     */
    public function getCategories()
    {
        return $this->helper()->getCategoryList();
    }

    /**
     * In Brand Category View
     *
     * @return boolean
     */
    public function isInBrandCategoryView()
    {
        $action = $this->getRequest()->getFullActionName();

        return $action === 'mmbrand_category_view';
    }

    /**
     * Load By Option
     *
     * @param [type] $option
     * @return void
     */
    public function loadByOption($option)
    {
        return $this->_brandFactory->create()->loadByOption($option);
    }

    /**
     * Brand Qty
     *
     * @param [type] $catId
     * @return void
     */
    public function getBrandQty($catId)
    {
        $sql    = 'main_table.cat_id IN (' . $catId . ')';
        $brands = $this->_categoryFactory->create()->getCategoryCollection($sql);

        return $brands->getSize();
    }

    /**
     * Logo Width
     *
     * @return void
     */
    public function getLogoWidth()
    {
        return $this->helper->getModuleConfig('brandpage/brand_logo_width');
    }

    /**
     * Logo Height
     *
     * @return void
     */
    public function getLogoHeight()
    {
        return $this->helper->getModuleConfig('brandpage/brand_logo_height');
    }
}
