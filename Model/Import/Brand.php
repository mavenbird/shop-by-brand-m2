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

namespace Mavenbird\Shopbybrand\Model\Import;

use Exception;
use Magento\Catalog\Model\Product\Attribute\Repository;
use Magento\CatalogImportExport\Model\Import\Uploader;
use Magento\CatalogImportExport\Model\Import\UploaderFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\ImportExport\Model\Import;
use Magento\ImportExport\Model\Import\AbstractEntity;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\ImportExport\Model\ImportFactory;
use Magento\ImportExport\Model\ResourceModel\Helper;
use Magento\Swatches\Helper\Media;
use Magento\Swatches\Model\Swatch;
use Mavenbird\Shopbybrand\Helper\Data;

class Brand extends AbstractEntity
{
    public const COL_ATTR_CODE                = 'attribute_code';
    public const COL_NAME                     = 'brand_name';
    public const COL_STORE_ID                 = 'store_id';
    public const COL_VALUE                    = 'label';
    public const COL_URL_KEY                  = 'url_key';
    public const COL_IMAGE                    = 'image';
    public const COL_FEATURED                 = 'is_featured';
    public const COL_SHORT_DESCRIPTION        = 'short_description';
    public const COL_DESCRIPTION              = 'description';
    public const COL_STATIC_BLOCK             = 'static_block';
    public const COL_META_TITLE               = 'meta_title';
    public const COL_META_DESCRIPTION         = 'meta_description';
    public const COL_META_KEYWORDS            = 'meta_keywords';
    public const COL_SWATCH_TYPE              = 'swatch_type';
    public const COL_SWATCH_VALUE             = 'swatch_value';
    public const SCOPE_DEFAULT                = 0;
    public const SCOPE_STORE                  = 1;
    public const ERROR_TITLE_IS_EMPTY         = 'Empty TITLE';
    public const ERROR_INVALID_ATTRIBUTE_CODE = 'invalidAttributeCode';
    public const ERROR_INVALID_BRAND_OPTION   = 'invalidBrandOption';
    public const ERROR_INVALID_VALUE          = 'invalidValue';
    public const ERROR_INVALID_ATTRIBUTE      = 'invalidAttribute';

    /**
     * Collection of attribute options
     *
     * @var [type]
     */
    protected $_optionCollection;

    /**
     * Helper for brand
     *
     * @var [type]
     */
    protected $_brandHelper;

    /**
     * Repository for product
     *
     * @var [type]
     */
    protected $_productRepository;

    /**
     * Uploader for file
     *
     * @var [type]
     */
    protected $_fileUploader;

    /**
     * Factory for uploader
     *
     * @var [type]
     */
    protected $_uploaderFactory;

    /**
     * Directory for media
     *
     * @var [type]
     */
    protected $_mediaDirectory;

    /**
     * Helper for Swatch
     *
     * @var [type]
     */
    protected $swatchHelper;

    /**
     * Available Behavior
     *
     * @var array
     */
    protected $_availableBehaviors = [
        Import::BEHAVIOR_ADD_UPDATE,
        Import::BEHAVIOR_REPLACE,
        Import::BEHAVIOR_DELETE
    ];

    /**
     * Message Template
     *
     * @var array
     */
    protected $_messageTemplates = [
        self::ERROR_INVALID_ATTRIBUTE_CODE => 'Invalid value in Attribute Code column',
        self::ERROR_INVALID_BRAND_OPTION   => 'Invalid value in Brand name column',
        self::ERROR_INVALID_VALUE          => 'Invalid value in Value column',
        self::ERROR_INVALID_ATTRIBUTE      => 'Attribute does not exist'
    ];

    /**
     * Valid Column Name
     *
     * @var array
     */
    protected $validColumnNames = [
        self::COL_NAME,
        self::COL_STORE_ID
    ];

    /**
     * Attribute Lists
     *
     * @var array
     */
    protected $_attributeList = [];

    /**
     * Option Lists
     *
     * @var array
     */
    protected $_optionList = [];

    /**
     * Master Attributes Code
     *
     * @var string
     */
    protected $masterAttributeCode = 'brand_name';

    /**
     * Resources
     *
     * @var [type]
     */
    protected $_resource;

