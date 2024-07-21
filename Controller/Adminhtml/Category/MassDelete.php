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

use Exception;
use Magento\Framework\View\Result\PageFactory;
use Mavenbird\Shopbybrand\Controller\Adminhtml\Category;
use Mavenbird\Shopbybrand\Model\Category as Categories;

class MassDelete extends Category
{
    /**
     * Category
     *
     * @var [type]
     */
    protected $categories;
    
    /**
     * Construct
     *
     * @param Categories $categories
     */
    public function __construct(
        Categories $categories
    ) {
        $this->categories = $categories;
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $ids = $this->getRequest()->getParam('cat_id');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addErrorMessage(__('Please select category.'));
        } else {
            $numOfSuccess = 0;
            foreach ($ids as $id) {
                try {
                    /** @var \Mavenbird\Shopbybrand\Model\Category $cat */
                    $cat = $this->categories->load($id);
                    $cat->delete();
                    $numOfSuccess++;
                } catch (Exception $e) {
                    $this->messageManager->addErrorMessage(__('Cannot delete category with ID %1', $id));
                }
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $numOfSuccess));
        }

        $this->_redirect('*/*/');
    }
}
