<?php
/**
 * Mavenbird Technologies Private Limited
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mavenbird.com/Mavenbird-Module-License.txt
 *
 * =================================================================
 *
 * @category   Mavenbird
 * @package    Mavenbird_Shopbybrand
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */

namespace Mavenbird\Shopbybrand\Observer;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config;
use Mavenbird\Shopbybrand\Helper\Data;

class NoindexPagination implements ObserverInterface
{
    /**
     * Data
     *
     * @var [type]
     */
    protected $_helper;

    /**
     * Requests
     *
     * @var [type]
     */
    protected $request;

    /**
     * Config
     *
     * @var [type]
     */
    protected $_corePageConfig;

    /**
     * Construct
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
     * Execute
     *
     * @param Observer $observer
     * @return void
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
