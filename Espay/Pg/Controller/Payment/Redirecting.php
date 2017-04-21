<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Espay\Pg\Controller\Payment;

class Redirecting extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory  */
    protected $resultPageFactory;
    protected $_logger;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
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

      $om = $this->_objectManager;
      $config = $om->get('Magento\Framework\App\Config\ScopeConfigInterface');
      $isProduction = $config->getValue('payment/espay/is_production', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)=='1'?true:false;
      $client_key = $config->getValue('payment/espay/merchant_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

      $url = "https://sandbox-kit.espay.id/public/signature/js";
      if($isProduction){
        $url = "https://kit.espay.id/public/signature/js";
      }

      $quote_id = empty($request['quote_id']) ? '': $request['quote_id'];
      $quote = $om->create('Magento\Sales\Model\Order')->load($quote_id);
      $order_id = $quote->getRealOrderId();

      $_prval = empty($request['product_value']) ? '0:0' : $request['product_value'];
      $product_value = explode(':', $_prval );

      $back_url = empty($request['back_url']) ? '': $request['back_url'];
      $back_url_with_param = $back_url."?order_id=".$order_id."&product=".$product_value[1];

      $error = empty($client_key) ? "Merchant key is not valid" : "Please wait, redirecting ..";

      /** @var \Magento\Framework\View\Result\Page $resultPage */
      $resultPage = $this->resultPageFactory->create();
      $resultPage->getLayout()->initMessages();
      $resultPage->getLayout()->getBlock('Espay_main')->setOrderId($order_id);
      $resultPage->getLayout()->getBlock('Espay_main')->setBackUrl($back_url_with_param);
      $resultPage->getLayout()->getBlock('Espay_main')->setBankCode($product_value[0]);
      $resultPage->getLayout()->getBlock('Espay_main')->setProductCode($product_value[1]);
      $resultPage->getLayout()->getBlock('Espay_main')->setKey($client_key);
      $resultPage->getLayout()->getBlock('Espay_main')->setError($error);

      return $resultPage;
    }
}
