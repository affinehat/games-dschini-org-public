<?php
include_once('../config.php');

class paymentController {

	public static $THEME = THEME_DEFAULT;

	public static $PRODUCTS = array(
		array(
			'paymentAmount' => '3.00',
			'currencyCodeType' => 'EUR',
			'coins' => 3,
		),
		array(
			'paymentAmount' => '5.00',
			'currencyCodeType' => 'EUR',
			'coins' => 6,
		),
		array(
			'paymentAmount' => '8.00',
			'currencyCodeType' => 'EUR',
			'coins' => 10,
		)
	);
	
	public static function paypalIPNsActions(){
		$sandbox = isset($_POST['test_ipn']) ? true : false;
		$ssl = $sandbox ? false : true;
		$ppHost = $sandbox ? 'www.sandbox.paypal.com' : 'www.paypal.com';
		$req = 'cmd=_notify-validate';
		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
		// post back to PayPal system to validate
		$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		$fp = fsockopen ('ssl://'.$ppHost, 443, $errno, $errstr, 30);
		// assign posted variables to local variables
		$mc_gross = isset($_POST['mc_gross']) ? $_POST['mc_gross'] : '';
		$protection_eligibility = isset($_POST['protection_eligibility']) ? $_POST['protection_eligibility'] : '';
		$address_status = isset($_POST['address_status']) ? $_POST['address_status'] : '';
		$payer_id = isset($_POST['payer_id']) ? $_POST['payer_id'] : '';
		$tax = isset($_POST['tax']) ? $_POST['tax'] : '';
		$address_street = isset($_POST['address_street']) ? $_POST['address_street'] : '';
		$payment_date = isset($_POST['payment_date']) ? $_POST['payment_date'] : '';
		$payment_status = isset($_POST['payment_status']) ? $_POST['payment_status'] : '';
		$charset = isset($_POST['charset']) ? $_POST['charset'] : '';
		$address_zip = isset($_POST['address_zip']) ? $_POST['address_zip'] : '';
		$first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
		$mc_fee = isset($_POST['mc_fee']) ? $_POST['mc_fee'] : '';
		$address_country_code = isset($_POST['address_country_code']) ? $_POST['address_country_code'] : '';
		$address_name = isset($_POST['address_name']) ? $_POST['address_name'] : '';
		$notify_version = isset($_POST['notify_version']) ? $_POST['notify_version'] : '';
		$custom = isset($_POST['custom']) ? $_POST['custom'] : '';
                $item_name = isset($_POST['item_name']) ? $_POST['item_name'] : '';
                $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
		$payer_status = isset($_POST['payer_status']) ? $_POST['payer_status'] : '';
		$address_country = isset($_POST['address_country']) ? $_POST['address_country'] : '';
		$address_city = isset($_POST['address_city']) ? $_POST['address_city'] : '';
		$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
		$verify_sign = isset($_POST['verify_sign']) ? $_POST['verify_sign'] : '';
		$payer_email = isset($_POST['payer_email']) ? $_POST['payer_email'] : '';
		$txn_id = isset($_POST['txn_id']) ? $_POST['txn_id'] : '';
		$payment_type = isset($_POST['payment_type']) ? $_POST['payment_type'] : '';
		$last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
		$address_state = isset($_POST['address_state']) ? $_POST['address_state'] : '';
		$receiver_email = isset($_POST['receiver_email']) ? $_POST['receiver_email'] : '';
		$payment_fee = isset($_POST['payment_fee']) ? $_POST['payment_fee'] : '';
		$receiver_id = isset($_POST['receiver_id']) ? $_POST['receiver_id'] : '';
		$txn_type = isset($_POST['txn_type']) ? $_POST['txn_type'] : '';
		$item_name = isset($_POST['item_name']) ? $_POST['item_name'] : '';
		$mc_currency = isset($_POST['mc_currency']) ? $_POST['mc_currency'] : '';
		$item_number = isset($_POST['item_number']) ? $_POST['item_number'] : '';
		$residence_country = isset($_POST['residence_country']) ? $_POST['residence_country'] : '';
		$test_ipn = isset($_POST['test_ipn']) ? $_POST['test_ipn'] : '';
		$handling_amount = isset($_POST['handling_amount']) ? $_POST['handling_amount'] : '';
		$transaction_subject = isset($_POST['transaction_subject']) ? $_POST['transaction_subject'] : '';
		$payment_gross = isset($_POST['payment_gross']) ? $_POST['payment_gross'] : '';
		$shipping = isset($_POST['shipping']) ? $_POST['shipping'] : '';

		if (!$fp) {
			mail(ADMIN_EMAIL,'Dschini PayPal HTTP ERROR','!$fp - HTTP ERROR');
		} else {
			fputs ($fp, $header . $req);
			while (!feof($fp)) {
				$res = fgets ($fp, 1024);
				if (strcmp ($res, "VERIFIED") == 0) {
					// check the payment_status is Completed
					// check that txn_id has not been previously processed
					// check that receiver_email is your Primary PayPal email
					// check that payment_amount/payment_currency are correct
					// process payment
					if($payment_status=='Completed'){
						if(!PaymentsProxy::txnIdExists($txn_id)){
							if($receiver_email == PAYPAL_EMAIL){
								$paymentsProxy = new PaymentsProxy();
								$paymentsProxy->mc_gross = $mc_gross;
								$paymentsProxy->protection_eligibility = $protection_eligibility;
								$paymentsProxy->address_status = $address_status;
								$paymentsProxy->payer_id = $payer_id;
								$paymentsProxy->tax = $tax;
								$paymentsProxy->address_street = $address_street;
								$paymentsProxy->payment_date = $payment_date;
								$paymentsProxy->payment_status = $payment_status;
								$paymentsProxy->charset = $charset;
								$paymentsProxy->address_zip = $address_zip;
								$paymentsProxy->first_name = $first_name;
								$paymentsProxy->mc_fee = $mc_fee;
								$paymentsProxy->address_country_code = $address_country_code;
								$paymentsProxy->address_name = $address_name;
								$paymentsProxy->notify_version = $notify_version;
								$paymentsProxy->custom = $custom;
								$paymentsProxy->payer_status = $payer_status;
								$paymentsProxy->address_country = $address_country;
								$paymentsProxy->address_city = $address_city;
								$paymentsProxy->quantity = $quantity;
								$paymentsProxy->verify_sign = $verify_sign;
								$paymentsProxy->payer_email = $payer_email;
								$paymentsProxy->txn_id = $txn_id;
								$paymentsProxy->payment_type = $payment_type;
								$paymentsProxy->last_name = $last_name;
								$paymentsProxy->address_state = $address_state;
								$paymentsProxy->receiver_email = $receiver_email;
								$paymentsProxy->payment_fee = $payment_fee;
								$paymentsProxy->receiver_id = $receiver_id;
								$paymentsProxy->txn_type = $txn_type;
								$paymentsProxy->item_name = $item_name;
								$paymentsProxy->mc_currency = $mc_currency;
								$paymentsProxy->item_number = $item_number;
								$paymentsProxy->residence_country = $residence_country;
								$paymentsProxy->test_ipn = $test_ipn;
								$paymentsProxy->handling_amount = $handling_amount;
								$paymentsProxy->transaction_subject = $transaction_subject;
								$paymentsProxy->payment_gross = $payment_gross;
								$paymentsProxy->shipping = $shipping;
								$paymentsProxy->save();
								$paymentsProxy->sendPaymentMail();
								
								$_custom = explode(' ',$custom);
								$account = AccountsProxy::getByEMail($_custom[3]);
								if(!$account){
									mail(PAYPAL_EMAIL,'PayPal Error','the payment has completed but we could not find the account in the custom field: paymentsProxy->id:' . $paymentsProxy->id );
								}
								if($account){
									$coinsProxy = new CoinsProxy();
									$coinsProxy->account_id = $account->id;
									$coinsProxy->type = CoinsProxy::$TYPE_PURCHASE;
									$_product = self::productByPaymentAmount($mc_gross);
									$coinsProxy->amount = $_product['coins'];
									$coinsProxy->spent = 0;
									$coinsProxy->body = 'paymentProxy->id:'.$paymentsProxy->id;
									$coinsProxy->save();
									$account->points+=$coinsProxy->amount;
									$account->save();
								}
							}
						}
					}
				}
				else if (strcmp ($res, "INVALID") == 0) {
					mail(ADMIN_EMAIL,'Dschini PayPal INVALID','NOT VERIFIED');
				}
			}
			fclose ($fp);
		}

	}

