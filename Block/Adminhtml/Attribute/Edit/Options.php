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

namespace Mavenbird\Shopbybrand\Block\Adminhtml\Attribute\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Options as OOptions;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory;
use Magento\Framework\Registry;
use Magento\Framework\Validator\UniversalFactory;
use Mavenbird\Shopbybrand\Helper\Data;

class Options extends OOptions
{
    /**
     * Helper
     *
     * @var [type]
     */
    protected $brandHelper;

    /**
     * Templates
     *
     * @var string
     */
    protected $_template = 'Mavenbird_Shopbybrand::catalog/product/attribute/options.phtml';

    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $registry
     * @param CollectionFactory $attrOptionCollectionFactory
     * @param UniversalFactory $universalFactory
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        CollectionFactory $attrOptionCollectionFactory,
        UniversalFactory $universalFactory,
        Data $helper,
        array $data = []
    ) {
        $this->brandHelper = $helper;

        parent::__construct($context, $registry, $attrOptionCollectionFactory, $universalFactory, $data);
    }

    /**
     * Construct
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        if (!$this->brandHelper->versionCompare('2.1.0')) {
            $this->setTemplate('Mavenbird_Shopbybrand::catalog/product/attribute/options_old.phtml');
        }
    }

    /**
     * Brand Attribute
     *
     * @return boolean
     */
    public function isBrandAttribute()
    {
        return $this->brandHelper->isEnabled() && in_array(
            $this->getAttributeObject()->getAttributeCode(),
            $this->brandHelper->getAllBrandsAttributeCode(),
            true
        );
    }

    /**
     * Brand Update Url
     *
     * @return void
     */
    public function getBrandUpdateUrl()
    {
        return $this->getUrl('mmbrand/attribute/update');
    }

    /**
     * Stores Sorted By Sort Order
     *
     * @return void
     */
    public function getStoresSortedBySortOrder()
    {
        $stores = $this->getStores();
        if (is_array($stores)) {
            usort($stores, function ($storeA, $storeB) {
                if ($storeA->getSortOrder() === $storeB->getSortOrder()) {
                    return $storeA->getId() < $storeB->getId() ? -1 : 1;
                }

                return ($storeA->getSortOrder() < $storeB->getSortOrder()) ? -1 : 1;
            });
        }

        return $stores;
    }
}
