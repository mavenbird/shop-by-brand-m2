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
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Filter\FilterManager;
use Mavenbird\Shopbybrand\Controller\Adminhtml\Category;
use Mavenbird\Shopbybrand\Model\CategoryFactory;
use RuntimeException;

class Save extends Category
{
    /**
     * Factory for Category
     *
     * @var [type]
     */
    protected $categoryFactory;

    /**
     * Filters
     *
     * @var [type]
     */
    protected $_filter;

    /**
     * Constructor
     *
     * @param Context $context
     * @param CategoryFactory $categoryFactory
     * @param FilterManager $filter
     */
    public function __construct(
        Context $context,
        CategoryFactory $categoryFactory,
        FilterManager $filter
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->_filter         = $filter;

        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $catId = $this->getRequest()->getParam('cat_id');
        $data  = $this->getRequest()->getParams();
        if ($data) {
            $this->prepareData($data);
            $cat = $this->categoryFactory->create();
            if ($catId) {
                $cat->load($catId);
            }

            $errors = $this->validateData($data);
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->messageManager->addErrorMessage($error);
                }

                return $catId ? $this->_redirect('*/*/edit', ['cat_id' => $catId]) : $this->_redirect('*/*/new');
            }

            $cat->setData($data);

            try {
                $cat->save();
                $this->messageManager->addSuccessMessage(__('The category has been saved successfully.'));
                $this->_session->setProductFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['cat_id' => $cat->getId()]);

                    return;
                }

                $this->_redirect('*/*/');

                return;
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the category.'));
            }

            $this->_redirect('*/*/edit', ['cat_id' => $this->getRequest()->getParam('cat_id')]);

            return;
        }
        $this->_redirect('*/*/');
    }

    /**
     * Prepare Data
     *
     * @param [type] $data
     * @return void
     */
    private function prepareData(&$data)
    {
        $data['url_key'] = $this->formatUrlKey($data['url_key']);

        $this->_getSession()->setProductFormData($data);

        return $data;
    }

    /**
     * Format Url Key
     *
     * @param [type] $str
     * @return void
     */
    public function formatUrlKey($str)
    {
        return $this->_filter->translitUrl($str);
    }

    /**
     * Validate Data
     *
     * @param array $data
     * @return void
     */
    public function validateData(array $data)
    {
        $errors = [];

        if (!isset($data['name'])) {
            $errors[] = __('Please enter the category name.');
        }

        if (isset($data['url_key'])) {
            $pages = $this->categoryFactory->create()->getCollection()
                ->addFieldToFilter('url_key', $data['url_key']);
            if ($pages->getSize()) {
                if (isset($data['cat_id'])) {
                    foreach ($pages as $page) {
                        if ($page->getId() != $data['cat_id']) {
                            $errors[] = __('The url key "%1" has been used.', $data['url_key']);
                        }
                    }
                } else {
                    $errors[] = __('The url key "%1" has been used.', $data['url_key']);
                }
            }
        } else {
            $errors[] = __('Please enter the category url key.');
        }

        return $errors;
    }

    /**
     * Get Data
     *
     * @return void
     */
    public function getData()
    {
        return $this->getRequest()->getParams();
    }
}