    /**
     * Constructor
     *
     * @param StringUtils $string
     * @param ScopeConfigInterface $scopeConfig
     * @param ImportFactory $importFactory
     * @param Helper $resourceHelper
     * @param ResourceConnection $resource
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     * @param Repository $productRepository
     * @param CollectionFactory $optionCollection
     * @param Data $brandHelper
     * @param UploaderFactory $uploaderFactory
     * @param Filesystem $filesystem
     * @param Media $swatchHelper
     * @param array $data
     *
     * @throws FileSystemException
     */
    public function __construct(
        StringUtils $string,
        ScopeConfigInterface $scopeConfig,
        ImportFactory $importFactory,
        Helper $resourceHelper,
        ResourceConnection $resource,
        ProcessingErrorAggregatorInterface $errorAggregator,
        Repository $productRepository,
        CollectionFactory $optionCollection,
        Data $brandHelper,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem,
        Media $swatchHelper,
        array $data = []
    ) {
        $this->_resource             = $resource;
        $this->_productRepository    = $productRepository;
        $this->_optionCollection     = $optionCollection;
        $this->_brandHelper          = $brandHelper;
        $this->_uploaderFactory      = $uploaderFactory;
        $this->_mediaDirectory       = $filesystem->getDirectoryWrite(DirectoryList::ROOT);
        $this->swatchHelper          = $swatchHelper;
        $this->errorMessageTemplates = array_merge($this->errorMessageTemplates, $this->_messageTemplates);

        parent::__construct($string, $scopeConfig, $importFactory, $resourceHelper, $resource, $errorAggregator);
    }

    /**
     * @inheritDoc
     */
    public function getEntityTypeCode()
    {
        return 'mavenbird_brand';
    }

    /**
     * Import data
     *
     * @return void
     */
    protected function _importData()
    {
        if (Import::BEHAVIOR_DELETE === $this->getBehavior()) {
            $this->_deleteBrands();
        } else {
            $this->_saveBrands();
        }

        return true;
    }

