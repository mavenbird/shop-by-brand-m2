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

namespace Mavenbird\Shopbybrand\Api\Data;

interface BrandInterface
{
    public const BRAND_ID = 'brand_id';

    public const OPTION_ID = 'option_id';

    public const PAGE_TITLE = 'page_title';

    public const URL_KEY = 'url_key';

    public const IMAGE = 'image';

    public const SHORT_DESCRIPTION = 'short_description';

    public const DESCRIPTION = 'description';

    public const IS_FEATURED = 'is_featured';

    public const STATIC_BLOCK = 'static_block';

    public const META_TITLE = 'meta_title';

    public const META_KEYWORDS = 'meta_keywords';

    public const META_DESCRIPTION = 'meta_description';

    public const LABEL = 'label';

    public const VALUE = 'value';

    public const SORT_ORDER = 'sort_order';

    public const STORE_LABELS = 'store_labels';

    public const IS_DEFAULT = 'is_default';

    public const ATTRIBUTES = [
        self::BRAND_ID,
        self::OPTION_ID,
        self::PAGE_TITLE,
        self::URL_KEY,
        self::IMAGE,
        self::SHORT_DESCRIPTION,
        self::DESCRIPTION,
        self::IS_FEATURED,
        self::STATIC_BLOCK,
        self::META_TITLE,
        self::META_KEYWORDS,
        self::META_DESCRIPTION
    ];

    /**
     * Brand id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set brand id
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * Eav Option id
     *
     * @return int|null
     */
    public function getOptionId();

    /**
     * Set Eav Option id
     *
     * @param int $id
     *
     * @return $this
     */
    public function setOptionId($id);

    /**
     * Get Page Title
     *
     * @return void
     */
    public function getPageTitle();

    /**
     * Set Page Title
     *
     * @param [type] $title
     * @return void
     */
    public function setPageTitle($title);

    /**
     * Get Url key
     *
     * @return void
     */
    public function getUrlKey();

    /**
     * Set Url Key
     *
     * @param [type] $url
     * @return void
     */
    public function setUrlKey($url);

    /**
     * Get Image
     *
     * @return void
     */
    public function getImage();

    /**
     * Set Image
     *
     * @param [type] $image
     * @return void
     */
    public function setImage($image);

    /**
     * Get Short Description
     *
     * @return void
     */
    public function getShortDescription();

    /**
     * Set Short Descriptions
     *
     * @param [type] $value
     * @return void
     */
    public function setShortDescription($value);

    /**
     * Get Description
     *
     * @return void
     */
    public function getDescription();

    /**
     * Set Description
     *
     * @param [type] $value
     * @return void
     */
    public function setDescription($value);

    /**
     * Get Is Featured
     *
     * @return void
     */
    public function getIsFeatured();

    /**
     * Set Is Featured
     *
     * @param [type] $value
     * @return void
     */
    public function setIsFeatured($value);

    /**
     * Get Static Block
     *
     * @return void
     */
    public function getStaticBlock();

    /**
     * Set Static Block
     *
     * @param [type] $value
     * @return void
     */
    public function setStaticBlock($value);

    /**
     * Get Meta Title
     *
     * @return void
     */
    public function getMetaTitle();

    /**
     * Set Meta Title
     *
     * @param [type] $value
     * @return void
     */
    public function setMetaTitle($value);

    /**
     * Get Meta Keywords
     *
     * @return void
     */
    public function getMetaKeywords();

    /**
     * Set Meta Keywords
     *
     * @param [type] $value
     * @return void
     */
    public function setMetaKeywords($value);

    /**
     * Get Meta Description
     *
     * @return void
     */
    public function getMetaDescription();

    /**
     * Set Meta Description
     *
     * @param [type] $value
     * @return void
     */
    public function setMetaDescription($value);

    /**
     * Get option label
     *
     * @return string
     */
    public function getLabel();

    /**
     * Set option label
     *
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label);

    /**
     * Get option value
     *
     * @return string
     */
    public function getValue();

    /**
     * Set option value
     *
     * @param string $value
     *
     * @return string
     */
    public function setValue($value);

    /**
     * Get option order
     *
     * @return int|null
     */
    public function getSortOrder();

    /**
     * Set option order
     *
     * @param int $sortOrder
     *
     * @return $this
     */
    public function setSortOrder($sortOrder);

    /**
     * Get Is Default
     *
     * @return bool|null
     */
    public function getIsDefault();

    /**
     * Set Is Default
     *
     * @param bool $isDefault
     *
     * @return $this
     */
    public function setIsDefault($isDefault);

    /**
     * Get option label for store scopes
     *
     * @return \Magento\Eav\Api\Data\AttributeOptionLabelInterface[]|null
     */
    public function getStoreLabels();

    /**
     * Set option label for store scopes
     *
     * @param \Magento\Eav\Api\Data\AttributeOptionLabelInterface[] $storeLabels
     *
     * @return $this
     */
    public function setStoreLabels(array $storeLabels = null);
}
