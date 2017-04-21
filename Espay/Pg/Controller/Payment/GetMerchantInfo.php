<?php
namespace Espay\Pg\Controller\Payment;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;

class GetMerchantInfo extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory  */
    protected $_logger;

    public function __construct(\Magento\Framework\App\Action\Context $context)
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $om = $this->_objectManager;
        $config = $om->get('Magento\Framework\App\Config\ScopeConfigInterface');

        $isProduction = $config->getValue('payment/espay/is_production', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)=='1'?true:false;

        $client_key = $config->getValue('payment/espay/merchant_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $request = 'key='.$client_key;

        $url = "https://sandbox-api.espay.id/rest/merchant/merchantinfo";
        if($isProduction){
          $url = "https://api.espay.id/rest/merchant/merchantinfo";
        }

        try {
                  $curl = curl_init($url);

                  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                  curl_setopt($curl, CURLOPT_POST, true);
                  curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

                  curl_setopt($curl, CURLOPT_HEADER, false);
                  // curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
                  curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // use http 1.1
                  curl_setopt($curl, CURLOPT_TIMEOUT, 60);
                  curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
                  // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

                  // NOTE: skip SSL certificate verification (this allows sending request to hosts with self signed certificates, but reduces security)
                  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                  // enable ssl version 3
                  // this is added because mandiri ecash case that ssl version that have been not supported before
                  curl_setopt($curl, CURLOPT_SSLVERSION, 1);

                  curl_setopt($curl, CURLOPT_VERBOSE, true);
                  // save to temporary file (php built in stream), cannot save to php://memory
                  $verbose = fopen('php://temp', 'rw+');
                  curl_setopt($curl, CURLOPT_STDERR, $verbose);

                  $response = curl_exec($curl);

                  // $response = json_decode($response);
                  echo $response;

        }
        catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}
