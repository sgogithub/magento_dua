define(
    [
        'Magento_Checkout/js/view/payment/default',
        'jquery',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/url-builder',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/full-screen-loader',
        'Espay_Pg/js/action/set-payment-method',
        'Magento_Checkout/js/model/payment/additional-validators',
        'mage/url',
        'Magento_Ui/js/model/messageList'
    ],
    function (Component, $, quote, urlBuilder, storage, errorProcessor, customer, fullScreenLoader, setPaymentMethodAction, additionalValidators, url, messageList) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Espay_Pg/payment/espay'
            },
            getPayments: function(){

                console.log(quote);

                fullScreenLoader.startLoader();
                $('#sl_payment').prop('disabled', 'disabled');
                var urlRedirect = url.build('espay/payment/getmerchantinfo');
                $.ajax({
                    type: 'get',
                    url: urlRedirect,
                    cache: false,
                    success: function(response) {
                      var parsed = JSON.parse(response);
                      var result = parsed.data;

                      $.each(result, function (i, item) {
                          var prvalue = item.bankCode + ':' + item.productCode + ':' + item.productName;
                          $('#sl_payment').append($('<input>', {
                              id: item.productCode,
                              type: 'radio',
                              name: 'product_value',
                              value: prvalue
                          }));
                          $('#sl_payment').append($('<label>', {
                              class: 'drinkcard-cc',
                              style: 'background-image:url(https://kit.espay.id/images/products/'+item.productCode+'.png);',
                              title: item.productName,
                              for: item.productCode
                          }));
                      });
                      fullScreenLoader.stopLoader();
                    }
                });
            },

            redirectAfterPlaceOrder: false,
            afterPlaceOrder: function () {

                var quoteId = quote.getQuoteId();
                var backUrl = url.build('espay/payment/response');

                var form = $("#f_redirect");
                var urlOrderSummary = url.build('espay/payment/ordersummary');
                var urlRedirect = url.build('espay/payment/redirecting');

                form.attr("action", urlOrderSummary);

                $("#quote_id").val(quoteId);
                $("#back_url").val(backUrl);
                $("#urlRedirect").val(urlRedirect);
                form.submit();

            }
        });
    }
);
