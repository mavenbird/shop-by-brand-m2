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

namespace Mavenbird\Shopbybrand\Controller\Adminhtml\Attribute;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Catalog\Model\Product\Attribute\Repository;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Mavenbird\Shopbybrand\Helper\Data as HelperData;
use Mavenbird\Shopbybrand\Model\BrandFactory;

class Update extends Action
{
    /**
     * Helper
     *
     * @var [type]
     */
    protected $_jsonHelper;

    /**
     * Brands Helper
     *
     * @var [type]
     */
    protected $_brandHelper;

    /**
     * Repository for Product Attribute
     *
     * @var [type]
     */
    protected $_productAttributeRepository;

    /**
     * Factory for Brand
     *
     * @var [type]
     */
    protected $_brandFactory;

    /**
     * Factory for Result Page
     *
     * @var [type]
     */
    protected $_resultPageFactory;

    /**
     * Stores Manager
     *
     * @var [type]
     */
    protected $_storeManager;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Data $jsonHelper
     * @param StoreManagerInterface $storeManager
     * @param Repository $productRepository
     * @param PageFactory $resultPageFactory
     * @param HelperData $brandHelper
     * @param BrandFactory $brandFactory
     */
    public function __construct(
        Context $context,
        Data $jsonHelper,
        StoreManagerInterface $storeManager,
        Repository $productRepository,
        PageFactory $resultPageFactory,
        HelperData $brandHelper,
        BrandFactory $brandFactory
    ) {
        parent::__construct($context);

        $this->_jsonHelper                 = $jsonHelper;
        $this->_brandHelper                = $brandHelper;
        $this->_productAttributeRepository = $productRepository;
        $this->_brandFactory               = $brandFactory;
        $this->_resultPageFactory          = $resultPageFactory;
        $this->_storeManager               = $storeManager;
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $result        = ['success' => false];
        $optionId      = (int) $this->getRequest()->getParam('id');
        $attributeCode = $this->_brandHelper->getAttributeCode();
        $options       = $this->_productAttributeRepository->get($attributeCode)->getOptions();
        foreach ($options as $option) {
            if ($option->getValue() == $optionId) {
                $result = ['success' => true];
                break;
            }
        }

        if ($result['success']) {
            $store = $this->getRequest()->getParam('store') ?: 0;
            $brand = $this->_brandFactory->create()->loadByOption($optionId, $store);
            if (!$brand->getUrlKey()) {
                $brand->setUrlKey($this->_brandHelper->formatUrlKey($brand->getDefaultValue()));

                $defaultBlock = $this->_brandHelper->getBrandDetailConfig('default_block', $store);
                if ($defaultBlock) {
                    $brand->setStaticBlock($defaultBlock);
                }
            }

            /** @var Page $resultPage */
            $resultPage = $this->_resultPageFactory->create();

            $result['html']     = $resultPage->getLayout()->getBlock('brand.attribute.html')
                ->setOptionData($brand->getData())
                ->toHtml();
            $result['switcher'] = $resultPage->getLayout()->getBlock('brand.store.switcher')
                ->toHtml();
        } else {
            $result['message'] = __('Attribute option does not exist.');
        }

        $this->getResponse()->representJson($this->_jsonHelper->jsonEncode($result));
    }
}
