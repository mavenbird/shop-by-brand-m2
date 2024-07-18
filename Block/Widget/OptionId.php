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

namespace Mavenbird\Shopbybrand\Block\Widget;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection;
use Magento\Framework\View\Element\Template\Context;
use Mavenbird\Shopbybrand\Helper\Data as Helper;
use Mavenbird\Shopbybrand\Model\BrandFactory;

/**
 * Class OptionId
 *
 * @package Mavenbird\Shopbybrand\Block\Brand
 */
class OptionId extends AbstractBrand
{
    /**
     * @var string
     */
    protected $_template = 'Mavenbird_Shopbybrand::widget/brandlist.phtml';

    /**
     * @type BrandFactory
     */
    protected $_brandFactory;

    /**
     * OptionId constructor.
     *
     * @param Context $context
     * @param Helper $helper
     * @param BrandFactory $brandFactory
     */
    public function __construct(
        Context $context,
        Helper $helper,
        BrandFactory $brandFactory
    ) {
        $this->_brandFactory = $brandFactory;

        parent::__construct($context, $helper);
    }

    /**
     * @return string
     */
    public function getOptionIds()
    {
        $str = $this->getData('option_id');
        if (strpos($str, ',') !== false) {
            $str = str_replace(',', ' ', $str);
        } elseif (strpos($str, '-') !== false) {
            $str = str_replace('-', ' ', $str);
        }
        $optionIDs = explode(' ', $str);

        return implode(',', array_diff($optionIDs, ['']));
    }

    /**
     * get brand by option IDs
     *
     * @return Collection
     */
    public function getCollection()
    {
        $collection = $this->_brandFactory->create()->getBrandCollection(
            null,
            ['main_table.option_id' => ['in' => $this->getOptionIds()]]
        );

        return $collection;
    }
}
