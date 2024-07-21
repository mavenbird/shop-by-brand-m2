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

namespace Mavenbird\Shopbybrand\Controller\Index;

use Exception;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Mavenbird\Shopbybrand\Helper\Data as Helper;
use Mavenbird\Shopbybrand\Model\Brand;
use Mavenbird\Shopbybrand\Model\BrandFactory;

class View extends Action
{
    /**
     * Factory for Result Page
     *
     * @var [type]
     */
    protected $resultPageFactory;

    /**
     * Data
     *
     * @var [type]
     */
    protected $helper;

    /**
     * Factory for Brand
     *
     * @var [type]
     */
    protected $_brandFactory;

    /**
     * Repository for Category
     *
     * @var [type]
     */
    protected $categoryRepository;

    /**
     * Stores Manager
     *
     * @var [type]
     */
    protected $_storeManager;

    /**
     * Factory for Forward Result
     *
     * @var [type]
     */
    protected $resultForwardFactory;

    /**
     * Registry
     *
     * @var [type]
     */
    protected $_coreRegistry;

    /**
     * Helper
     *
     * @var [type]
     */
    protected $_jsonHelper;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Helper $helper
     * @param BrandFactory $brandFactory
     * @param Registry $coreRegistry
     * @param ForwardFactory $resultForwardFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param StoreManagerInterface $storeManager
     * @param Data $jsonHelper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Helper $helper,
        BrandFactory $brandFactory,
        Registry $coreRegistry,
        ForwardFactory $resultForwardFactory,
        CategoryRepositoryInterface $categoryRepository,
        StoreManagerInterface $storeManager,
        Data $jsonHelper
    ) {
        $this->resultPageFactory    = $resultPageFactory;
        $this->_brandFactory        = $brandFactory;
        $this->helper               = $helper;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_coreRegistry        = $coreRegistry;
        $this->_jsonHelper          = $jsonHelper;
        $this->_storeManager        = $storeManager;
        $this->categoryRepository   = $categoryRepository;

        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        if ($this->helper->isEnabled() && $brand = $this->_initBrand()) {
            $this->getRequest()->setParam($this->helper->getAttributeCode(), $brand->getId());
            $page = $this->resultPageFactory->create();
            $page->getConfig()->addBodyClass('page-products');
            $imageUrl = $this->helper->getBrandImageUrl($brand);
            $brand->setImage($imageUrl);

            if ($this->helper->showQuickView() && $this->getRequest()->isAjax()) {
                $brand->setShortDescription($this->helper->getBrandDescription($brand, true));
                $status = 'ok';
                $layout = $page->getLayout();
                $result = [
                    'products'   => $layout->getBlock('brand.category.products.list')->toHtml(),
                    'navigation' => $layout->getBlock('catalog.leftnav')->toHtml(),
                    'brand'      => $brand->getData(),
                    'status'     => $status
                ];

                return $this->getResponse()->representJson($this->_jsonHelper->jsonEncode($result));
            }

            return $page;
        }

        return $this->resultForwardFactory->create()->forward('noroute');
    }

    /**
     * Init Brand
     *
     * @return void
     */
    protected function _initBrand()
    {
        $urlKey = $this->getRequest()->getParam('brand_key');
        if (!$urlKey) {
            return false;
        }

        $currentBrand = false;
        try {
            $category = $this->categoryRepository->get(
                $this->_storeManager->getStore()->getRootCategoryId()
            );
            $this->_coreRegistry->register('current_category', $category);
        } catch (Exception $e) {
            return false;
        }
        $brandCollection = $this->_brandFactory->create()->getBrandCollection();
        foreach ($brandCollection as $brand) {
            if ($this->helper->processKey($brand) === $urlKey) {
                $currentBrand = $brand;
                break;
            }
        }
        if ($currentBrand) {
            $this->_coreRegistry->register('current_brand', $currentBrand);
        }

        return $currentBrand;
    }
}
