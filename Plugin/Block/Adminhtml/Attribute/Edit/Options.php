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

namespace Mavenbird\Shopbybrand\Plugin\Block\Adminhtml\Attribute\Edit;

use Mavenbird\Shopbybrand\Model\BrandFactory;

class Options
{
    /**
     * Factory for Brand
     *
     * @var [type]
     */
    protected $_brandFactory;

    /**
     * Construct
     *
     * @param BrandFactory $brandFactory
     */
    public function __construct(BrandFactory $brandFactory)
    {
        $this->_brandFactory = $brandFactory;
    }

    /**
     * Is Featured
     *
     * @param [type] $optionId
     * @return void
     */
    public function getIsFeatured($optionId)
    {
        $brands = $this->_brandFactory->create()->loadByOption($optionId);

        return $brands->getIsFeatured() ? 'FEATURED' : 'SIMPLE';
    }

    /**
     * After Get Option Values
     *
     * @param \Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Options $options
     * @param [type] $result
     * @return void
     */
    public function afterGetOptionValues(\Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Options $options, $result)
    {
        foreach ($result as $item) {
            $item->setIsFeature($this->getIsFeatured($item->getId()));
        }

        return $result;
    }
}
