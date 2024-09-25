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

define([
    'jquery',
    'mavenbird/shopbybrand/owl.carousel'
], function ($) {
    'use strict';
    return function (config, element) {
        $(element).owlCarousel({
            nav:true,
            center: true,
            loop: true,
            margin: 10,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            lazyLoad: true,
            dots: true,
            responsiveClass: true,
            responsiveBaseElement: '#' + $(element).attr('id'),
            responsive: {
                0: {items: 1},
                360: {items: 2},
                540: {items: 3},
                720: {items: 4},
                900: {items: 5}
            }
        });
    };
});