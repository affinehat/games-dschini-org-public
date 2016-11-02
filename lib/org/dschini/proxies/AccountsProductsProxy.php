<?php
class AccountsProductsProxy
{
	public $id;
	public $created;
	public $modified;
	public $account_id;
	public $product_id;
	public $expires;
	
	public function __construct(){
	}
	
	public function save(){
		if(!isset($this->id)){
			$sql = sprintf("INSERT INTO `accounts_products` 
				( `created`,`modified`,`account_id`,`product_id`,`expires`) 
				VALUES (
					NOW(),
					NOW(),
					%d,
					%d,
					DATE_ADD(NOW(),INTERVAL 30 DAY)
					);"
				,DBConnectionHelper::getInstance()->escape($this->account_id)
				,DBConnectionHelper::getInstance()->escape($this->product_id)
				);
			DBConnectionHelper::getInstance()->execute($sql);
			$this->id = DBConnectionHelper::getInstance()->insert_id();
		} else {
			$sql = sprintf("UPDATE `accounts_products` 
				SET `modified`=NOW(), 
					`account_id`=%d, 
					`product_id`=%d, 
					`expires`=DATE_ADD(expires,INTERVAL 30 DAY)
				WHERE id=%d"
				,DBConnectionHelper::getInstance()->escape($this->account_id)
				,DBConnectionHelper::getInstance()->escape($this->product_id)
				,DBConnectionHelper::getInstance()->escape($this->id)
				);
			DBConnectionHelper::getInstance()->execute($sql);
		}
	}

	public static function get( $id ){
		$sql = sprintf("SELECT * from accounts_products WHERE `id`=%d"
			,DBConnectionHelper::getInstance()->escape($id)
		);
		$row = DBConnectionHelper::getInstance()->query($sql);
		if(sizeof($row)>0){
			$obj = new AccountsProductsProxy();
			foreach($row[0] as $key => $value){
				$obj->{$key} = $value;
			}
			return $obj;
		}
		return null;
	}

	public static function getUnlockedProductByAccount( $account_id, $product_id ){
		$sql = sprintf("SELECT * from accounts_products 
						WHERE `account_id`=%d 
						AND `product_id`=%d 
						AND modified <= expires
						LIMIT 0,1",
			DBConnectionHelper::getInstance()->escape($account_id),
			DBConnectionHelper::getInstance()->escape($product_id)
		);
		$row = DBConnectionHelper::getInstance()->query($sql);
		if(sizeof($row)>0){
			$obj = new AccountsProductsProxy();
			foreach($row[0] as $key => $value){
				$obj->{$key} = $value;
			}
			return $obj;
		}
		return null;
	}
	
	public static function expireDateIsTooOld( $account_id, $product_id ){
		$sql = sprintf("SELECT * from accounts_products 
						WHERE `account_id`=%d 
						AND `product_id`=%d 
						AND DATE_ADD(expires,INTERVAL 90 DAY) >= NOW()
						LIMIT 0,1",
			DBConnectionHelper::getInstance()->escape($account_id),
			DBConnectionHelper::getInstance()->escape($product_id)
		);
		$row = DBConnectionHelper::getInstance()->query($sql);
		if(sizeof($row)>0){
			return true;
		}
		return false;
	}

	public static function filter( $filter ){
		$sql = sprintf("SELECT * FROM accounts_products WHERE %s", $filter);
		$rows = DBConnectionHelper::getInstance()->query($sql);
		$arr = array();
		foreach($rows AS $row){
			$obj = new AccountsProductsProxy();
			foreach($row as $key => $value){
				$obj->{$key} = $value;
			}
			$arr[] = $obj;
		}
		return $arr;
	}	
}
