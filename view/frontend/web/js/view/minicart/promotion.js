define([
    'uiComponent',
    'ko',
    'Magento_Customer/js/customer-data',
    ],
    function (Component, ko, customerData) {
        'use strict';

        let cartData = customerData.get('cart');

        return Component.extend({
            defaults: {
                template: 'Mfdc_Challenge/minicart/promotion'
            },

            initialize: function() {
                this._super();
            },

            hasPromotion: function () {
                return cartData().hasOwnProperty('promotion');
            },

            getPromos: function () {
                return cartData().promotion;
            }
        });
    }
);
