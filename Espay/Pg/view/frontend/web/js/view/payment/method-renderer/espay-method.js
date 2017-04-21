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
                          var prvalue = item.bankCode + ':' + item.productCode;
                          $('#sl_payment').append($('<option>', {
                              value: prvalue,
                              text : item.productName
                          }));
                      });
                      $('#sl_payment').prop('disabled', false);
                      fullScreenLoader.stopLoader();
                    }
                });
            },
            // getPaymentMethods: function() {
            //     // var json = getPayments();
            //     var json = '{"error_code":"0000","error_message":"","data":[{"bankCode":"014","productCode":"BCAKLIKPAY","productName":"BCA KlikPay"},{"bankCode":"016","productCode":"XLTUNAI","productName":"XL TUNAI"},{"bankCode":"016","productCode":"BIIATM","productName":"ATM MULTIBANK"},{"bankCode":"009","productCode":"BNIDBO","productName":"BNI Debit Online"},{"bankCode":"002","productCode":"BRIATM","productName":"BRI ATM"},{"bankCode":"002","productCode":"EPAYBRI","productName":"e-Pay BRI"},{"bankCode":"011","productCode":"DANAMONATM","productName":"ATM Danamon"},{"bankCode":"011","productCode":"DANAMONOB","productName":"Danamon Online Banking"},{"bankCode":"111","productCode":"DKIIB","productName":"DKI IB"},{"bankCode":"008","productCode":"CCINSTALL3","productName":"Credit Card Visa \/ Master 3 Months Installment"},{"bankCode":"SCASH","productCode":"EMOEDIKK2","productName":"EMO EDIKK2"},{"bankCode":"SCASH","productCode":"SGOEMONEY","productName":"SGO E Money"},{"bankCode":"SCASH","productCode":"EMOTOKOPETANI","productName":"EMONEY TOKO PETANI"},{"bankCode":"008","productCode":"CREDITCARD","productName":"Credit Card Visa \/ Master"},{"bankCode":"008","productCode":"TMONEY","productName":"T-Money"},{"bankCode":"008","productCode":"FINPAY195","productName":"Modern Channel"},{"bankCode":"008","productCode":"MANDIRISMS","productName":"MANDIRI SMS"},{"bankCode":"008","productCode":"MANDIRIIB","productName":"MANDIRI IB"},{"bankCode":"008","productCode":"MANDIRIECASH","productName":"MANDIRI E-CASH"},{"bankCode":"008","productCode":"PAYPAL","productName":"PAYPAL"},{"bankCode":"008","productCode":"CCPROMO","productName":"Credit Card Visa \/ Master Promotion"},{"bankCode":"008","productCode":"CCINSTALL6","productName":"Credit Card Visa \/ Master 6 Months Installment"},{"bankCode":"008","productCode":"CCINSTALL12","productName":"Credit Card Visa \/ Master 12 Months Installment"},{"bankCode":"157","productCode":"MASPIONATM","productName":"ATM MASPION"},{"bankCode":"097","productCode":"MAYAPADAIB","productName":"Mayapada Internet Banking"},{"bankCode":"147","productCode":"MUAMALATATM","productName":"MUAMALAT ATM"},{"bankCode":"503","productCode":"NOBUPAY","productName":"Nobu Pay"},{"bankCode":"013","productCode":"PERMATANETPAY","productName":"PermataNet"},{"bankCode":"013","productCode":"PERMATAATM","productName":"PERMATA ATM"}]}';
            //     var parsed = JSON.parse(json);
            //
            //     return _.map(parsed.data, function(result) {
            //         var value = result.bankCode + ':' + result.productCode;
            //         console.log(value);
            //         console.log(result);
            //
            //         return {
            //             'productValue': value,
            //             'productName': result.productName
            //         }
            //     });
            //
            //   },
            redirectAfterPlaceOrder: false,
            afterPlaceOrder: function () {

                var quoteId = quote.getQuoteId();
                var backUrl = url.build('espay/payment/response');
                var product_value = $("#sl_payment option:selected").val();

                var form = $("#f_redirect");
                var urlRedirect = url.build('espay/payment/redirecting');
                form.attr("action", urlRedirect);

                $("#quote_id").val(quoteId);
                $("#back_url").val(backUrl);
                $("#product_value").val(product_value);
                form.submit();

            }
        });
    }
);
