<?php
class UsersProxy
{
	public $id;
	public $uid;
	public $created;
	public $approved;
	public $username;
	public $password;
	public $informme;
	public $email;
	public $points;
	
	public function __construct(){
	}
	
	public function save(){
		if(!isset($this->id)){
			$sql = sprintf("INSERT INTO `accounts` 
				(`uid`,`created`,`approved`,`username`,`password`,`email`,`informme`,`points`) 
				VALUES 
				('%s',now(),%d,'%s','%s','%s',%b,%d);"
				,DBConnectionHelper::getInstance()->escape($this->uid)
				,DBConnectionHelper::getInstance()->escape($this->approved)
				,DBConnectionHelper::getInstance()->escape($this->username)
				,DBConnectionHelper::getInstance()->escape($this->password)
				,DBConnectionHelper::getInstance()->escape($this->email)
				,DBConnectionHelper::getInstance()->escape($this->informme)
				,DBConnectionHelper::getInstance()->escape($this->points)
				);
			DBConnectionHelper::getInstance()->execute($sql);
			$this->id = DBConnectionHelper::getInstance()->insert_id();
		} else {
			$sql = sprintf("UPDATE `accounts` 
				SET `uid`='%s', 
					`approved`=%d, 
					`username`='%s', 
					`password`='%s', 
					`email`='%s', 
					`informme`=%d, 
					`points`=%d
				WHERE id=%d"
				,DBConnectionHelper::getInstance()->escape($this->uid)
				,DBConnectionHelper::getInstance()->escape($this->approved)
				,DBConnectionHelper::getInstance()->escape($this->username)
				,DBConnectionHelper::getInstance()->escape($this->password)
				,DBConnectionHelper::getInstance()->escape($this->email)
				,DBConnectionHelper::getInstance()->escape($this->informme)
				,DBConnectionHelper::getInstance()->escape($this->points)
				,DBConnectionHelper::getInstance()->escape($this->id)
				);
			DBConnectionHelper::getInstance()->execute($sql);
		}
	}

	public static function get( $id ){
		$sql = sprintf("SELECT * from accounts WHERE `id`=%d"
			,DBConnectionHelper::getInstance()->escape($id)
		);
		$row = DBConnectionHelper::getInstance()->query($sql);
		if(sizeof($row)>0){
			$obj = new AccountsProxy();
			foreach($row[0] as $key => $value){
				$obj->{$key} = $value;
			}
			return $obj;
		}
		return null;
	}

	public static function getByUsername( $username ){
		$sql = sprintf("SELECT * from accounts WHERE `username`='%s'"
			,DBConnectionHelper::getInstance()->escape($username)
		);
		$row = DBConnectionHelper::getInstance()->query($sql);
		if(sizeof($row)>0){
			$obj = new AccountsProxy();
			foreach($row[0] as $key => $value){
				$obj->{$key} = $value;
			}
			return $obj;
		}
		return null;
	}

}
