<?php
namespace Espay\Pg\Model;

class Standard extends  \Magento\Payment\Model\Method\AbstractMethod {
    const TRX_STATUS_SETTLEMENT   = 'settlement';
    const ORDER_STATUS_EXPIRE   = 'expire';
	protected $_code = 'espay';

	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = true;
	protected $_canUseForMultishipping  = false;

	protected $_formBlockType = 'espay/form';
  	protected $_infoBlockType = 'espay/info';

	// call to redirectAction function at Veritrans_Snap_PaymentController
	public function getOrderPlaceRedirectUrl() {
		 return 'http://www.google.com/';
	}
}
?>
