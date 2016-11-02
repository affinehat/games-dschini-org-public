<?php
/* 
 * RequestHelper
 *
 * @author	John Weber
 * @date	16/12/08
 * @see		http://John.dschini.org/
 */
class RequestHelper
{
	public $controller;
	public $action;
	public $params;
	
	public $userRightContainer;
	public $requiredRight;
	
	private static $instance = null;
	
	public function __construct(){
		$this->requiredRight = 0;
		$this->userRightContainer = 0;
	}
	
	public function setController($controller){
		$this->controller = $controller;
	}
	
	public function setAction($action){
		$this->action = $action;
	}

	public function addUserRight(&$userRightContainer,$right){
		$this->userRightContainer = $userRightContainer = $userRightContainer | pow(2,$right);
	}
	
	public function setRequiredRight($right){
		$this->requiredRight = pow(2,$right);
	}

	public function userHasRequiredRight(){
		if($this->userRightContainer & $this->requiredRight){
			return true;
		}
		return false;
	}
	
	public function setParams($params){
		$this->params = $this->numKeysOnly($params);
	}
	
	public function isController( $name ){
		if($this->controller == $name){
			return true;
		}
		return false;
	}

	public function isAction( $name ){
		if($this->action == $name){
			return true;
		}
		return false;
	}
	
	private static function numKeysOnly($params){
		$ret = array();
		for($i=1; $i<count($params); $i++){
			if(isset($params[$i])){
				$ret[] = $params[$i];
			}
		}
	    return $ret;
	}
	
	/*
	 * redirect
	 */
	public static function redirect( $target ){
		header("Location: ".$target);
		exit();
	}
	
	/*
	 * isPost
	 */
	public static function isPost() {
		if(isset($_POST)&&sizeof($_POST)>0){
			return true;
		}
		return false;
	}
	
	public static function getInstance(){
		if(!self::$instance){
			self::$instance = new RequestHelper();
		}
		return self::$instance;
	}
	
	public final function __clone() {
		trigger_error( "Cannot clone instance of Singleton pattern", E_USER_ERROR );
	}
}
