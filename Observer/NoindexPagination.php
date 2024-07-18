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

namespace Mavenbird\Shopbybrand\Observer;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config;
use Mavenbird\Shopbybrand\Helper\Data;

/**
 * Class NoindexPagination
 *
 * @package Mavenbird\Shopbybrand\Observer
 */
class NoindexPagination implements ObserverInterface
{
    /**
     * @type Data
     */
    protected $_helper;

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var Config $_corePageConfig ;
     */
    protected $_corePageConfig;

    /**
     * NoindexPagination constructor.
     *
     * @param Http $request
     * @param Config $_corePageConfig
     * @param Data $helper
     */
    public function __construct(
        Http $request,
        Config $_corePageConfig,
        Data $helper
    ) {
        $this->_corePageConfig = $_corePageConfig;
        $this->_helper         = $helper;
        $this->request         = $request;
    }

    /**
     * @param Observer $observer
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(Observer $observer)
    {
        $actionName = $this->request->getFullActionName();
        if ($actionName === 'mmbrand_index_view'
            && $this->_helper->getModuleConfig('brand_seo/seo_pages')
            && $this->request->getParam('p')
        ) {
            $this->_corePageConfig->setRobots('NOINDEX,FOLLOW');
        }
    }
}
