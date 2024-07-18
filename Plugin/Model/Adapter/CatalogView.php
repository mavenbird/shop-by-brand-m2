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

namespace Mavenbird\Shopbybrand\Plugin\Model\Adapter;

use Magento\Framework\App\RequestInterface;

/**
 * Class Preprocessor
 * @package Mavenbird\LayeredNavigation\Model\Plugin\Adapter
 */
class CatalogView
{
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * CatalogView constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->_request = $request;
    }

    /**
     * @param \Magento\CatalogSearch\Model\Adapter\Aggregation\Checker\Query\CatalogView $subject
     * @param $result
     *
     * @return bool
     */
    public function afterIsApplicable(
        \Magento\CatalogSearch\Model\Adapter\Aggregation\Checker\Query\CatalogView $subject,
        $result
    ) {
        if ($this->_request->getFullActionName() === 'mmbrand_index_view') {
            return true;
        }

        return $result;
    }
}
