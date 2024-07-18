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

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\Product\Attribute\Repository;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Mavenbird\Shopbybrand\Helper\Data;

/**
 * Class View
 * @package Mavenbird\Shopbybrand\Controller\Adminhtml\Attribute
 */
class View extends Action
{
    /**
     * @type Data
     */
    protected $_brandHelper;

    /**
     * @type Repository
     */
    protected $_productAttributeRepository;

    /**
     * View constructor.
     *
     * @param Context $context
     * @param Repository $productRepository
     * @param Data $brandHelper
     */
    public function __construct(
        Context $context,
        Repository $productRepository,
        Data $brandHelper
    ) {
        $this->_brandHelper                = $brandHelper;
        $this->_productAttributeRepository = $productRepository;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        $attributeCode = $this->_brandHelper->getAttributeCode();
        try {
            $attribute = $this->_productAttributeRepository->get($attributeCode);

            $this->_forward('edit', 'product_attribute', 'catalog', ['attribute_id' => $attribute->getId()]);
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('You have to choose an attribute as brand in configuration.'));
            $this->_redirect('adminhtml/system_config/edit', ['section' => 'shopbybrand']);
        }
    }
}
