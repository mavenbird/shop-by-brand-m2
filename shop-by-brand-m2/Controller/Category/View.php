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

namespace Mavenbird\Shopbybrand\Controller\Category;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Mavenbird\Shopbybrand\Helper\Data;
use Mavenbird\Shopbybrand\Model\CategoryFactory as BrandCategoryModelFactory;
use Mavenbird\Shopbybrand\Model\ResourceModel\Category as BrandCategoryResourceModel;

class View extends Action
{
    /**
     * Factory for Result Page
     *
     * @var [type]
     */
    protected $resultPageFactory;

    /**
     * Factory for Forward Result
     *
     * @var [type]
     */
    protected $resultForwardFactory;

    /**
     * Data
     *
     * @var [type]
     */
    protected $helper;

    /**
     * Factory for Category
     *
     * @var [type]
     */
    protected $categoryFactory;

    /**
     * Resource categories
     *
     * @var [type]
     */
    protected $categoryResource;

    /**
     * Registry
     *
     * @var [type]
     */
    private $coreRegistry;

    /**
     * Constructor
     *
     * @param Context $context
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     * @param BrandCategoryModelFactory $categoryFactory
     * @param BrandCategoryResourceModel $categoryResource
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        BrandCategoryModelFactory $categoryFactory,
        BrandCategoryResourceModel $categoryResource,
        Data $helper
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory    = $resultPageFactory;
        $this->coreRegistry         = $coreRegistry;
        $this->categoryFactory      = $categoryFactory;
        $this->categoryResource     = $categoryResource;
        $this->helper               = $helper;

        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('cat_id');
        if ($id && $this->helper->isEnabled()) {
            $categoryModel = $this->categoryFactory->create();
            $this->categoryResource->load($categoryModel, $id);
            $this->coreRegistry->register('current_brand_category', $categoryModel);

            return $this->resultPageFactory->create();
        }

        return $this->resultForwardFactory->create()->forward('noroute');
    }
}
