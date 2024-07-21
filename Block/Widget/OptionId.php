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

namespace Mavenbird\Shopbybrand\Block\Widget;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection;
use Magento\Framework\View\Element\Template\Context;
use Mavenbird\Shopbybrand\Helper\Data as Helper;
use Mavenbird\Shopbybrand\Model\BrandFactory;

class OptionId extends AbstractBrand
{
    /**
     * Templates
     *
     * @var string
     */
    protected $_template = 'Mavenbird_Shopbybrand::widget/brandlist.phtml';

    /**
     * Factory for Brand
     *
     * @var [type]
     */
    protected $_brandFactory;

    /**
     * Constructor
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
     * Option Ids
     *
     * @return void
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
     * Collection
     *
     * @return void
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
