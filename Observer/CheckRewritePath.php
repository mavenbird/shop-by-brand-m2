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

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mavenbird\Shopbybrand\Helper\Data;

/**
 * Class CheckRewritePath
 * @package Mavenbird\Shopbybrand\Observer
 */
class CheckRewritePath implements ObserverInterface
{
    /**
     * @type Data
     */
    protected $_helper;

    /**
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->_helper = $helper;
    }

    /**
     * @param Observer $observer
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
