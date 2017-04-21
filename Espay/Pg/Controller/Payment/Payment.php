<?php
namespace Espay\Pg\Controller\Payment;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;

class Payment extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender
     */
    protected $orderCommentSender;

    public function __construct(\Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender $orderCommentSender
    )
    {
        parent::__construct($context);
        $this->registry = $registry;
        $this->orderCommentSender = $orderCommentSender;
    }

    /**
     * Load the page defined in view/frontend/layout/samplenewpage_index_index.xml
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {

        $om = $this->_objectManager;
        $config = $om->get('Magento\Framework\App\Config\ScopeConfigInterface');

        // Get Config Data
        $password = $config->getValue('payment/espay/password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $signature_key = $config->getValue('payment/espay/signature_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $default_order_status = $config->getValue('payment/espay/order_status', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        // Log the request
        $logger = $om->get('Psr\Log\LoggerInterface');
        $raw_notification = json_decode(file_get_contents("php://input"), true);
        $logger->debug('Espay $raw_notification : '.print_r($raw_notification,true));

        // Request Data from Espay
        $request = $this->getRequest()->getParams();
        $req_orderid = $request['order_id'];
        $req_prodcode = $request['product_code'];
        $req_password = $request['password'];
        $req_signature = $request['signature'];
        $req_rqdatetime = $request['rq_datetime'];
        $req_paymentref = $request['payment_ref'];

        // Step 1: Validate Password
        if($req_password === $password){
            // Step 2: Validate Signature
            $gen_signature = $this->generateSignature($req_rqdatetime, $req_orderid, $signature_key);
            if($req_signature === $gen_signature){
                // Step 3: Is Order Id Valid?

                // Get Order Id
                $orderId = $req_orderid;
                $order = $om->create('Magento\Sales\Model\Order')->load($orderId);

                if(!empty($order->getRealOrderId())){
                  // Step 4: Has order been processed?
                  if($order->getStatus() === $default_order_status){

                      // Check status avaibility
                      $statusModel = $om->create('Magento\Sales\Model\Order\Status')->load('accpt_espay_'.$req_prodcode);
                      $status = empty($statusModel->getStatus()) ? 'payment_accepted_espay' : 'accpt_espay_'.$req_prodcode;

                      if($order->canInvoice() && !$order->hasInvoices()) {
                                  $invoice = $this->_objectManager->create('Magento\Sales\Model\Service\InvoiceService')->prepareInvoice($order);
                                  $invoice->register();
                                  $invoice->save();
                                  $invoice->pay();
                                  $transactionSave = $this->_objectManager->create(
                                      'Magento\Framework\DB\Transaction'
                                  )->addObject(
                                          $invoice
                                      )->addObject(
                                          $invoice->getOrder()
                                      );
                                  $transactionSave->save();
                              }
              				$order->setData('state', 'processing');
                      $order->addStatusHistoryComment('Payment Success With Ref <b>' . $req_paymentref . '</b>.', true);
              				$order->setStatus(strtolower($status));

                      if ($this->registry->registry('advancedorderstatus_notifications')) {
                          $this->orderCommentSender->send($order);
                      }

                      $order->save();

                      $trxDate = date('d/m/Y H:i:s');
                      $reconsile_id = $order->getRealOrderId();
                      echo "0,Success,$reconsile_id,$orderId,$trxDate";
                  }else{
                    echo "1,Order Has Been Processed,,,";
                  }
                }else{
                  echo "1,Order Id Not Found,,,";
                }
            }else{
              echo "1,Invalid Signature,,,";
            }
        }else{
            echo "1,Invalid Password,,,";
        }
    }

    private function generateSignature($rqDatetime, $order_id, $signature_key){

      $mode = "PAYMENTREPORT";
      $key = $signature_key;
      $data = "##".$key."##".$rqDatetime."##".$order_id."##".$mode."##";

      $upperCase = strtoupper($data);
      $signature = hash('sha256', $upperCase);

      return $signature;
    }
}
