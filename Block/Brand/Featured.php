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

namespace Mavenbird\Shopbybrand\Block\Brand;

use Mavenbird\Shopbybrand\Block\Brand;

class Featured extends Brand
{
    /**
     * Templates
     *
     * @var string
     */
    protected $_template = 'Mavenbird_Shopbybrand::brand/featured.phtml';

    /**
     * Include Css Lib
     *
     * @return void
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

    public function getConfig()
    {
        return $this->_scopeConfig->getValue('shopbybrand/brandpage/brand_filter/enable_navigation', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function setWidgetOptions()
    {
        $this->setNavarrow((bool)$this->getWdNavarrow());
    }
    
    /**
     * Feature Title
     *
     * @return void
     */
    public function getFeatureTitle()
    {
        return $this->helper->getFeatureConfig('title');
    }

    /**
     * Show Label
     *
     * @return void
     */
    public function showLabel()
    {
        return $this->helper->getFeatureConfig('display');
    }

    /**
     * Featured Brand
     *
     * @return void
     */
    public function getFeaturedBrand()
    {
        $featureBrands = [];
        if (!$this->helper->enableFeature()) {
            return $featureBrands;
        }
        $collection = $this->getCollection();
        foreach ($collection as $brand) {
            if ($brand->getIsFeatured()) {
                $featureBrands[] = $brand;
            }
        }

        return $featureBrands;
    }

    /**
     * Get style display featured brand
     *
     * @return mixed
     */
    public function getStyleDisplayFeature()
    {
        return $this->helper->getFeatureConfig('style');
    }
}