    /**
     * Delete brands
     *
     * @return void
     */
    protected function _deleteBrands()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entitiesToDelete = [];

            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum) || (self::SCOPE_DEFAULT != $rowData[self::COL_STORE_ID])) {
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }

                if (array_key_exists($rowData[self::COL_NAME], $entitiesToDelete)) {
                    continue;
                }

                $options        = $this->processBrandOptions(
                    $this->getAttributeCode($rowData),
                    self::SCOPE_DEFAULT,
                    $rowNum
                );
                $optionToDelete = array_filter($options, function ($option) use (&$rowData) {
                    return $option['default_value'] === $rowData[self::COL_NAME];
                });
                if (is_array($optionToDelete) && !empty($optionToDelete)) {
                    $option                                     = array_shift($optionToDelete);
                    $entitiesToDelete[$rowData[self::COL_NAME]] = isset($option['option_id'])
                        ? $option['option_id'] : null;
                }
            }
            if (!empty($entitiesToDelete)) {
                $this->countItemsDeleted += $this->_connection->delete(
                    $this->_resource->getTableName('eav_attribute_option'),
                    $this->_connection->quoteInto('option_id IN (?)', array_values(array_filter($entitiesToDelete)))
                );
            }
        }

        return $this;
    }

    /**
     * Validate row
     *
     * @param array $rowData
     * @param [type] $rowNum
     * @return void
     */
    public function validateRow(array $rowData, $rowNum)
    {
        if (isset($this->_validatedRows[$rowNum])) {
            // check that row is already validated
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }

        $this->_validatedRows[$rowNum] = true;
        if (!isset($rowData[self::COL_NAME]) || !$rowData[self::COL_NAME]) {
            $this->addRowError(self::ERROR_INVALID_BRAND_OPTION, $rowNum);

            return false;
        }

        if (isset($rowData[self::COL_ATTR_CODE]) && $rowData[self::COL_ATTR_CODE]) {
            try {
                $this->_productRepository->get($rowData[self::COL_ATTR_CODE]);
            } catch (Exception $e) {
                $this->addRowError(self::ERROR_INVALID_ATTRIBUTE, $rowNum);

                return false;
            }
        }

        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    /**
     * Process brand options
     *
     * @param [type] $attributeCode
     * @param [type] $storeId
     * @param [type] $rowNum
     * @return void
     */
    private function processBrandOptions($attributeCode, $storeId, $rowNum)
    {
        if (!isset($this->_attributeList[$attributeCode])) {
            try {
                $attribute = $this->_productRepository->get($attributeCode);

                /** @var Collection $options */
                $options = $this->_optionCollection->create()
                    ->setAttributeFilter($attribute->getId())
                    ->setStoreFilter($storeId);

                $this->_attributeList[$attributeCode] = $options->getData();
            } catch (Exception $e) {
                $this->addRowError(self::ERROR_INVALID_ATTRIBUTE, $rowNum);

                return false;
            }
        }

        return $this->_attributeList[$attributeCode];
    }

    /**
     * Attribute code
     *
     * @param [type] $rowData
     * @return void
     */
    private function getAttributeCode($rowData)
    {
        return (isset($rowData[self::COL_ATTR_CODE]) && $rowData[self::COL_ATTR_CODE])
            ? $rowData[self::COL_ATTR_CODE] : $this->_brandHelper->getAttributeCode(self::SCOPE_DEFAULT);
    }

    /**
     * Save brands
     *
     * @return void
     */
    protected function _saveBrands()
    {
        $eavAttributeOptionTable = $this->_resource->getTableName('eav_attribute_option_value');
        $eavAttributeSwatchTable = $this->_resource->getTableName('eav_attribute_option_swatch');
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $brandData = [
                $this->_resource->getTableName('mavenbird_brand') => [],
                $eavAttributeSwatchTable                          => []
            ];
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    $this->addRowError(self::ERROR_TITLE_IS_EMPTY, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }

                if (!isset($this->_optionList[$rowData[self::COL_NAME]])) {
                    $storeId       = $rowData[self::COL_STORE_ID];
                    $attributeCode = $this->getAttributeCode($rowData);
                    $optionList    = $this->processBrandOptions($attributeCode, $storeId, $rowNum);
                    $optionFilters = array_filter($optionList, function ($option) use (&$rowData) {
                        return $option['default_value'] == $rowData[self::COL_NAME];
                    });
                    if (is_array($optionFilters) && !empty($optionFilters)) {
                        $option                                      = array_shift($optionFilters);
                        $this->_optionList[$rowData[self::COL_NAME]] = $option['option_id'];
                    } else {
                        $attribute                                   = $this->_productRepository->get($attributeCode);
                        $this->countItemsCreated                     = $this->_connection->insert(
                            $this->_resource->getTableName('eav_attribute_option'),
                            ['attribute_id' => $attribute->getId(), 'sort_order' => 0]
                        );
                        $this->_optionList[$rowData[self::COL_NAME]] = $this->_connection->lastInsertId(
                            $this->_resource->getTableName('eav_attribute_option')
                        );
                    }
                }

                $rowData['option_id'] = $this->_optionList[$rowData[self::COL_NAME]];
                $scope                = ($rowData[self::COL_STORE_ID] === self::SCOPE_DEFAULT) ? $rowData[self::COL_NAME] : '';
                $rowData['value']     = (isset($rowData[self::COL_VALUE]) && $rowData[self::COL_VALUE])
                    ? $rowData[self::COL_VALUE]
                    : $scope;

                if (isset($rowData['value']) && $rowData['value']) {
                    /**
                     * Table attribute_option_value doesn't have index
                     * (option_id, store_id) so cannot insert on duplicate row
                     */
                    $this->_connection->delete(
                        $eavAttributeOptionTable,
                        ['option_id =?' => $rowData['option_id'], 'store_id =?' => $rowData['store_id']]
                    );
                    $this->_connection->insert(
                        $eavAttributeOptionTable,
                        [
                            'option_id' => $rowData['option_id'],
                            'store_id'  => $rowData['store_id'],
                            'value'     => $rowData['value']
                        ]
                    );
                }

                if (isset($rowData['image']) && $rowData['image']) {
                    $res = $this->_getUploader('brand')->move($rowData['image']);
                    $this->_getUploader('resize')->move($rowData['image']);
                    $rowData['image'] = 'mavenbird/brand' . $res['file'];
                }

                $brandData[$this->_resource->getTableName('mavenbird_brand')][] = [
                    'option_id'         => $rowData['option_id'],
                    'store_id'          => $rowData['store_id'],
                    'url_key'           => isset($rowData['url_key']) ? $rowData['url_key'] : null,
                    'image'             => isset($rowData['image']) ? $rowData['image'] : null,
                    'is_featured'       => isset($rowData['is_featured']) ? $rowData['is_featured'] : null,
                    'short_description' => isset($rowData['short_description']) ? $rowData['short_description'] : null,
                    'description'       => isset($rowData['description']) ? $rowData['description'] : null,
                    'static_block'      => isset($rowData['static_block']) ? $rowData['static_block'] : null,
                    'meta_title'        => isset($rowData['meta_title']) ? $rowData['meta_title'] : null,
                    'meta_keywords'     => isset($rowData['meta_keywords']) ? $rowData['meta_keywords'] : null,
                    'meta_description'  => isset($rowData['meta_description']) ? $rowData['meta_description'] : null,
                ];

                if (isset($rowData[self::COL_SWATCH_TYPE], $rowData[self::COL_SWATCH_VALUE])
                    && ($rowData[self::COL_STORE_ID] == self::SCOPE_DEFAULT)
                ) {
                    if (($rowData[self::COL_SWATCH_TYPE] == Swatch::SWATCH_TYPE_VISUAL_IMAGE)
                        && $rowData[self::COL_SWATCH_VALUE]
                    ) {
                        $res                             = $this->_getUploader('swatch')->move(
                            $rowData[self::COL_SWATCH_VALUE],
                            true
                        );
                        $rowData[self::COL_SWATCH_VALUE] = $res['file'];
                    }
                    $brandData[$eavAttributeSwatchTable][] = [
                        'option_id' => $rowData['option_id'],
                        'store_id'  => self::SCOPE_DEFAULT,
                        'type'      => $rowData[self::COL_SWATCH_TYPE],
                        'value'     => $rowData[self::COL_SWATCH_VALUE]
                    ];
                }
            }
            foreach ($brandData as $key => $data) {
                if (!empty($data)) {
                    $updateFields = ($key === $eavAttributeOptionTable) ? ['value'] :
                        $this->getAttributeFields($key);

                    $this->countItemsUpdated = $this->_connection->insertOnDuplicate(
                        $this->_connection->getTableName($key),
                        $data,
                        $updateFields
                    );
                }
            }
        }
    }

    /**
     * Uploader
     *
     * @param [type] $type
     * @return void
     */
    protected function _getUploader($type)
    {
        if (!isset($this->_fileUploader[$type]) || ($this->_fileUploader[$type] === null)) {
            $fileUploader = $this->_uploaderFactory->create();

            $fileUploader->init();

            $dirConfig = DirectoryList::getDefaultConfig();
            $dirAddon  = $dirConfig[DirectoryList::MEDIA][DirectoryList::PATH];

            $DS = DIRECTORY_SEPARATOR;

            if (!empty($this->_parameters[Import::FIELD_NAME_IMG_FILE_DIR])) {
                $tmpPath = $this->_parameters[Import::FIELD_NAME_IMG_FILE_DIR];
            } else {
                $tmpPath = $dirAddon . $DS . $this->_mediaDirectory->getRelativePath('import');
            }

            if (!$fileUploader->setTmpDir($tmpPath)) {
                throw new LocalizedException(
                    __('File directory \'%1\' is not readable.', $tmpPath)
                );
            }
            switch ($type) {
                case 'brand':
                    $destinationDir = 'mavenbird/brand';
                    break;
                case 'resize':
                    $destinationDir = 'mavenbird/resized/80/mavenbird/brand';
                    break;
                case 'swatch':
                    $destinationDir = 'attribute/swatch';
                    break;
                default:
                    $destinationDir = 'mavenbird/brand';
                    break;
            }
            $destinationPath = $dirAddon . $DS . $this->_mediaDirectory->getRelativePath($destinationDir);

            $this->_mediaDirectory->create($destinationPath);
            if (!$fileUploader->setDestDir($destinationPath)) {
                throw new LocalizedException(
                    __('File directory \'%1\' is not writable.', $destinationPath)
                );
            }

            $this->_fileUploader[$type] = $fileUploader;
        }

        return $this->_fileUploader[$type];
    }

    /**
     * Attribute fields
     *
     * @param [type] $key
     * @return void
     */
    public function getAttributeFields($key)
    {
        return $key === $this->_resource->getTableName('eav_attribute_option_swatch') ? ['type', 'value'] :
            [
                'url_key',
                'image',
                'is_featured',
                'short_description',
                'description',
                'static_block',
                'meta_title',
                'meta_keywords',
                'meta_description'
            ];
    }
}
