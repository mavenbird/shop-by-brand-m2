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

namespace Mavenbird\Shopbybrand\Block\Adminhtml\Category\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('brand_category_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Category'));
    }
}
