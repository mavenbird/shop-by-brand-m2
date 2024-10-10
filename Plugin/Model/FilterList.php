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

namespace Mavenbird\Shopbybrand\Plugin\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\ObjectManagerInterface;
use Mavenbird\Shopbybrand\Helper\Data;
use Mavenbird\Shopbybrand\Model\Layer\Filter\Attribute; 

class FilterList
{
    /**
     * Data helper
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
     * Objects manager
     *
     * @var [type]
     */
    protected $objectManager;

    /**
     * Constructor
     *
     * @param ObjectManagerInterface $objectManager
     * @param RequestInterface $request
     * @param Data $helper
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        RequestInterface $request,
        Data $helper
    ) {
        $this->objectManager = $objectManager;
        $this->helper        = $helper;
        $this->request       = $request;
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
        if ($this->request->getFullActionName() !== 'mbbrand_index_view') {
            return $result;
        }

        $brandAttCode = $this->helper->getAttributeCode();
        foreach ($result as $key => $filter) {
            if ($filter->getRequestVar() === $brandAttCode) {
                $filterBrand  = $this->objectManager->create(
                    Attribute::class,
                    ['data' => ['attribute_model' => $filter->getAttributeModel()], 'layer' => $filter->getLayer()]
                );
                $result[$key] = $filterBrand;
                break;
            }
        }

        return $result;
    }
}
