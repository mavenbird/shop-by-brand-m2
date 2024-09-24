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

namespace Mavenbird\Shopbybrand\Plugin\Link;

use Mavenbird\Shopbybrand\Block\Link\CategoryMenu;
use Mavenbird\Shopbybrand\Helper\Data;
use Mavenbird\Shopbybrand\Model\Config\Source\BrandPosition;

class TopMenuPorto
{
    /**
     * Data
     *
     * @var [type]
     */
    protected $helperData;

    /**
     * Construct
     *
     * @param Data $helperData
     */
    public function __construct(Data $helperData)
    {
        $this->helperData = $helperData;
    }

    /**
     * After Get Megamenu Html
     *
     * @param [type] $topmenu
     * @param [type] $html
     * @return void
     */
    public function afterGetMegamenuHtml($topmenu, $html)
    {
        if (!$this->helperData->isEnabled() || !$this->helperData->canShowBrandLink(BrandPosition::CATEGORY)) {
            return $html;
        }
        $brandHtml = $topmenu->getLayout()->createBlock(CategoryMenu::class)
            ->setTemplate('Mavenbird_Shopbybrand::position/topmenuporto.phtml')->toHtml();

        return $html . $brandHtml;
    }
}
