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

namespace Mavenbird\Shopbybrand\Helper;

use Exception;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Filter\TranslitUrl;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Swatches\Helper\Media;
use Magento\Swatches\Model\Swatch;
use Mavenbird\Shopbybrand\Helper\AbstractData;
use Mavenbird\Shopbybrand\Model\Brand;
use Mavenbird\Shopbybrand\Model\BrandFactory;
use Mavenbird\Shopbybrand\Model\Category;
use Mavenbird\Shopbybrand\Model\CategoryFactory;
use Mavenbird\Shopbybrand\Model\ResourceModel\Category\Collection as BrandCategoryCollection;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractData
{
    public const CONFIG_MODULE_PATH = 'shopbybrand';
    public const IMAGE_SIZE = '135x135';
    public const GENERAL_CONFIGURATION = 'shopbybrand/general';
    public const BRAND_SIDEBAR_CONFIGURATION = 'sidebar';
    public const BRAND_CONFIGURATION = 'brandpage';
    public const BRAND_DETAIL_CONFIGURATION = 'brandview';
    public const BRAND_MEDIA_PATH = 'mavenbird/brand';
    public const DEFAULT_ROUTE = 'brand';
    public const CATEGORY = 'category';
    public const BRAND_FIRST_CHAR = 'char';
    public const XML_PATH_FILTER_HEADING = 'shopbybrand/brandpage/brand_filter/filter_brands_title';
    public const XML_PATH_FEATURE = 'shopbybrand/brandpage/brand_filter/enable_navigation';

    /**
     * Filter Manager
     *
     * @var [type]
     */
    protected $_filter;

    /**
     * Translit Urls
     *
     * @var [type]
     */
    protected $translitUrl;

    /**
     * Factory for Category
     *
     * @var [type]
     */
    protected $categoryFactory;

    /**
     * Character
     *
     * @var string
     */
    protected $_char = '';

    /**
     * Factory for Brand
     *
     * @var [type]
     */
    protected $_brandFactory;

    /**
     * Brand Collections
     *
     * @var [type]
     */
    protected $_brandCollection;

    /**
     * Core Registry
     *
     * @var [type]
     */
    protected $registry;

    /**
     * Attributes
     *
     * @var [type]
     */
    protected $_attribute;

    /**
     * Brand Collections
     *
     * @var [type]
     */
    protected $brandCollectionCache;

    /**
     * Filter Providers
     *
     * @var [type]
     */
    protected $filterProvider;

     /**
     * Products Metadata
     *
     * @var [type]
     */
    protected $productMetadata;

    /**
     * Urls
     *
     * @var [type]
     */
    protected $url;

     /**
     * Scope Config
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param ObjectManagerInterface $objectManager
     * @param TranslitUrl $translitUrl
     * @param FilterManager $filter
     * @param CategoryFactory $categoryFactory
     * @param BrandFactory $brandFactory
     * @param Registry $registry
     * @param Attribute $attribute
     * @param FilterProvider $filterProvider
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ObjectManagerInterface $objectManager,
        TranslitUrl $translitUrl,
        FilterManager $filter,
        CategoryFactory $categoryFactory,
        BrandFactory $brandFactory,
        Registry $registry,
        Attribute $attribute,
        FilterProvider $filterProvider,
        ProductMetadataInterface $productMetadata,
        UrlInterface $url,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_filter         = $filter;
        $this->translitUrl     = $translitUrl;
        $this->categoryFactory = $categoryFactory;
        $this->_brandFactory   = $brandFactory;
        $this->registry        = $registry;
        $this->_attribute      = $attribute;
        $this->filterProvider  = $filterProvider;
        $this->objectManager  = $objectManager;
        $this->storeManager  = $storeManager;
        $this->productMetadata = $productMetadata;
        $this->url = $url;

        parent::__construct($context, $objectManager, $storeManager, $productMetadata, $url, $scopeConfig);
    }

    /**
     * Show Brand Link
     *
     * @param [type] $position
     * @return boolean
     */
    public function canShowBrandLink($position)
    {
        if (!$this->isEnabled()) {
            return false;
        }
        $positionConfig = explode(',', $this->getConfigGeneral('show_position'));

        return in_array($position, $positionConfig, true);
    }

    /**
     * Brand Url
     *
     * @param [type] $brand
     * @return void
     */
    public function getBrandUrl($brand = null)
    {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        $key     = ($brand === null) ? '' : '/' . $this->processKey($brand);

        return $baseUrl . $this->getRoute() . $key . $this->getUrlSuffix();
    }

    /**
     * Process Key
     *
     * @param [type] $brand
     * @return void
     */
    public function processKey($brand)
    {
        if (!$brand) {
            return '';
        }
        $str = $brand->getUrlKey() ?: $brand->getDefaultValue();

        return $this->formatUrlKey($str);
    }

    /**
     * Format Url Key
     *
     * @param [type] $str
     * @return void
     */
    public function formatUrlKey($str)
    {
        return $this->_filter->translitUrl($str);
    }

    /**
     * Brand Image Url
     *
     * @param [type] $brand
     * @return void
     */
    public function getBrandImageUrl($brand)
    {
        if ($brand->getImage()) {
            $image = $brand->getImage();
        } elseif ($brand->getSwatchType() == Swatch::SWATCH_TYPE_VISUAL_IMAGE) {
            $image = Media::SWATCH_MEDIA_PATH . $brand->getSwatchValue();
        } elseif ($this->getBrandDetailConfig('default_image')) {
            $image = self::BRAND_MEDIA_PATH . '/' . $this->getBrandDetailConfig('default_image');
        } else {
            return ObjectManager::getInstance()
                ->get(Image::class)
                ->getDefaultPlaceholderUrl('small_image');
        }

        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $image;
    }

    /**
     * Brand Title
     *
     * @return void
     */
    public function getBrandTitle()
    {
        return $this->getConfigGeneral('link_title') ?: __('Brands');
    }

    /**
     * Brand Description
     *
     * @param [type] $brand
     * @param boolean $short
     * @return void
     */
    public function getBrandDescription($brand, $short = false)
    {
        if ($short) {
            $content = $brand->getShortDescription() ?: '';
        } else {
            $content = $brand->getDescription() ?: '';
        }

        try {
            return $this->filterProvider->getBlockFilter()->filter($content);
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Attribute Code
     *
     * @param [type] $store
     * @return void
     */
    public function getAttributeCode($store = null)
    {
        return $this->getConfigGeneral('attribute', $store);
    }

    /**
     * Route
     *
     * @return void
     */
    public function getRoute()
    {
        $route = $this->getConfigGeneral('route', $this->getStoreId()) ?: self::DEFAULT_ROUTE;

        return $this->formatUrlKey($route);
    }

    /**
     * Get Url Suffix
     *
     * @param [type] $storeId
     * @return void
     */
    public function getUrlSuffix($storeId = null)
    {
        return $this->scopeConfig->getValue(
            CategoryUrlPathGenerator::XML_PATH_CATEGORY_URL_SUFFIX,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Brand Config
     *
     * @param string $code
     * @param [type] $store
     * @return void
     */
    public function getBrandConfig($code = '', $store = null)
    {
        $code = $code ? self::BRAND_CONFIGURATION . '/' . $code : self::BRAND_CONFIGURATION;

        return $this->getModuleConfig($code, $store);
    }

    /**
     * Image Size
     *
     * @param string $group
     * @param [type] $store
     * @return void
     */
    public function getImageSize($group = '', $store = null)
    {
        $imageSize = $this->getBrandConfig($group . 'image_size', $store) ?: self::IMAGE_SIZE;

        return explode('x', $imageSize);
    }

    /**
     * Feature Config
     *
     * @param string $code
     * @param [type] $store
     * @return void
     */
    public function getFeatureConfig($code = '', $store = null)
    {
        $code = $code ? 'feature' . '/' . $code : 'feature';

        return $this->getBrandConfig($code, $store);
    }

    /**
     * Enable Feature
     *
     * @param [type] $store
     * @return void
     */
    public function enableFeature($store = null)
    {
        return $this->getFeatureConfig('enable', $store);
    }

    /**
     * Search Config
     *
     * @param string $code
     * @param [type] $store
     * @return void
     */
    public function getSearchConfig($code = '', $store = null)
    {
        $code = $code ? 'search' . '/' . $code : 'search';

        return $this->getBrandConfig($code, $store);
    }

    /**
     * Enable Search
     *
     * @param [type] $store
     * @return void
     */
    public function enableSearch($store = null)
    {
        return $this->getSearchConfig('enable', $store);
    }

    /**
     * Brand Detail Config
     *
     * @param string $code
     * @param [type] $store
     * @return void
     */
    public function getBrandDetailConfig($code = '', $store = null)
    {
        $code = $code ? self::BRAND_DETAIL_CONFIGURATION . '/' . $code : self::BRAND_DETAIL_CONFIGURATION;

        return $this->getModuleConfig($code, $store);
    }

    /**
     * Sidebar Config
     *
     * @param string $code
     * @param [type] $store
     * @return void
     */
    public function getSidebarConfig($code = '', $store = null)
    {
        $code = $code ? self::BRAND_SIDEBAR_CONFIGURATION . '/' . $code : self::BRAND_SIDEBAR_CONFIGURATION;

        return $this->getModuleConfig($code, $store);
    }

    /**
     * All Brands Attribute Code
     *
     * @return void
     */
    public function getAllBrandsAttributeCode()
    {
        $stores           = $this->storeManager->getStores();
        $attributeCodes   = [];
        $attributeCodes[] = $this->getAttributeCode();
        foreach ($stores as $store) {
            $attributeCodes[] = $this->getAttributeCode($store->getId());
        }
        $attributeCodes = array_unique($attributeCodes);

        return $attributeCodes;
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
        $name = $this->removeUnicode($name);
        $text = $this->translitUrl->filter($name);
        if ($count == 0) {
            $count = '';
        }
        if (empty($text)) {
            return 'n-a' . $count;
        }

        return $text . $count;
    }

    /**
     * Remove Unicode
     *
     * @param [type] $str
     * @return void
     */
    public function removeUnicode($str)
    {
        $str = mb_strtolower(trim($str));
        $str = preg_replace('/([àáạảãâầấậẩẫăằắặẳẵ])/u', 'a', $str);
        $str = preg_replace('/([èéẹẻẽêềếệểễ])/u', 'e', $str);
        $str = preg_replace('/([ìíịỉĩ])/u', 'i', $str);
        $str = preg_replace('/([òóọỏõôồốộổỗơờớợởỡ])/u', 'o', $str);
        $str = preg_replace('/([ùúụủũưừứựửữ])/u', 'u', $str);
        $str = preg_replace('/([ỳýỵỷỹ])/u', 'y', $str);
        $str = preg_replace('/(đ)/u', 'd', $str);

        return $str;
    }

    /**
     * Cat Url
     *
     * @param [type] $cat
     * @return void
     */
    public function getCatUrl($cat = null)
    {
        $baseUrl    = $this->storeManager->getStore()->getBaseUrl();
        $brandRoute = $this->getRoute();
        $key        = ($cat === null) ? '' : '/' . $this->processKey($cat);

        return $baseUrl . $brandRoute . '/' . self::CATEGORY . $key . $this->getUrlSuffix();
    }

    /**
     * Store Id
     *
     * @return void
     */
    public function getStoreId()
    {
        try {
            $storeId = $this->storeManager->getStore()->getId();
        } catch (NoSuchEntityException $e) {
            $storeId = 0;
        }

        return $storeId;
    }

    /**
     * Brand Route
     *
     * @param [type] $routePath
     * @param [type] $routeSize
     * @return boolean
     */
    public function isBrandRoute($routePath, $routeSize)
    {
        if ($routeSize > 3) {
            return false;
        }

        $urlSuffix  = $this->getUrlSuffix();
        $brandRoute = $this->getRoute();
        if ($urlSuffix) {
            $brandSuffix = strpos($brandRoute, $urlSuffix);
            if ($brandSuffix) {
                $brandRoute = substr($brandRoute, 0, $brandSuffix);
            }
        }

        return ($routePath[0] === $brandRoute);
    }

    /**
     * Category By Url Key
     *
     * @param [type] $urlKey
     * @return void
     */
    public function getCategoryByUrlKey($urlKey)
    {
        /** @var Category $cat */
        $cat = $this->categoryFactory->create()->load($urlKey, 'url_key');
        if ($cat) {
            return $cat->getId();
        }

        return null;
    }

    /**
     * Brand List
     *
     * @param [type] $type
     * @param [type] $ids
     * @return void
     */
    public function getBrandList($type = null, $ids = null)
    {
        /** @var Brand $brands */
        $brands = $this->_brandFactory->create();
        switch ($type) {
            //Get Brand List by Category
            case self::CATEGORY:
                $list = $brands->getBrandCollection(null, ['main_table.option_id' => ['in' => $ids]]);
                break;
            //Get Brand List Filtered by Brand First Char
            case self::BRAND_FIRST_CHAR:
                $list = $brands->getBrandCollection();
                break;
            default:
                //Get Brand List
                if (!$this->brandCollectionCache) {
                    $this->brandCollectionCache = $brands->getBrandCollection();
                }
                $list = $this->brandCollectionCache;
        }

        return $list;
    }

    /**
     * Has Data
     *
     * @param string $key
     * @return boolean
     */
    public function hasData($key = '')
    {
        if (empty($key) || !is_string($key)) {
            return !empty($this->_data);
        }

        return array_key_exists($key, $this->_data);
    }

    /**
     * Check Character
     *
     * @param [type] $char
     * @return void
     */
    public function checkCharacter($char)
    {
        $specialChar = [
            '!',
            '"',
            '#',
            '$',
            '&',
            '(',
            ')',
            '*',
            '+',
            ',',
            '-',
            '.',
            '/',
            ':',
            ';',
            '<',
            '=',
            '>',
            '?',
            '@',
            '[',
            ']',
            '^',
            '_',
            '`',
            '{',
            '|',
            '}',
            '~'
        ];
        if (in_array($char, $specialChar, true)) {
            $sqlCond = 'IF(tsv.value_id > 0, tsv.value, tdv.value) LIKE ' . "'" . $char . "%'";
        } elseif ($char === "'") {
            $sqlCond = 'IF(tsv.value_id > 0, tsv.value, tdv.value) LIKE ' . '"' . $char . '%"';
        } else {
            $sqlCond = 'IF(tsv.value_id > 0, tsv.value, tdv.value) REGEXP BINARY ' . "'^" . $char . "'";
        }

        return $sqlCond;
    }

    /**
     * Category List
     *
     * @return void
     */
    public function getCategoryList()
    {
        $collection = $this->categoryFactory->create()
            ->getCollection()
            ->addFieldToFilter('status', '1')
            ->addFieldToFilter(['store_ids', 'store_ids'], [
                ['finset' => $this->getStoreId()],
                ['finset' => 0]
            ]);

        return $collection;
    }

    /**
     * Filter Class
     *
     * @param [type] $brand
     * @return void
     */
    public function getFilterClass($brand)
    {
        //vietnamese unikey format
        if ($this->getBrandConfig('brand_filter/encode_key')) {
            $firstChar = mb_substr($brand->getValue(), 0, 1, $this->getBrandConfig('brand_filter/encode_key'));
        } else {
            $firstChar = mb_substr($brand->getValue(), 0, 1, 'UTF-8');
        }

        return is_numeric($firstChar) ? 'num' . $firstChar : ucfirst($firstChar);
    }

    /**
     * Cat Filter Class
     *
     * @param [type] $optionId
     * @return void
     */
    public function getCatFilterClass($optionId)
    {
        $catName = [];
        $sql     = 'brand_cat_tbl.option_id IN (' . $optionId . ')';
        $group   = 'main_table.cat_id';

        $collection = $this->categoryFactory->create()->getCategoryCollection($sql, $group);
        foreach ($collection as $item) {
            $str       = str_replace([' ', '*', '/', '\\'], '_', $item->getName());
            $catName[] = 'cat' . $str;
        }

        return implode(' ', $catName);
    }

    /**
     * Show Quick View
     *
     * @return void
     */
    public function showQuickView()
    {
        return $this->getBrandConfig('show_quick_view');
    }

    /**
     * Quick View Url
     *
     * @return void
     */
    public function getQuickViewUrl()
    {
        return $this->_getUrl('');
    }

    /**
     * Quick View
     *
     * @param [type] $brand
     * @return void
     */
    public function getQuickview($brand = null)
    {
        $key = ($brand === null) ? '' : '/' . $this->processKey($brand);

        return $this->getRoute() . $key . $this->getUrlSuffix();
    }

    /**
     * Brand Text From Product
     *
     * @param Product|null $product
     * @return void
     */
    public function getBrandTextFromProduct(Product $product = null)
    {
        $currentProduct = $product ?: $this->getCurrentProduct();
        if (!$currentProduct) {
            return null;
        }

        $attCode = $this->getAttributeCode();

        if (!$attCode) {
            // Handle the case where the attribute code is not defined
            return null;
        }

        return $currentProduct->getAttributeText($attCode);
    }

    /**
     * Brand Object
     *
     * @return void
     */
    public function getBrandObject()
    {
        $currentProduct = $this->getCurrentProduct();
        if (!$currentProduct) {
            return null;
        }

        $optionId = $currentProduct->getData($this->getAttributeCode());
        if (!$optionId) {
            return null;
        }

        return $this->_brandFactory->create()->loadByOption($optionId);
    }

    /**
     * Current Product
     *
     * @return void
     */
    public function getCurrentProduct()
    {
        if (!$this->registry->registry('current_product')) {
            return $this->registry->registry('product');
        }

        return $this->registry->registry('current_product');
    }

    /**
     * Brand
     *
     * @return void
     */
    public function getBrand()
    {
        return $this->registry->registry('current_brand');
    }

    /**
     * Convert Uppercase
     *
     * @param [type] $array
     * @return void
     */
    public function convertUppercase($array)
    {
        $input = array_flip($array);
        $input = array_change_key_case($input, CASE_UPPER);
        $input = array_flip($input);

        return $input;
    }

    /**
     * Attribute Id
     *
     * @param [type] $code
     * @return void
     */
    public function getAttributeId($code)
    {
        return $this->_attribute->getIdByCode(Product::ENTITY, $code);
    }

    /**
     * GetFilterBrandsTitle
     *
     * @param [type] $store
     * @return void
     */
    public function getFilterBrandsTitle()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_FILTER_HEADING, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * GetFeatureConfig
     *
     * @param [type] $field
     * @return void
     */
    public function getFeatureConfig1($field)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_FEATURE . $field, ScopeInterface::SCOPE_STORE);
    }
    
}