	public static function coinsCancelAction(){
		$ret = array();
		if(isset($_SESSION['accountId'])){
			$ret['amountCoins'] = CoinsProxy::amountByAccountId($_SESSION['accountId']);
		}
		return TemplateHelper::renderToResponse(self::$THEME,"html/payments/coins_cancel.phtml",$ret);
	}
	
	public static function productByPaymentAmount($paymentAmount){
		foreach(self::$PRODUCTS as $product){
                	if($product['paymentAmount']==$paymentAmount){
				return $product;
			}
		}
		return null;
	}

	public static function coinsErrorAction(){
		$ret = array();
		$ret['resArray']=$_SESSION['reshash'];
		if(isset($_SESSION['accountId'])){
			$ret['amountCoins'] = CoinsProxy::amountByAccountId($_SESSION['accountId']);
		}
		return TemplateHelper::renderToResponse(self::$THEME,"html/payments/coins_error.phtml",$ret);
	}

	public static function coinsStep1Action(){
		$ret = array();
		$_SESSION['paypal'] = null;
		$_SESSION['nvpReqArray'] = null;
		$_SESSION['reshash'] = null;
		unset($_SESSION['paypal']);
		unset($_SESSION['nvpReqArray']);
		unset($_SESSION['reshash']);
		$_SESSION['payment']['step1'] = true;
		if(isset($_SESSION['accountId'])){
			$ret['amountCoins'] = CoinsProxy::amountByAccountId($_SESSION['accountId']);
		}
		$ret['products'] = self::$PRODUCTS;
		return TemplateHelper::renderToResponse(self::$THEME,"html/payments/coins_step1.phtml",$ret);
	}

