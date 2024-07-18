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

namespace Mavenbird\Shopbybrand\Plugin\Block\Adminhtml\Attribute\Edit;

use Mavenbird\Shopbybrand\Model\BrandFactory;

/**
 * Class Options
 *
 * @package Mavenbird\Shopbybrand\Plugin\Block\Adminhtml\Attribute\Edit
 */
class Options
{
    /**
     * @var BrandFactory
     */
    protected $_brandFactory;

    /**
     * Options constructor.
     *
     * @param BrandFactory $brandFactory
     */
    public function __construct(BrandFactory $brandFactory)
    {
        $this->_brandFactory = $brandFactory;
    }

    /**
     * @param $optionId
     *
     * @return string
     */
    public function getIsFeatured($optionId)
    {
        $brands = $this->_brandFactory->create()->loadByOption($optionId);

        return $brands->getIsFeatured() ? 'FEATURED' : 'SIMPLE';
    }

    /**
     * @param \Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Options $options
     * @param $result
     *
     * @return mixed
     */
    public function afterGetOptionValues(\Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Options $options, $result)
    {
        foreach ($result as $item) {
            $item->setIsFeature($this->getIsFeatured($item->getId()));
        }

        return $result;
    }
}
