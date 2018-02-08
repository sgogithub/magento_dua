<?php
namespace Espay\Pg\Controller\Payment;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;

class Response extends \Magento\Framework\App\Action\Action
{
    protected $product;
    protected $cart;
    protected $_responseFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Product $product,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\App\ResponseFactory $responseFactory
    )
    {
        $this->product = $product;
        $this->cart = $cart;
        $this->_responseFactory = $responseFactory;
       parent::__construct($context);
    }

    /**
     * Load the page defined in view/frontend/layout/samplenewpage_index_index.xml
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $om = $this->_objectManager;
        $session = $om->get('Magento\Checkout\Model\Session');
        $quote = $session->getQuote();

        if(isset($_GET['order_id']) ) {
            $config = $om->get('Magento\Framework\App\Config\ScopeConfigInterface');
            $orderId = $_GET['order_id']; // Generally sent by gateway
            $productCode = $_GET['product'];
            $quote = $om->create('Magento\Sales\Model\Order')->load($orderId);

            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/responseafterpayment.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $_info = "orderid : ".$orderId.".";
            $logger->info( $_info );
            $statusOrder = $quote->getStatus();

            if( ($statusOrder == 'accpt_espay_'.strtolower($productCode) or $statusOrder == 'payment_accepted_espay') && !is_null($orderId) && $orderId != '') {
				          return $this->resultRedirectFactory->create()->setPath('checkout/onepage/success');
            }else {
                //  $this->fromOrderId($orderId);
                return $this->resultRedirectFactory->create()->setPath('checkout/onepage/failure');
                // Back to merchant - reorder
            }
        }
        else{
               return $this->resultRedirectFactory->create()->setPath('/');
        }
    }

    // public function fromOrderId($orderId){
    //
    //     $order = $this->_objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($orderId);
    //
    //     $orderItems = $order->getAllItems();
    //     $order->addStatusToHistory(\Magento\Sales\Model\Order::STATE_CANCELED, "Gateway has declined the payment");
    //     $order->save();
    //
    //     $storeManager = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
    //     $currentStore = $storeManager->getStore();
    //     $baseUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::DEFAULT_URL_TYPE);
    //     $redirUrl = $baseUrl.'checkout/onepage/success';
    //
    //     foreach($orderItems as $item){
    //         $productId = $item->getProductId();
    //         $params = array();
    //         $params['qty'] = $item->getQtyOrdered();
    //         $_product = $this->product->load($productId);
    //
    //         $stockState = $this->_objectManager->get('\Magento\CatalogInventory\Api\StockStateInterface');
    //         $stock = $stockState->getStockQty($_product->getId(), $_product->getStore()->getWebsiteId());
    //         if ($_product && $stock > 0) {
    //             $redirUrl = $baseUrl.'checkout/cart';
    //             $this->cart->addProduct($_product, $params);
    //             $this->cart->save();
    //         }
    //     }
    //
    //     // $this->_messageManager->addError( __('Can not submit payment confirmation. Please try again later.') );
    //     $a = $this->_responseFactory->create()->setRedirect($redirUrl)->sendResponse();
    //     // var_dump(empty($a));
    //     // die();
    //
    //     // return true;
    // }
}
