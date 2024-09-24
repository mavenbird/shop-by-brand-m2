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

namespace Mavenbird\Shopbybrand\Block\Adminhtml\Category\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Exception\LocalizedException;
use Mavenbird\Shopbybrand\Block\Adminhtml\Form\Renderer\BrandCategory;

class Brand extends Generic implements TabInterface
{
    /**
     * Prepare Form
     *
     * @return void
     */
    protected function _prepareForm()
    {
        //todo create default attributes table
        /** @var \Mavenbird\Shopbybrand\Model\Category $model */
        $model = $this->_coreRegistry->registry('current_brand_category');
        $form  = $this->_formFactory->create();
        $form->setHtmlIdPrefix('cat_');

        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('Brands'),
            'class'  => 'fieldset-wide'
        ]);
        $field    = $fieldset->addField('brands', 'text', [
            'label' => __('Brands'),
            'title' => __('Brands')
        ]);

        /** @var RendererInterface $renderer */
        $renderer = $this->getLayout()->createBlock(BrandCategory::class);
        $field->setRenderer($renderer);

        $form->addValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Brands');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Brands');
    }

    /**
     * @inheritdoc
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isHidden()
    {
        return false;
    }
}
