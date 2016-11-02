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
	private $affectedRows;
	
	public function __construct(){
		try {
			$this->link = new PDO('mysql:host='.DATABASE_HOST.';dbname='.DATABASE_NAME, DATABASE_USER, 
				DATABASE_PASS, array(PDO::ERRMODE_EXCEPTION => true, PDO::ATTR_PERSISTENT => true));
			//$this->link->query('SET NAMES utf8');
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	
	public static function getInstance(){
		if (!self::$instance){
			self::$instance = new DBConnectionHelper();
		}
		return self::$instance;
	}

    public function execute($sql){
        try {
            $stmt = $this->link->prepare(addslashes($sql));
            $stmt->execute(array());
            return "ok";
        } catch(PDOException $e){
            return "error";
        }
    }

    public function execute2($sql){
        try {
            $stmt = $this->link->prepare($sql);
            $stmt->execute(array());
            return "ok";
        } catch(PDOException $e){
            return "error";
        }
    }
	
	public function query($sql){
	    $result = $this->link->query($sql);
		$arr = array();
		if(is_object($result)){
			$arr =  $result->fetchAll(PDO::FETCH_ASSOC);
			$this->affectedRows = count($arr);
		}
		return $arr;
	}
	
	public function __destruct(){
		//mysqli_close($this->link);
	}
	
	public function escape($str){
        return $str;
        //return $this->link->quote($str);
	}
	
	public function insert_id(){
		return DBConnectionHelper::getInstance()->link->lastInsertId();
	}
	
	public function affected_rows(){
		return $this->affectedRows;
	}

}
