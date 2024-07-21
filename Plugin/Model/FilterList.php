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

namespace Mavenbird\Shopbybrand\Plugin\Model;

use Magento\Framework\App\RequestInterface;
use Mavenbird\Shopbybrand\Helper\Data;
use Mavenbird\Shopbybrand\Model\Layer\Filter\Attribute;
use Magento\Catalog\Model\Layer\FilterList as Subject;

class FilterList
{
    /**
     * Data
     *
     * @var [type]
     */
    protected $helper;

    /**
     * Requests
     *
     * @var [type]
     */
    protected $request;

    /**
     * Attributes Filter
     *
     * @var [type]
     */
    protected $attributeFilter;

    /**
     * Construct
     *
     * @param RequestInterface $request
     * @param Data $helper
     * @param Attribute $attributeFilter
     */
    public function __construct(
        RequestInterface $request,
        Data $helper,
        Attribute $attributeFilter
    ) {
        $this->helper        = $helper;
        $this->request       = $request;
        $this->attributeFilter = $attributeFilter;
    }

    /**
     * After get filters
     *
     * @param \Magento\Catalog\Model\Layer\FilterList $subject
     * @param [type] $result
     * @return void
     */
    public function afterGetFilters(\Magento\Catalog\Model\Layer\FilterList $subject, $result)
    {
        if ($this->request->getFullActionName() !== 'mmbrand_index_view') {
            return $result;
        }

        $brandAttCode = $this->helper->getAttributeCode();
        foreach ($result as $key => $filter) {
            if ($filter->getRequestVar() === $brandAttCode) {
                $filterBrand  = clone $this->attributeFilter;
                $filterBrand->setData('attribute_model', $filter->getAttributeModel());
                $filterBrand->setLayer($filter->getLayer());
                $result[$key] = $filterBrand;
                break;
            }
        }

        return $result;
    }
}
