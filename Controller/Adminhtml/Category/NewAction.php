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

namespace Mavenbird\Shopbybrand\Controller\Adminhtml\Category;

use Mavenbird\Shopbybrand\Controller\Adminhtml\Category;

/**
 * Class NewAction
 * @package Mavenbird\Shopbybrand\Controller\Adminhtml\Category
 */
class NewAction extends Category
{
    /**
     * Forward to edit page
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
