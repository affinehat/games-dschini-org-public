<?php
class PaymentsProxy
{
	public $id;
	public $created;
	
	public $mc_gross;
	public $protection_eligibility;
	public $address_status;
	public $payer_id;

	public $tax;
	public $address_street;
	public $payment_date;
	public $payment_status;
	public $charset;
	
	public $address_zip;
	public $first_name;
	public $mc_fee;
	public $address_country_code;
	public $address_name;
	
	public $notify_version;
	public $custom;
	public $payer_status;
	public $address_country;
	public $address_city;
	
	public $quantity;
	public $verify_sign;
	public $payer_email;
	public $txn_id;
	public $payment_type;
	
	public $last_name;
	public $address_state;
	public $receiver_email;
	public $payment_fee;
	public $receiver_id;
	
	public $txn_type;
	public $item_name;
	public $mc_currency;
	public $item_number;
	public $residence_country;
	
	public $test_ipn;
	public $handling_amount;
	public $transaction_subject;
	public $payment_gross;
	public $shipping;
	
	public function sendPaymentMail()
	{
		$headers = 'From: noreply@dschini.org';
		$subject = 'dschini.org - Payment approved!';
		$message = 'Your payment has been approved:'."\r\n";
		$message.= "\r\n";
		$message.= "Order: ".$this->custom."\r\n";
		$message.= "Total: ".$this->mc_gross." ".$this->mc_currency."\r\n";
		$message.= "\r\n";
		$message.= 'Thank you and enjoy the games!'."\r\n";
		$message.= 'http://games.dschini.org'."\r\n";
		$message.= "\r\n";
		$message.= 'If you experience any problem doe not hesitate to contact me:'."\r\n";
		$message.= 'you@mail.com'."\r\n";
		mail($this->payer_email, $subject, $message, $headers);
	}
	
	public function save(){
		if(!isset($this->id)){
			$sql = sprintf("INSERT INTO `payments`
							(`created`,
							`mc_gross`,`protection_eligibility`,`address_status`,`payer_id`,`tax`,
							`address_street`,`payment_date`,`payment_status`,`charset`,`address_zip`,
							`first_name`,`mc_fee`,`address_country_code`,`address_name`,`notify_version`,
							`custom`,`payer_status`,`address_country`,`address_city`,`quantity`,
							`verify_sign`,`payer_email`,`txn_id`,`payment_type`,`last_name`,
							`address_state`,`receiver_email`,`payment_fee`,`receiver_id`,
							`txn_type`,`item_name`,`mc_currency`,`item_number`,`residence_country`,
							`test_ipn`,`handling_amount`,`transaction_subject`,`payment_gross`,`shipping`) 
							VALUES 
							(now(),
							'%s','%s','%s','%s','%s',
							'%s','%s','%s','%s','%s',
							'%s','%s','%s','%s','%s',
							'%s','%s','%s','%s','%s',
							'%s','%s','%s','%s','%s',
							'%s','%s','%s','%s',
							'%s','%s','%s','%s','%s',
							'%s','%s','%s','%s','%s'
							);"
				,DBConnectionHelper::getInstance()->escape($this->mc_gross)
				,DBConnectionHelper::getInstance()->escape($this->protection_eligibility)
				,DBConnectionHelper::getInstance()->escape($this->address_status)
				,DBConnectionHelper::getInstance()->escape($this->payer_id)
				,DBConnectionHelper::getInstance()->escape($this->tax)

				,DBConnectionHelper::getInstance()->escape($this->address_street)
				,DBConnectionHelper::getInstance()->escape($this->payment_date)
				,DBConnectionHelper::getInstance()->escape($this->payment_status)
				,DBConnectionHelper::getInstance()->escape($this->charset)
				,DBConnectionHelper::getInstance()->escape($this->address_zip)

				,DBConnectionHelper::getInstance()->escape($this->first_name)
				,DBConnectionHelper::getInstance()->escape($this->mc_fee)
				,DBConnectionHelper::getInstance()->escape($this->address_country_code)
				,DBConnectionHelper::getInstance()->escape($this->address_name)
				,DBConnectionHelper::getInstance()->escape($this->notify_version)

				,DBConnectionHelper::getInstance()->escape($this->custom)
				,DBConnectionHelper::getInstance()->escape($this->payer_status)
				,DBConnectionHelper::getInstance()->escape($this->address_country)
				,DBConnectionHelper::getInstance()->escape($this->address_city)
				,DBConnectionHelper::getInstance()->escape($this->quantity)

				,DBConnectionHelper::getInstance()->escape($this->verify_sign)
				,DBConnectionHelper::getInstance()->escape($this->payer_email)
				,DBConnectionHelper::getInstance()->escape($this->txn_id)
				,DBConnectionHelper::getInstance()->escape($this->payment_type)
				,DBConnectionHelper::getInstance()->escape($this->last_name)

				,DBConnectionHelper::getInstance()->escape($this->address_state)
				,DBConnectionHelper::getInstance()->escape($this->receiver_email)
				,DBConnectionHelper::getInstance()->escape($this->payment_fee)
				,DBConnectionHelper::getInstance()->escape($this->receiver_id)

				,DBConnectionHelper::getInstance()->escape($this->txn_type)
				,DBConnectionHelper::getInstance()->escape($this->item_name)
				,DBConnectionHelper::getInstance()->escape($this->mc_currency)
				,DBConnectionHelper::getInstance()->escape($this->item_number)
				,DBConnectionHelper::getInstance()->escape($this->residence_country)

				,DBConnectionHelper::getInstance()->escape($this->test_ipn)
				,DBConnectionHelper::getInstance()->escape($this->handling_amount)
				,DBConnectionHelper::getInstance()->escape($this->transaction_subject)
				,DBConnectionHelper::getInstance()->escape($this->payment_gross)
				,DBConnectionHelper::getInstance()->escape($this->shipping)
				);

				DBConnectionHelper::getInstance()->execute($sql);
			$this->id = DBConnectionHelper::getInstance()->insert_id();
		} else {
			// nothing yet
		}
	}
	
	public static function txnIdExists($txn_id){
		$sql = sprintf("SELECT * from payments WHERE `txn_id`='%s'",
			DBConnectionHelper::getInstance()->escape($txn_id)
		);
		$row = DBConnectionHelper::getInstance()->query($sql);
		if($row){
			return true;
		}
		return false;
	}
	
	public static function get( $id ){
		$sql = sprintf("SELECT * from payments WHERE `id`=%d",$id);
		$row = DBConnectionHelper::getInstance()->query($sql);
		if(sizeof($row)>0){
			$obj = new PaymentsProxy();
			foreach($row[0] as $key => $value){
				$obj->{$key} = $value;
			}
			return $obj;
		}
		return null;
	}

	public static function filter( $filter ){
		$sql = sprintf("SELECT * FROM payments WHERE %s", $filter);
		$rows = DBConnectionHelper::getInstance()->query($sql);
		$arr = array();
		foreach($rows AS $row){
			$obj = new PaymentsProxy();
			foreach($row as $key => $value){
				$obj->{$key} = $value;
			}
			$arr[] = $obj;
		}
		return $arr;
	}	
}