	public static function coinsStep2Action(){
		if(!isset($_SESSION['payment']['step1'])){
			RequestHelper::redirect('/coins/');
		}
		$ret = array();
		if(! isset($_REQUEST['token'])) {
			$serverName = $_SERVER['SERVER_NAME'];
			$serverPort = $_SERVER['SERVER_PORT'];
			$url=dirname('http://'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);
			$_product = self::productByPaymentAmount($_REQUEST['paymentAmount']);
			$custom = $_product['coins'].' coins - '.$_SESSION['email'];
			$paymentAmount=$_REQUEST['paymentAmount'];
			$currencyCodeType=$_REQUEST['currencyCodeType'];
			$paymentType=$_REQUEST['paymentType'];
			$returnURL =urlencode('http://'.$_SERVER['SERVER_NAME'].'/coins/step2/?currencyCodeType='.$currencyCodeType.'&paymentType='.$paymentType.'&paymentAmount='.$paymentAmount.'&custom='.$custom);
			$cancelURL =urlencode('http://'.$_SERVER['SERVER_NAME'].'/coins/cancel/' );
			$nvpstr="&Amt=".$paymentAmount."&PAYMENTACTION=".$paymentType."&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$currencyCodeType.'&custom='.$custom;
			$resArray=CallerServiceHelper::hash_call("SetExpressCheckout",$nvpstr);
			$_SESSION['reshash']=$resArray;
			$ack = strtoupper($resArray["ACK"]);
			if($ack=="SUCCESS"){
				$token = urldecode($resArray["TOKEN"]);
				$payPalURL = PAYPAL_URL.$token;
				RequestHelper::redirect($payPalURL);
			} else {
				RequestHelper::redirect('/coins/error/');	
			}
		} else {
			$token =urlencode( $_REQUEST['token']);
			$custom = urlencode( $_REQUEST['custom']);
			$nvpstr="&TOKEN=".$token."&custom=".$custom;
			$resArray=CallerServiceHelper::hash_call("GetExpressCheckoutDetails",$nvpstr);
			$_SESSION['paypal']['reshash']=$resArray;
			$ack = strtoupper($resArray["ACK"]);
			if($ack!="SUCCESS"){		
				RequestHelper::redirect('/coins/error/');	
			}
		}
		$_SESSION['paypal']['token']=$_REQUEST['token'];
		$_SESSION['paypal']['payer_id'] = $_REQUEST['PayerID'];
		$_SESSION['paypal']['paymentAmount']=$_REQUEST['paymentAmount'];
		$_SESSION['paypal']['currCodeType']=$_REQUEST['currencyCodeType'];
		$_SESSION['paypal']['paymentType']=$_REQUEST['paymentType'];
		$_SESSION['paypal']['custom']=$_REQUEST['custom'];

		$ret['resArray'] = $resArray;
		$ret['paypal'] = $_SESSION['paypal'];
	
		if(isset($_SESSION['accountId'])){
			$ret['amountCoins'] = CoinsProxy::amountByAccountId($_SESSION['accountId']);
		}
		$_SESSION['payment']['step2'] = true;
		return TemplateHelper::renderToResponse(self::$THEME,"html/payments/coins_step2.phtml",$ret);
	}
	
