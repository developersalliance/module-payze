define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'payze',
                component: 'DevAll_Payze/js/view/payment/method-renderer/payze'
            },
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
