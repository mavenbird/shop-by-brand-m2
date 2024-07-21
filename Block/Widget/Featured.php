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

use Magento\Framework\View\Element\Template\Context;
use Mavenbird\Shopbybrand\Helper\Data as Helper;
use Mavenbird\Shopbybrand\Model\BrandFactory;

class Featured extends AbstractBrand
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
     * @param BrandFactory $brandFactory
     * @param Helper $helper
     */
    public function __construct(
        Context $context,
        BrandFactory $brandFactory,
        Helper $helper
    ) {
        $this->_brandFactory = $brandFactory;

        parent::__construct($context, $helper);
    }

    /**
     * Collection
     *
     * @return void
     */
    public function getCollection()
    {
        $featureBrands = [];
        $collection    = $this->_brandFactory->create()->getBrandCollection();
        foreach ($collection as $brand) {
            if ($brand->getIsFeatured()) {
                $featureBrands[] = $brand;
            }
        }

        return $featureBrands;
    }
}
