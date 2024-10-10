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

namespace Mavenbird\Shopbybrand\Block\Adminhtml\Category;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\Product;
use Magento\Config\Model\Config\Source\Enabledisable;
use Magento\Framework\DataObject;
use Magento\Store\Model\System\Store;
use Mavenbird\Shopbybrand\Model\ResourceModel\Category\Collection;

class Grid extends Extended
{
    /**
     * Factory for Collection
     *
     * @var [type]
     */
    protected $_collectionFactory;

    /**
     * Bool Options
     *
     * @var [type]
     */
    protected $_booleanOptions;

    /**
     * Store
     *
     * @var [type]
     */
    protected $_systemStore;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Data $backendHelper
     * @param Enabledisable $booleanOptions
     * @param Store $systemStore
     * @param Collection $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        Enabledisable $booleanOptions,
        Store $systemStore,
        Collection $collectionFactory,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_booleanOptions    = $booleanOptions;
        $this->_systemStore       = $systemStore;

        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('categoryGrid');
        $this->setDefaultSort('cat_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }

    /**
     * Prepare Collection
     *
     * @return void
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->load();
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function _prepareColumns()
    {
        $this->addColumn('cat_id', [
            'header'           => __('ID'),
            'type'             => 'number',
            'index'            => 'cat_id',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
        ]);

        $this->addColumn('name', [
            'header' => __('Name'),
            'index'  => 'name'
        ]);

        $this->addColumn('route', [
            'header' => __('URL Key'),
            'index'  => 'url_key'
        ]);

        $this->addColumn('status', [
            'header'  => __('Status'),
            'index'   => 'status',
            'type'    => 'options',
            'options' => $this->getStatusOptions(),
        ]);

        if (!$this->_storeManager->isSingleStoreMode()) {
            $this->addColumn('store_ids', [
                'header'                    => __('Store View'),
                'index'                     => 'store_ids',
                'type'                      => 'store',
                'store_all'                 => true,
                'store_view'                => true,
                'sortable'                  => false,
                'filter_condition_callback' => [$this, '_filterStoreCondition']
            ]);
        }

        $this->addColumn('edit', [
            'header'           => __('Edit'),
            'type'             => 'action',
            'getter'           => 'getId',
            'actions'          => [
                [
                    'caption' => __('Edit'),
                    'url'     => [
                        'base' => '*/*/edit'
                    ],
                    'field'   => 'cat_id'
                ]
            ],
            'filter'           => false,
            'sortable'         => false,
            'index'            => 'stores',
            'header_css_class' => 'col-action',
            'column_css_class' => 'col-action'
        ]);

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

    /**
     * Filter Store Condition
     *
     * @param [type] $collection
     * @param DataObject $column
     * @return void
     */
    protected function _filterStoreCondition($collection, DataObject $column)
    {
        if (!($value = $column->getFilter()->getValue())) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    /**
     * Get Status Option
     *
     * @return array
     */
    protected function getStatusOptions()
    {
        $options = [];
        foreach ($this->_booleanOptions->toOptionArray() as $option) {
            $options[$option['value']] = $option['label'];
        }

        return $options;
    }

    /**
     * Prepare Mass Action
     *
     * @return void
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('cat_id');
        $this->getMassactionBlock()->setFormFieldName('cat_id');

        $this->getMassactionBlock()->addItem('delete', [
            'label'   => __('Delete'),
            'url'     => $this->getUrl('mbbrand/*/massDelete'),
            'confirm' => __('Are you sure?')
        ]);

        return $this;
    }

    /**
     * Grid Url
     *
     * @return void
     */
    public function getGridUrl()
    {
        return $this->getUrl('mbbrand/category/index', ['_current' => true]);
    }

    /**
     * Row Url
     *
     * @param [type] $row
     * @return void
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['cat_id' => $row->getId()]);
    }
}
