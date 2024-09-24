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
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Mavenbird\Shopbybrand\Controller\Adminhtml\Category;

class Index extends Category
{
    /**
     * Factory for Result Page
     *
     * @var [type]
     */
    protected $resultPageFactory;

    /**
     * Result pages
     *
     * @var [type]
     */
    protected $resultPage;

    /**
     * Construct
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $this->resultPage = $this->resultPageFactory->create();
        $this->resultPage->setActiveMenu('Mavenbird_Shopbybrand::categories');
        $this->resultPage->getConfig()->getTitle()->prepend(__('Brand Categories'));

        return $this->resultPage;
    }
}
