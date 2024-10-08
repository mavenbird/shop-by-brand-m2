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
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Mavenbird\Shopbybrand\Model\Config\Source\StaticBlock;

class Brand extends Generic
{
    /**
     * Block
     *
     * @var [type]
     */
    protected $staticBlock;

    /**
     * Config
     *
     * @var [type]
     */
    protected $_wysiwygConfig;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param StaticBlock $staticBlock
     * @param Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        StaticBlock $staticBlock,
        Config $wysiwygConfig,
        array $data = []
    ) {
        $this->staticBlock    = $staticBlock;
        $this->_wysiwygConfig = $wysiwygConfig;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Adding product form elements for editing attribute
     *
     * @return Generic
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $data = $this->getOptionData();
        $form = $this->_formFactory->create([
            'data' => [
                'id'            => 'brand_attribute_save',
                'action'        => $this->getUrl('mbbrand/attribute/save', ['id' => $data['brand_id']]),
                'method'        => 'post',
                'use_container' => true,
                'enctype'       => 'multipart/form-data'
            ]
        ]);

        $mainFieldset = $form->addFieldset('brand_fieldset', [
            'legend' => __('Brand Information'),
            'class'  => 'fieldset-wide'
        ]);
        $mainFieldset->addField('option_id', 'hidden', [
            'name' => 'option_id'
        ]);
        $mainFieldset->addField('store_id', 'hidden', [
            'name' => 'store_id'
        ]);
        $mainFieldset->addField('page_title', 'text', [
            'name'  => 'page_title',
            'label' => __('Page Title'),
            'title' => __('Page Title'),
            'note'  => __('If empty, option label by store will be used.')
        ]);
        $mainFieldset->addField('url_key', 'text', [
            'name'     => 'url_key',
            'label'    => __('URL Key'),
            'title'    => __('URL Key'),
            'required' => true,
        ]);
        $mainFieldset->addField('image', 'image', [
            'name'  => 'image',
            'label' => __('Brand Image'),
            'title' => __('Brand Image'),
            'note'  => __('If empty, option visual image or default image from configuration will be used.')
        ]);
        $mainFieldset->addField('is_featured', 'select', [
            'name'   => 'is_featured',
            'label'  => __('Featured'),
            'title'  => __('Featured'),
            'values' => ['1' => __('Enabled'), '0' => __('Disabled')],
            'note'   => __('If \'Enabled\', this brand will be displayed on featured brand slider.')
        ]);
        $mainFieldset->addField('short_description', 'editor', [
            'name'   => 'short_description',
            'label'  => __('Short Description'),
            'title'  => __('Short Description'),
            'config' => $this->_wysiwygConfig->getConfig(['add_variables' => false, 'add_widgets' => false])
        ]);
        $mainFieldset->addField('description', 'editor', [
            'name'   => 'description',
            'label'  => __('Description'),
            'title'  => __('Description'),
            'config' => $this->_wysiwygConfig->getConfig(['add_variables' => false, 'add_widgets' => false])
        ]);
        $mainFieldset->addField('static_block', 'select', [
            'name'   => 'static_block',
            'label'  => __('CMS Block'),
            'title'  => __('CMS Block'),
            'values' => $this->staticBlock->getOptionArray(),
        ]);

        $metaFieldset = $form->addFieldset('brand_meta_fieldset', [
            'legend' => __('Meta Information'),
            'class'  => 'fieldset-wide'
        ]);
        $metaFieldset->addField('meta_title', 'text', [
            'name'  => 'meta_title',
            'label' => __('Meta Title'),
            'title' => __('Meta Title'),
            'note'  => __('If empty, option label by store will be used.')
        ]);
        $metaFieldset->addField('meta_keywords', 'textarea', [
            'name'  => 'meta_keywords',
            'label' => __('Meta Keywords'),
            'title' => __('Meta Keywords'),
        ]);
        $metaFieldset->addField('meta_description', 'editor', [
            'name'  => 'meta_description',
            'label' => __('Meta Description'),
            'title' => __('Meta Description'),
        ]);

        $form->addValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
