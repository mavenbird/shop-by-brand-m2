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

namespace Mavenbird\Shopbybrand\Block\Adminhtml\System;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class FeatureDisplay extends Field
{
    /**
     * Element Html
     *
     * @param AbstractElement $element
     * @return void
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $html = '<div class="control-value" style="padding-top: 8px">';
        $html .= '<p>' . __('Use following code to show featured brand block in any place which you want.') . '</p>';

        $html .= '<strong>' . __('CMS Page/Static Block') . '</strong><br />';
        $html .= '<pre style="background-color: #f5f5dc"><code>{{block class="Mavenbird\Shopbybrand\Block\Brand\Featured"}}</code></pre>';

        $html .= '<strong>' . __('Template .phtml file') . '</strong><br />';
        $html .= '<pre style="background-color: #f5f5dc"><code>' . $this->_escaper->escapeHtml('<?php echo $block->getLayout()->createBlock("Mavenbird\Shopbybrand\Block\Brand\Featured")->toHtml();?>') . '</code></pre>';

        $html .= '<strong>' . __('Layout file') . '</strong><br />';
        $html .= '<pre style="background-color: #f5f5dc"><code>' . $this->_escaper->escapeHtml('<block class="Mavenbird\Shopbybrand\Block\Brand\Featured" name="featured_brand" />') . '</code></pre>';

        $html .= '</div>';

        return $html;
    }
}
