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

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mavenbird\Shopbybrand\Helper\Data;

class CheckRewritePath implements ObserverInterface
{
    /**
     * Data
     *
     * @var [type]
     */
    protected $_helper;

    /**
     * Construct
     *
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->_helper = $helper;
    }

    /**
     * Execute
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $object = $observer->getEvent()->getObject();

        $pathInfo  = $object->getData('pathInfo');
        $routePath = explode('/', $pathInfo);

        if ((count($routePath) === 2)
            && (array_shift($routePath) === $this->_helper->getRoute())
        ) {
            $object->setData('rewrite', true);
        }
    }
}
