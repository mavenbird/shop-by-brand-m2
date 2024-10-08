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

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Mavenbird\Shopbybrand\Controller\Adminhtml\Category;
use Mavenbird\Shopbybrand\Helper\Data as HelperData;
use Mavenbird\Shopbybrand\Model\CategoryFactory;

class Edit extends Category
{
    /**
     * Data
     *
     * @var [type]
     */
    protected $helper;

    /**
     * Factory for Result Page
     *
     * @var [type]
     */
    protected $resultPageFactory;

    /**
     * Factory for Category
     *
     * @var [type]
     */
    protected $categoryFactory;

    /**
     * Core registry
     *
     * @var [type]
     */
    protected $registry;

    /**
     * Helper
     *
     * @var [type]
     */
    protected $jsonHelper;

    /**
     * Constructor
     *
     * @param HelperData $data
     * @param Data $jsonHelper
     * @param PageFactory $pageFactory
     * @param Registry $registry
     * @param CategoryFactory $categoryFactory
     * @param Context $context
     */
    public function __construct(
        HelperData $data,
        Data $jsonHelper,
        PageFactory $pageFactory,
        Registry $registry,
        CategoryFactory $categoryFactory,
        Context $context
    ) {
        $this->helper            = $data;
        $this->jsonHelper        = $jsonHelper;
        $this->registry          = $registry;
        $this->resultPageFactory = $pageFactory;
        $this->categoryFactory   = $categoryFactory;

        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $cat = $this->categoryFactory->create();
        if ($id = $this->getRequest()->getParam('cat_id')) {
            $cat->load($id);
            if (!$cat->getId()) {
                $this->messageManager->addErrorMessage(__('The category does not exist.'));

                return $this->_redirect('*/*/');
            }
        }

        //Set entered data if was error when we do save
        $data = $this->_session->getProductFormData(true);
        if (!empty($data)) {
            $cat->setData($data);
        }

        $this->registry->register('current_brand_category', $cat);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set($cat->getId() ? $cat->getName() : __('New Category'));

        return $resultPage;
    }
}
