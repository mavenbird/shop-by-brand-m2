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

interface BrandCategoryInterface
{
    public const STATUS           = 'status';
    public const STORE_IDS        = 'store_ids';
    public const NAME             = 'name';
    public const URL_KEY          = 'url_key';
    public const META_TITLE       = 'meta_title';
    public const META_KEYWORDS    = 'meta_keywords';
    public const META_DESCRIPTION = 'meta_description';
    public const META_ROBOTS      = 'meta_robots';
    public const CREATED_AT       = 'created_at';
    public const UPDATED_AT       = 'updated_at';

    public const ATTRIBUTES = [
        'cat_id',
        self::STATUS,
        self::STORE_IDS,
        self::NAME,
        self::URL_KEY,
        self::META_TITLE,
        self::META_KEYWORDS,
        self::META_DESCRIPTION,
        self::META_ROBOTS,
        self::CREATED_AT,
        self::UPDATED_AT
    ];

    /**
     * Brand category id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set brand category id
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * Store Ids
     *
     * @return void
     */
    public function getStoreIds();

    /**
     * Set Store Ids
     *
     * @param [type] $store
     * @return void
     */
    public function setStoreIds($store);

    /**
     * Get Name
     *
     * @return void
     */
    public function getName();

    /**
     * Set Name
     *
     * @param [type] $name
     * @return void
     */
    public function setName($name);

    /**
     * Get Status
     *
     * @return void
     */
    public function getStatus();

    /**
     * Set Status
     *
     * @param [type] $status
     * @return void
     */
    public function setStatus($status);

    /**
     * Get Url Key
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
     * Get Meta Robots
     *
     * @return void
     */
    public function getMetaRobots();

    /**
     * Set Meta Robots
     *
     * @param [type] $value
     * @return void
     */
    public function setMetaRobots($value);

    /**
     * Get Created At
     *
     * @return void
     */
    public function getCreatedAt();

    /**
     * Set Created At
     *
     * @param [type] $createdAt
     * @return void
     */
    public function setCreatedAt($createdAt);

    /**
     * Get Updated At
     *
     * @return void
     */
    public function getUpdatedAt();

    /**
     * Set Updated At
     *
     * @param [type] $updatedAt
     * @return void
     */
    public function setUpdatedAt($updatedAt);
}
