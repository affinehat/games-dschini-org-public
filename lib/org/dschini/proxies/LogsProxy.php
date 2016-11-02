<?php
class LogsProxy
{
	public $id;
	public $created;
	public $type;
	public $value;
	public $public;
	
	public static $TYPE_GAMESTART = "gamestart";
	public static $TYPE_ACCOUNTAPPROVED = "accountapproved";
	public static $TYPE_SAVESCORE = "savescore";
	public static $TYPE_LOGIN = "login";
	public static $TYPE_EARNEDPOINTS = "earnedpoints";

	public function save(){
		if(!isset($this->id)){
			$sql = sprintf("INSERT INTO `logs`
							(`created`,`type`,`value`,`public`) 
							VALUES 
							(now(),'%s','%s',%d);"
				,DBConnectionHelper::getInstance()->escape($this->type)
				,DBConnectionHelper::getInstance()->escape($this->value)
				,DBConnectionHelper::getInstance()->escape($this->public)
				);
				DBConnectionHelper::getInstance()->execute($sql);
			$this->id = DBConnectionHelper::getInstance()->insert_id();
		} else {
			$sql = sprintf("UPDATE `logs` SET `type`='%s', `value`='%s', `public`=%d WHERE id=%d"
				,DBConnectionHelper::getInstance()->escape($this->type)
				,DBConnectionHelper::getInstance()->escape($this->value)
				,DBConnectionHelper::getInstance()->escape($this->public)
				,DBConnectionHelper::getInstance()->escape($this->id)
				);
			DBConnectionHelper::getInstance()->execute($sql);
		}
	}

	public static function get( $id ){
		$sql = sprintf("SELECT * from logs WHERE `id`=%d",$id);
		$row = DBConnectionHelper::getInstance()->query($sql);
		if(sizeof($row)>0){
			$obj = new LogsProxy();
			foreach($row[0] as $key => $value){
				$obj->{$key} = $value;
			}
			return $obj;
		}
		return null;
	}

	public static function logsFromTimestamp($timestamp=null,$amount=20){
		if(!$timestamp){
			$timestamp = strtotime();
		}
		$sql = sprintf("SELECT * FROM logs 
						WHERE UNIX_TIMESTAMP(created)>%d 
						ORDER BY created DESC 
						LIMIT 0,%d"
						,$timestamp,$amount);
		$rows = DBConnectionHelper::getInstance()->query($sql);
        $arr = array();
        foreach($rows AS $row){
			$obj = new LogsProxy();
        	foreach($row as $key => $value){
            	$obj->{$key} = $value;
			}
            $arr[] = $obj;
		}
		return $arr;
	}
	
	public static function logsLatest($amount=10){
		$sql = sprintf("SELECT * FROM logs ORDER BY created DESC LIMIT 0,%d"
						,$amount);
		$rows = DBConnectionHelper::getInstance()->query($sql);
        $arr = array();
        foreach($rows AS $row){
			$obj = new LogsProxy();
        	foreach($row as $key => $value){
            	$obj->{$key} = $value;
			}
            $arr[] = $obj;
		}
		return $arr;
	}

	public static function logsByGameId($game_id,$amount=10){
		$sql = sprintf("SELECT * FROM logs WHERE type='%s' OR type='%s' ORDER BY created DESC LIMIT 0,%d"
						,self::$TYPE_SAVESCORE,self::$TYPE_EARNEDPOINTS,$amount);
		$rows = DBConnectionHelper::getInstance()->query($sql);
        $arr = array();
        foreach($rows AS $row){
			$obj = new LogsProxy();
        	foreach($row as $key => $value){
            	$obj->{$key} = $value;
			}
            $arr[] = $obj;
		}
		return $arr;
	}
	
	public static function latestPublic($amount=15){
		$sql = sprintf("SELECT * FROM logs ORDER BY created DESC LIMIT 0,%d",$amount);
        $rows = DBConnectionHelper::getInstance()->query($sql);
        $arr = array();
        foreach($rows AS $row){
			$obj = new LogsProxy();
        	foreach($row as $key => $value){
            	$obj->{$key} = $value;
			}
            $arr[] = $obj;
		}
		return $arr;
	}
		
	public static function filter( $filter ){
		$sql = sprintf("SELECT * FROM logs WHERE %s", $filter);
		$rows = DBConnectionHelper::getInstance()->query($sql);
		$arr = array();
		foreach($rows AS $row){
			$obj = new LogsProxy();
			foreach($row as $key => $value){
				$obj->{$key} = $value;
			}
			$arr[] = $obj;
		}
		return $arr;
	}	
}
