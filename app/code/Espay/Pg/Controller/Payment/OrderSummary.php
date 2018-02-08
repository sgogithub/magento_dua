<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Espay\Pg\Controller\Payment;

class OrderSummary extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory  */
    protected $resultPageFactory;
    protected $_logger;

    /**
     * @var \Sivajik34\CustomFee\Helper\Data
     */
    protected $_dataHelper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Espay\Pg\Helper\Data $dataHelper,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_dataHelper = $dataHelper;
        parent::__construct($context);
    }
    /**
     * Load the page defined in view/frontend/layout/samplenewpage_index_index.xml
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {

      $request = $this->getRequest()->getParams();
      $_prval = empty($request['product_value']) ? '0:0:0' : $request['product_value'];
      $quote_id = empty($request['quote_id']) ? '': $request['quote_id'];
      $back_url = empty($request['back_url']) ? '': $request['back_url'];
      $urlRedirect = empty($request['urlRedirect']) ? '': $request['urlRedirect'];
      $product_value = explode(':', $_prval );

      $om = $this->_objectManager;
      $config = $om->get('Magento\Framework\App\Config\ScopeConfigInterface');

      $quote = $om->create('Magento\Sales\Model\Order')->load($quote_id);
      $order_id = $quote->getRealOrderId();

      // Get the Fee
      $fee  = $this->getFeeByProductCode($config, $product_value[1], $quote);

      $quote->setGrandTotal($quote->getGrandTotal() + $fee);
      $quote->setBaseGrandTotal($quote->getBaseGrandTotal() + $fee);
      $quote->setTotalDue($quote->getTotalDue() + $fee);
      $quote->setBaseTotalDue($quote->getBaseTotalDue() + $fee);
      $quote->setEspayfee($fee);

      $quote->save();

      $back_url_with_param = $back_url."?order_id=".$order_id."&product=".$product_value[1];

      /** @var \Magento\Framework\View\Result\Page $resultPage */
      $resultPage = $this->resultPageFactory->create();
      $resultPage->getLayout()->initMessages();

      $resultPage->getLayout()->getBlock('Espay_ordersummary')->setOrderId($quote_id);
      $resultPage->getLayout()->getBlock('Espay_ordersummary')->setBackUrl($back_url_with_param);
      $resultPage->getLayout()->getBlock('Espay_ordersummary')->setBankCode($product_value[0]);
      $resultPage->getLayout()->getBlock('Espay_ordersummary')->setProductValue($_prval);
      $resultPage->getLayout()->getBlock('Espay_ordersummary')->setProductName($product_value[2]);
      $resultPage->getLayout()->getBlock('Espay_ordersummary')->setUrlRedirect($urlRedirect);

      $resultPage->getLayout()->getBlock('Espay_ordersummary')->setSubtotal(number_format($quote->getSubtotal(), 2, '.', ','));
      $resultPage->getLayout()->getBlock('Espay_ordersummary')->setDiscountAmount(number_format(abs($quote->getDiscountAmount()), 2, '.', ','));
      $resultPage->getLayout()->getBlock('Espay_ordersummary')->setServiceFee(number_format($fee, 2, '.', ','));
      $resultPage->getLayout()->getBlock('Espay_ordersummary')->setShippingAmount(number_format($quote->getShippingAmount(), 2, '.', ','));
      $resultPage->getLayout()->getBlock('Espay_ordersummary')->setGrandTotal(number_format($quote->getGrandTotal(), 2, '.', ','));
      $resultPage->getLayout()->getBlock('Espay_ordersummary')->setFeeLabel($this->_dataHelper->getFeeLabel());

      return $resultPage;
    }


    private function getFeeByProductCode($config, $productCode, $order){

        $lower_product = strtolower($productCode);
        $defaultFee = $config->getValue('payment/espay/espay_default_fee', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $fee = $config->getValue('payment/espay/espay_'.$lower_product.'_fee', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if(empty($fee)){
          $fee = $defaultFee;
        }

        if ($productCode === 'CREDITCARD' || $productCode === 'BNIDBO'){
          $pct = $config->getValue('payment/espay/espay_creditcard_mdr', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
          $dec = str_replace('%', '', $pct) / 100;

          $totalWithoutFee = $order->getGrandTotal();
          $total = floatval($totalWithoutFee) + floatval($fee);
          $ccFee = floatval($dec) * floatval($total);
          $fee = floatval($fee)+floatval($ccFee);

        }

        return $fee;

    }
}
