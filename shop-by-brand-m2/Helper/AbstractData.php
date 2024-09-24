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

use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface; // Add this line

class AbstractData extends AbstractHelper
{
    public const CONFIG_MODULE_PATH = 'mavenbird';

    /**
     * Datas
     *
     * @var array
     */
    protected $_data = [];

    /**
     * Stores Manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Object Managers
     *
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Products Metadata
     *
     * @var ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * Urls
     *
     * @var UrlInterface
     */
    protected $url;

    /**
     * Scope Config
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig; // Add this line

    /**
     * Constructor
     *
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param ProductMetadataInterface $productMetadata
     * @param UrlInterface $url
     * @param ScopeConfigInterface $scopeConfig // Add this line
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        ProductMetadataInterface $productMetadata,
        UrlInterface $url,
        ScopeConfigInterface $scopeConfig // Add this line
    ) {
        $this->objectManager = $objectManager;
        $this->productMetadata = $productMetadata;
        $this->storeManager = $storeManager;
        $this->url = $url;
        $this->scopeConfig = $scopeConfig; // Add this line
        parent::__construct($context);
    }

    /**
     * Enabled
     *
     * @param int|null $storeId
     * @return boolean
     */
    public function isEnabled($storeId = null)
    {
        return $this->getConfigGeneral('enabled', $storeId);
    }

    /**
     * Config General
     *
     * @param string $code
     * @param int|null $storeId
     * @return string|null
     */
    public function getConfigGeneral($code = '', $storeId = null)
    {
        $code = ($code !== '') ? '/' . $code : '';

        return $this->getConfigValue(static::CONFIG_MODULE_PATH . '/general' . $code, $storeId);
    }

    /**
     * Module Config
     *
     * @param string $field
     * @param int|null $storeId
     * @return string|null
     */
    public function getModuleConfig($field = '', $storeId = null)
    {
        $field = ($field !== '') ? '/' . $field : '';

        return $this->getConfigValue(static::CONFIG_MODULE_PATH . $field, $storeId);
    }

    /**
     * Config Value
     *
     * @param string $field
     * @param int|null $storeId
     * @return string|null
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get Data
     *
     * @param string $name
     * @return mixed
     */
    public function getData($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }

        return null;
    }

    /**
     * Set Data
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setData($name, $value)
    {
        $this->_data[$name] = $value;

        return $this;
    }

    /**
     * Get Current Url
     *
     * @return string
     */
    public function getCurrentUrl()
    {
        return $this->url->getCurrentUrl();
    }

    /**
     * Serialize
     *
     * @param mixed $data
     * @return string
     */
    public function serialize($data)
    {
        if ($this->versionCompare('2.2.0')) {
            return self::jsonEncode($data);
        }

        return $this->getSerializeClass()->serialize($data);
    }

    /**
     * Version Compare
     *
     * @param string $ver
     * @return bool
     */
    public function versionCompare($ver)
    {
        $version = $this->productMetadata->getVersion();

        return version_compare($version, $ver, '>=');
    }

    /**
     * Json Encode
     *
     * @param mixed $valueToEncode
     * @return string
     */
    public static function jsonEncode($valueToEncode)
    {
        try {
            $encodeValue = self::getJsonHelper()->jsonEncode($valueToEncode);
        } catch (\Exception $e) {
            $encodeValue = '{}';
        }

        return $encodeValue;
    }

    /**
     * Json Helper
     *
     * @return JsonHelper
     */
    public static function getJsonHelper()
    {
        return ObjectManager::getInstance()->get(JsonHelper::class);
    }

    /**
     * Serialize Class
     *
     * @return \Zend_Serializer_Adapter_PhpSerialize
     */
    protected function getSerializeClass()
    {
        return $this->objectManager->get(\Zend_Serializer_Adapter_PhpSerialize::class);
    }

    /**
     * Unserialize
     *
     * @param string $string
     * @return mixed
     */
    public function unserialize($string)
    {
        if ($this->versionCompare('2.2.0')) {
            return self::jsonDecode($string);
        }

        return $this->getSerializeClass()->unserialize($string);
    }

    /**
     * Json Decode
     *
     * @param string $encodedValue
     * @return mixed
     */
    public static function jsonDecode($encodedValue)
    {
        try {
            $decodeValue = self::getJsonHelper()->jsonDecode($encodedValue);
        } catch (\Exception $e) {
            $decodeValue = [];
        }

        return $decodeValue;
    }

    /**
     * Admin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        /** @var \Magento\Framework\App\State $state */
        $state = $this->_state;

        try {
            $areaCode = $state->getAreaCode();
        } catch (\Exception $e) {
            return false;
        }

        return $areaCode == Area::AREA_ADMINHTML;
    }

    /**
     * Create Object
     *
     * @param string $path
     * @param array $arguments
     * @return object
     */
    public function createObject($path, $arguments = [])
    {
        return $this->objectManager->create($path, $arguments);
    }

    /**
     * Object
     *
     * @param string $path
     * @return object
     */
    public function getObject($path)
    {
        return $this->objectManager->get($path);
    }
}
