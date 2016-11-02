<?php
/*
 * DBConnectionHelper
 * @author	John Weber
 * @date	30/11/08
 * @see		http://dschini.googlecode.com/
 */
class DBConnectionHelper {
	
	/*
	 * db settings
	 */
	public static $NAME = DATABASE_NAME;
	public static $USER = DATABASE_USER;
	public static $PASS = DATABASE_PASS;
	public static $HOST = DATABASE_HOST;
	public static $PORT = DATABASE_PORT;
	
	static private $instance = false;
	
	public $link;
	
	public function __construct(){
		$this->link = mysql_pconnect(DBConnectionHelper::$HOST.":".DBConnectionHelper::$PORT, DBConnectionHelper::$USER, DBConnectionHelper::$PASS)
				or die('Could not connect: ' . mysql_error($this->link));
		mysql_select_db ( DBConnectionHelper::$NAME , $this->link );
		mysql_query("SET NAMES utf8",$this->link) or die('Could not select database');
	}
	
	/*
	 * getInstance
	 */
	public static function getInstance(){
		if (!self::$instance){
			self::$instance = new DBConnectionHelper();
		}
		return self::$instance;
	}
	
	/*
	 * execute
	 */
	public function execute($sql){
		mysql_query($sql, DBConnectionHelper::getInstance()->link) or die('Query failed: ' . mysql_error($this->link));
	}
	
	/*
	 * query
	 */
	public function query($sql){
		$result = mysql_query($sql,$this->link) or die('Query failed: ' . mysql_error($this->link));
		$arr = array();
		if(is_resource($result)){
			while($row = mysql_fetch_assoc($result)){
				$arr[] = $row;
			}
			mysql_free_result($result);
		}
		return $arr;
	}
	

	/*
	 * escape
	 */
	public function escape($str){
		return mysql_real_escape_string($str,DBConnectionHelper::getInstance()->link);
	}
	
	/*
	 * insert_id
	 */
	public function insert_id(){
		return mysql_insert_id(DBConnectionHelper::getInstance()->link);
	}
	
	/*
	 * affected_rows
	 */
	public function affected_rows(){
		return mysql_affected_rows(DBConnectionHelper::getInstance()->link);
	}

}
