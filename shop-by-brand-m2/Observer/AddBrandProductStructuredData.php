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

class AddBrandProductStructuredData implements ObserverInterface
{
    /**
     * Helper
     *
     * @var [type]
     */
    protected $_dataHelper;

    /**
     * Construct
     *
     * @param Data $dataHelper
     */
    public function __construct(Data $dataHelper)
    {
        $this->_dataHelper = $dataHelper;
    }

    /**
     * Execute
     *
     * @param Observer $observer
     * @return void
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