	public static function coinsStep3Action(){
		if(!isset($_SESSION['payment']['step1'])
			||!isset($_SESSION['payment']['step2'])){
			RequestHelper::redirect('/coins/');
		}
		$ret = array();

		$token =urlencode( $_SESSION['paypal']['token']);
		$paymentAmount =urlencode ($_SESSION['paypal']['paymentAmount']);
		$paymentType = urlencode($_SESSION['paypal']['paymentType']);
		$currCodeType = urlencode($_SESSION['paypal']['currCodeType']);
		$payerID = urlencode($_SESSION['paypal']['payer_id']);
		$custom = urlencode($_SESSION['paypal']['custom']);

		$serverName = urlencode($_SERVER['SERVER_NAME']);
		$nvpstr='&TOKEN='.$token.'&PAYERID='.$payerID.'&PAYMENTACTION='.$paymentType.'&AMT='.$paymentAmount.'&CURRENCYCODE='.$currCodeType.'&custom='.$custom.'&IPADDRESS='.$serverName ;
		$resArray=CallerServiceHelper::hash_call("DoExpressCheckoutPayment",$nvpstr);
		$ack = strtoupper($resArray["ACK"]);
		if($ack!="SUCCESS"){
			$_SESSION['paypal']['reshash']=$resArray;
			RequestHelper::redirect('/coins/error/');
		}
		$ret['resArray'] = $resArray;
		if(isset($_SESSION['accountId'])){
			$ret['amountCoins'] = CoinsProxy::amountByAccountId($_SESSION['accountId']);
		}
		$ret['transactionId'] = $resArray['TRANSACTIONID'];
		return TemplateHelper::renderToResponse(self::$THEME,"html/payments/coins_step3.phtml",$ret);
	}
	
	public static function buyAction($product_id){
		if(!$product_id){
			RequestHelper::redirect('/');
		}
		if(!isset($_SESSION['accountId'])){
			RequestHelper::redirect('/login/');
		}
		if(!isset($GLOBALS['games'][$product_id])){
			RequestHelper::redirect('/');
		}
		if($GLOBALS['games'][$product_id]['coins']<=0){
			RequestHelper::redirect('/');
		}
		$amountCoins = CoinsProxy::amountByAccountId($_SESSION['accountId']);
		if(!$amountCoins){
			RequestHelper::redirect('/');
		}
		if($amountCoins<$GLOBALS['games'][$product_id]['coins']){
			RequestHelper::redirect('/');
		}
		if(AccountsProductsProxy::expireDateIsTooOld($_SESSION['accountId'],$product_id)){
			RequestHelper::redirect('/');
		}
		$ok = CoinsProxy::doSpentByAccountId($_SESSION['accountId'],1);
		if(!$ok){
			RequestHelper::redirect('/');
		}
		$accountsProductsProxy = AccountsProductsProxy::getUnlockedProductByAccount($_SESSION['accountId'],$product_id);
		if(!$accountsProductsProxy){
			$accountsProductsProxy = new AccountsProductsProxy();
			$accountsProductsProxy->account_id = $_SESSION['accountId'];
			$accountsProductsProxy->product_id = $product_id;
		}
		$accountsProxy = AccountsProxy::get($_SESSION['accountId']);
		if($accountsProxy){
			$accountsProxy->points+=5;
			$accountsProxy->save();
		}
		$accountsProductsProxy->save();
		$next = isset($_GET['next']) ? $_GET['next'] : '/';
		RequestHelper::redirect($next);
	}
}
