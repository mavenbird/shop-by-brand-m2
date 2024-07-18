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

namespace Mavenbird\Shopbybrand\Block\Sidebar;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection;
use Mavenbird\Shopbybrand\Block\Brand;

/**
 * Class Featured
 *
 * @package Mavenbird\Shopbybrand\Block\Sidebar
 */
class Featured extends Brand
{
    /**
     * Default feature template
     *
     * @type string
     */
    protected $_template = 'Mavenbird_Shopbybrand::sidebar/featured.phtml';

    /**
     * Default title sidebar featured brand
     */
    const TITLE = 'Featured Brand';

    /**
     * @return string
     */
    public function includeCssLib()
    {
        $cssFiles = ['Mavenbird_Shopbybrand::css/owl.carousel.css', 'Mavenbird_Shopbybrand::css/owl.theme.css'];
        $template = '<link rel="stylesheet" type="text/css" media="all" href="%s">' . "\n";
        $result   = '';
        foreach ($cssFiles as $file) {
            $asset  = $this->_assetRepo->createAsset($file);
            $result .= sprintf($template, $asset->getUrl());
        }

        return $result;
    }

    /**
     * @return mixed|string
     */
    public function getFeatureTitle()
    {
        return $this->helper->getModuleConfig('sidebar/feature/title') ?: self::TITLE;
    }

    /**
     * @return mixed
     */
    public function showTitle()
    {
        return $this->helper->getModuleConfig('sidebar/feature/show_title');
    }

    /**
     * @return Collection|mixed
     */
    public function getFeaturedBrand()
    {
        $featureBrands = [];
        if (!$this->helper->getModuleConfig('sidebar/feature/enable')) {
            return $featureBrands;
        }

        $collection = $this->getCollection('char');
        foreach ($collection as $brand) {
            if ($brand->getIsFeatured()) {
                $featureBrands[] = $brand;
            }
        }

        return $featureBrands;
    }
}
