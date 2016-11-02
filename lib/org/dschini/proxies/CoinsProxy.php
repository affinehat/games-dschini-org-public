<?php
class CoinsProxy
{
	public $id;
	public $created;
	public $account_id;
	public $type;
	public $amount;
	public $spent;
	public $body;
	
	public static $TYPE_GIFT = 1;
	public static $TYPE_PURCHASE = 10;

	public function save(){
		if(!isset($this->id)){
			$sql = sprintf("INSERT INTO `coins`
							(`created`,`account_id`,`type`,`amount`,`spent`,`body`) 
							VALUES 
							(now(),%d,%d,%d,%d,'%s');"
				,DBConnectionHelper::getInstance()->escape($this->account_id)
				,DBConnectionHelper::getInstance()->escape($this->type)
				,DBConnectionHelper::getInstance()->escape($this->amount)
				,DBConnectionHelper::getInstance()->escape($this->spent)
				,DBConnectionHelper::getInstance()->escape($this->body)
				);
				DBConnectionHelper::getInstance()->execute($sql);
			$this->id = DBConnectionHelper::getInstance()->insert_id();
		} else {
			$sql = sprintf("UPDATE `coins` SET 
				`account_id`=%d, `type`=%d, `amount`=%d, `spent`=%d, `body`='%s'
				WHERE id=%d"
				,DBConnectionHelper::getInstance()->escape($this->account_id)
				,DBConnectionHelper::getInstance()->escape($this->type)
				,DBConnectionHelper::getInstance()->escape($this->amount)
				,DBConnectionHelper::getInstance()->escape($this->spent)
				,DBConnectionHelper::getInstance()->escape($this->body)
				);
			DBConnectionHelper::getInstance()->execute($sql);
		}
	}
	
	public static function doSpentByAccountId($account_id,$amount){
		$sql = sprintf("SELECT * from coins WHERE `account_id`=%d and amount>=(spent+%d) LIMIT 0,1",$account_id,$amount);
		$row = DBConnectionHelper::getInstance()->query($sql);
		if(!$row){
			return false;
		}
		$sql = sprintf("UPDATE coins SET spent=spent+%d WHERE id=%d",$amount,$row[0]['id']);
		DBConnectionHelper::getInstance()->query($sql);
		return true;
	}
		
	public static function amountByAccountId($account_id){
		$sql = sprintf("SELECT SUM(amount)-SUM(spent) as amount from coins WHERE `account_id`=%d",$account_id);
		$row = DBConnectionHelper::getInstance()->query($sql);
		if(sizeof($row)>0){
			$amount = $row[0]['amount'];
		}
		if($amount<=0){
			return 0;
		}
		return $amount;
	}
	
	public static function getByAccountId($account_id){
		$sql = sprintf("SELECT * from coins WHERE `account_id`=%d",$account_id);
		$rows = DBConnectionHelper::getInstance()->query($sql);
		$arr = array();
		foreach($rows AS $row){
			$obj = new CoinsProxy();
			foreach($row as $key => $value){
				$obj->{$key} = $value;
			}
			$arr[] = $obj;
		}
		return $arr;
	}

	public static function get( $id ){
		$sql = sprintf("SELECT * from coins WHERE `id`=%d",$id);
		$row = DBConnectionHelper::getInstance()->query($sql);
		if(sizeof($row)>0){
			$obj = new CoinsProxy();
			foreach($row[0] as $key => $value){
				$obj->{$key} = $value;
			}
			return $obj;
		}
		return null;
	}

	public static function filter( $filter ){
		$sql = sprintf("SELECT * FROM coins WHERE %s", $filter);
		$rows = DBConnectionHelper::getInstance()->query($sql);
		$arr = array();
		foreach($rows AS $row){
			$obj = new CoinsProxy();
			foreach($row as $key => $value){
				$obj->{$key} = $value;
			}
			$arr[] = $obj;
		}
		return $arr;
	}	
}
