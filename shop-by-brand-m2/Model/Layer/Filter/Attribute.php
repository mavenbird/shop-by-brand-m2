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

namespace Mavenbird\Shopbybrand\Model\Layer\Filter;

use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;

class Attribute extends AbstractFilter
{
    /**
     * Apply attribute option filter to product collection
     *
     * @param RequestInterface $request
     *
     * @return $this|AbstractFilter
     * @throws LocalizedException
     */
    public function apply(RequestInterface $request)
    {
        $attributeValue = $request->getParam($this->_requestVar);
        if (empty($attributeValue)) {
            return $this;
        }
        $attribute = $this->getAttributeModel();
        $this->getLayer()
            ->getProductCollection()
            ->addFieldToFilter($attribute->getAttributeCode(), $attributeValue);

        return $this;
    }
}
