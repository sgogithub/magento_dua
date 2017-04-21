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
                type: 'espay',
                component: 'Espay_Pg/js/view/payment/method-renderer/espay-method'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
