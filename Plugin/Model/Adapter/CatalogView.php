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

namespace Mavenbird\Shopbybrand\Plugin\Model\Adapter;

use Magento\Framework\App\RequestInterface;

class CatalogView
{
    /**
     * Requests
     *
     * @var [type]
     */
    protected $_request;

    /**
     * Construct
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->_request = $request;
    }

    /**
     * After Is Applicable
     *
     * @param \Magento\CatalogSearch\Model\Adapter\Aggregation\Checker\Query\CatalogView $subject
     * @param [type] $result
     * @return void
     */
    public function afterIsApplicable(
        \Magento\CatalogSearch\Model\Adapter\Aggregation\Checker\Query\CatalogView $subject,
        $result
    ) {
        if ($this->_request->getFullActionName() === 'mbbrand_index_view') {
            return true;
        }

        return $result;
    }
}
