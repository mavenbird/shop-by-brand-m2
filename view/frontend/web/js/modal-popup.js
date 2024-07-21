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

require([
        'jquery',
        'Magento_Ui/js/modal/modal'
    ], function ($, modal) {
    'use strict';
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            clickableOverlay: true,
            modalClass: 'brand-quick-view',
            title: 'Brand Informations',
            buttons: [{
                text: $.mage.__('Close'),
                class: '',
                click: function () {
                    this.closeModal();
                }
            }]
        };

        modal(options, $('#quick-view'));

        $(".open_model").on("click", function () {
            $('#quick-view').modal('openModal');
            $('.modals-overlay').addClass('z-index:');
        });
        $(document).click(function (event) {
            if (event.target.className.indexOf('_show') >= 0) {
                $('#quick-view').modal('closeModal');
            }
        });
    }
);