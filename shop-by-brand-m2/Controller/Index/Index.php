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

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Mavenbird\Shopbybrand\Helper\Data;

class Index extends Action
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
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Data $helper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->helper            = $helper;

        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        return $this->helper->isEnabled() ? $this->resultPageFactory->create() : $this->_redirect('noroute');
    }
}
