define([
    'jquery',
    'Magento_Checkout/js/view/payment/default',
    'Magento_Checkout/js/action/redirect-on-success',
    'mage/url'
], function ($, Component, redirectOnSuccessAction, url) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'DevAll_Payze/payment/payze',
        },

        /**
         * Redirect to controller after place order
         */
        afterPlaceOrder: function () {
            redirectOnSuccessAction.redirectUrl = url.build('payze/payment/redirect');
            this.redirectAfterPlaceOrder = true;
        }
    });
});
