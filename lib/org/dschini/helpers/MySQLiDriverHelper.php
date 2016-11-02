<?php
class DBConnectionHelper
{
	public static $NAME = DATABASE_NAME;
	public static $USER = DATABASE_USER;
	public static $PASS = DATABASE_PASS;
	public static $HOST = DATABASE_HOST;
	public static $PORT = DATABASE_PORT;
	
	static private $instance = false;
	
	public $link;
	
	public function __construct(){
		$this->link = mysqli_connect(DBConnectionHelper::$HOST, DBConnectionHelper::$USER, DBConnectionHelper::$PASS, DBConnectionHelper::$NAME)
				or die('Could not connect: ' . mysqli_error($this->link));
		mysqli_query($this->link, "SET NAMES utf8") or die('Could not select database');
	}
	
	public static function getInstance(){
		if (!self::$instance){
			self::$instance = new DBConnectionHelper();
		}
		return self::$instance;
	}
	
	public function execute($sql){
		mysqli_query($this->link, $sql) or die('Query failed: ' . mysqli_error($this->link));
	}
	
	public function query($sql){
		$result = mysqli_query($this->link, $sql) or die('Query failed: ' . mysqli_error($this->link));
		$arr = array();
		if(is_object($result)){
			while($row = mysqli_fetch_assoc($result)){
				$arr[] = $row;
			}
			mysqli_free_result($result);
		}
		return $arr;
	}
	
	public function __destruct(){
		mysqli_close($this->link);
	}
	
	public function escape($str){
		return mysqli_real_escape_string(DBConnectionHelper::getInstance()->link,$str);
	}
	
	public function insert_id(){
		return mysqli_insert_id(DBConnectionHelper::getInstance()->link);
	}
	
	public function affected_rows(){
		return mysqli_affected_rows(DBConnectionHelper::getInstance()->link);
	}

}
