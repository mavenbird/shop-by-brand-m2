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

use Magento\Cms\Block\Block;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Mavenbird\Shopbybrand\Block\Brand;
use Mavenbird\Shopbybrand\Model\BrandFactory;
use Mavenbird\Shopbybrand\Model\CategoryFactory;

class View extends Brand
{
    /**
     * Prepare Layout
     *
     * @return void
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
    
        $brand = $this->getBrand();
        if ($brand) {
            $title = $brand->getPageTitle() ?: $brand->getValue();
            if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbsBlock->addCrumb('view', ['label' => $title]);
            }
    
            $description = $brand->getMetaDescription();
            if ($description) {
                $this->pageConfig->setDescription($description);
            }
            $keywords = $brand->getMetaKeywords();
            if ($keywords) {
                $this->pageConfig->setKeywords($keywords);
            }
            $this->pageConfig->addRemotePageAsset(
                $this->helper()->getBrandUrl($brand),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
    
            $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
            if ($pageMainTitle) {
                $pageMainTitle->setPageTitle($title);
            }
        }
    
        return $this;
    }
    

    /**
     * getBrandDescriptions
     *
     * @return void
     */
    public function getBrandDescription()
    {
        $brand = $this->getBrand();
        return $brand ? $brand->getDescription() : ''; // Assuming 'description' is a valid field.
    }
    
    /**
     * Addition Crumb
     *
     * @param [type] $block
     * @return void
     */
    protected function additionCrumb($block)
    {
        $title = $this->getPageTitle();
        $block->addCrumb('brand', [
            'label' => __($title),
            'title' => __($title),
            'link'  => $this->helper->getBrandUrl()
        ]);

        $brand      = $this->getBrand();
        $brandTitle = $brand->getPageTitle() ?: $brand->getValue();
        $block->addCrumb('view', ['label' => $brandTitle]);

        return $this;
    }

    /**
     * Brand
     *
     * @return void
     */
    public function getBrand()
    {
        if (!$this->hasData('current_brand')) {
            $this->setData('current_brand', $this->helper()->getBrand());
        }

        return $this->getData('current_brand');
    }

    /**
     * Meta Title
     *
     * @return void
     */
    public function getMetaTitle()
    {
        $brand = $this->getBrand();
    
        if (!$brand) {
            return $this->getPageTitle();
        }
    
        $metaTitle = $brand->getMetaTitle();
        if ($metaTitle) {
            return $metaTitle;
        }
    
        $title = $brand->getPageTitle() ?: $brand->getValue();
    
        return implode($this->getTitleSeparator(), [$title, $this->getPageTitle()]);
    }
    

    /**
     * Brand Image
     *
     * @return void
     */
    public function getBrandImage()
    {
        if (!$this->helper->getBrandDetailConfig('show_image')) {
            return '';
        }

        return $this->getBrand()->getImage();
    }

    // /**
    //  * Brand Description
    //  *
    //  * @return void
    //  */
    // public function getBrandDescription()
    // {
    //     if (!$this->helper->getBrandDetailConfig('show_description')) {
    //         return '';
    //     }
    //     $brand = $this->getBrand();

    //     return $this->helper()->getBrandDescription($brand);
    // }

    /**
     * Static Content
     *
     * @return void
     */
    public function getStaticContent()
    {
        if (!$this->helper->getBrandDetailConfig('show_block')) {
            return '';
        }

        $block = $this->getBrand()->getStaticBlock() ?: $this->helper()->getBrandDetailConfig('default_block');

        $cmsBlock = $this->_blockFactory->create();
        $cmsBlock->load($block, 'identifier');

        $html = '';
        if ($cmsBlock && $cmsBlock->getId()) {
            $html = $this->getLayout()->createBlock(Block::class)
                ->setBlockId($cmsBlock->getId())
                ->toHtml();
        }

        return $html;
    }

    /**
     * Product List Html
     *
     * @return void
     */
    public function getProductListHtml()
    {
        return $this->getChildHtml();
    }
}
