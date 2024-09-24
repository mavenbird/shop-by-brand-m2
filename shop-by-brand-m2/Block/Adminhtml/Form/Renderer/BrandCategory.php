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

namespace Mavenbird\Shopbybrand\Block\Adminhtml\Form\Renderer;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;
use Mavenbird\Shopbybrand\Helper\Data as BrandHelper;
use Mavenbird\Shopbybrand\Model\BrandFactory;
use Mavenbird\Shopbybrand\Model\CategoryFactory;

class BrandCategory extends Element
{
    /**
     * Templates
     *
     * @var string
     */
    protected $_template = 'Mavenbird_Shopbybrand::category/brands.phtml';

    /**
     * Data
     *
     * @var [type]
     */
    protected $helperData;

    /**
     * Factory for Brand
     *
     * @var [type]
     */
    protected $brandFactory;

    /**
     * Factory for Category Brand
     *
     * @var [type]
     */
    protected $brandCategoryFactory;

    /**
     * Store
     *
     * @var [type]
     */
    protected $systemStore;

    /**
     * Registry
     *
     * @var [type]
     */
    protected $coreRegistry;

    /**
     * Constructor
     *
     * @param BrandHelper $helperData
     * @param BrandFactory $brandFactory
     * @param CategoryFactory $brandCategoryFactory
     * @param Store $systemStore
     * @param Registry $coreRegistry
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        BrandHelper $helperData,
        BrandFactory $brandFactory,
        CategoryFactory $brandCategoryFactory,
        Store $systemStore,
        Registry $coreRegistry,
        Context $context,
        array $data = []
    ) {
        $this->helperData           = $helperData;
        $this->brandFactory         = $brandFactory;
        $this->coreRegistry         = $coreRegistry;
        $this->systemStore          = $systemStore;
        $this->brandCategoryFactory = $brandCategoryFactory;

        parent::__construct($context, $data);
    }

    /**
     * Brands
     *
     * @return void
     */
    public function getBrands()
    {
        return $this->brandFactory->create()->getBrandCollection();
    }

    /**
     * Store Views
     *
     * @return void
     */
    public function getStoreViews()
    {
        return $this->systemStore->getStoreValuesForForm();
    }

    /**
     * Single Store Mode
     *
     * @return boolean
     */
    public function isSingleStoreMode()
    {
        return $this->_storeManager->isSingleStoreMode();
    }

    /**
     * Selected Brands
     *
     * @return void
     */
    public function getSelectedBrands()
    {
        $optionIds = [];
        $model     = $this->coreRegistry->registry('current_brand_category');
        if ($model->getId()) {
            $collection = $this->brandCategoryFactory->create()->getCollection();
            $collection->getSelect()
                ->joinInner(
                    ['at' => $collection->getTable('mavenbird_shopbybrand_brand_category')],
                    'main_table.cat_id = at.cat_id'
                );

            foreach ($collection->getData() as $item) {
                if ($item['cat_id'] === $model->getId()) {
                    $optionIds[] = $item['option_id'];
                }
            }
        }

        return $optionIds;
    }
}
