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
 * Class AddBrandProductStructuredData
 * @package Mavenbird\Shopbybrand\Observer
 */
class AddBrandProductStructuredData implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $_dataHelper;

    /**
     * AddBrandProductStructuredData constructor.
     *
     * @param Data $dataHelper
     */
    public function __construct(Data $dataHelper)
    {
        $this->_dataHelper = $dataHelper;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $objectStructuredData  = $observer->getData('structured_data');
        $productStructuredData = $objectStructuredData->getMpdata();
        $brandText             = $this->_dataHelper->getBrandTextFromProduct();
        if ($brandText) {
            $productStructuredData['brand']['@type'] = 'Thing';
            $productStructuredData['brand']['name']  = $brandText;
            $objectStructuredData->setMpdata($productStructuredData);
        }
    }
}
