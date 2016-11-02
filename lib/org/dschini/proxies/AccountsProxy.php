<?php
class AccountsProxy
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
	
	public function sendAccountMail()
	{
		if(!$this->email||!$this->password){
			echo "Error: email does not exists!";
			exit();
		}
		$headers = 'From: noreply@dschini.org';
		$subject = 'dschini.org - Your login data!';
		$message = 'Your login data:'."\r\n";
		$message.= 'Username: '.$this->username." \r\n";
		$message.= 'Passwort: '.$this->password." \r\n";
		$message.= "\r\n";
		$message.= 'User the link below to login:'."\r\n";
		$message.= 'http://games.dschini.org/login/'."\r\n";
		$message.= "\r\n";
		$message.= 'Thank you and enjoy the games!'."\r\n";
		$message.= 'http://games.dschini.org'."\r\n";
		mail($this->email, $subject, $message, $headers);
	}
	
	public function sendActivateMail()
	{
		$headers = 'From: noreply@dschini.org';
		$subject = 'dschini.org - Please approve your account!';
		$message = 'Please approve your email by clicking on the following link:'."\r\n";
		$message.= "\r\n";
		$message.= 'http://games.dschini.org/activate/'.$this->uid."/\r\n";
		$message.= "\r\n";
		$message.= 'Thank you and enjoy the games!'."\r\n";
		$message.= 'http://games.dschini.org'."\r\n";
		mail($this->email, $subject, $message, $headers);
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
		$sql = sprintf("SELECT * from accounts WHERE `id`=%d",$id);
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

	public static function getByUID( $uid ){
		$sql = sprintf("SELECT * from accounts WHERE `uid`='%s'",
			DBConnectionHelper::getInstance()->escape($uid)
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
		$sql = sprintf("SELECT * from accounts WHERE `username`='%s'",
			DBConnectionHelper::getInstance()->escape($username)
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

    public static function leadershipAccounts($amount=30){
        if($GLOBALS['memcache']->get('AccountsProxy-leadershipAccounts-'.$amount)) {
            return $GLOBALS['memcache']->get('AccountsProxy-leadershipAccounts-'.$amount);
        }
        $sql = sprintf("SELECT * FROM accounts  where approved=1 ORDER BY points DESC LIMIT 0,%d",$amount);
        $rows = DBConnectionHelper::getInstance()->query($sql);
        $arr = array();
        foreach($rows AS $row){
            $obj = new AccountsProxy();
            foreach($row as $key => $value){
                $obj->{$key} = $value;
            }
            $arr[] = $obj;
        }
        $GLOBALS['memcache']->add('AccountsProxy-leadershipAccounts-'.$amount, $arr, 0, 5*60);
        return $arr;
    }

    public static function randomAccounts($amount=30){
        if($GLOBALS['memcache']->get('AccountsProxy-randomAccounts-'.$amount)) {
            return $GLOBALS['memcache']->get('AccountsProxy-randomAccounts-'.$amount);
        }
        $sql = sprintf("SELECT * FROM accounts where approved=1 and points>100 ORDER BY RAND() LIMIT 0,%d",$amount);
        $rows = DBConnectionHelper::getInstance()->query($sql);
        $arr = array();
        foreach($rows AS $row){
            $obj = new AccountsProxy();
            foreach($row as $key => $value){
                $obj->{$key} = $value;
            }
            $arr[] = $obj;
        }
        $GLOBALS['memcache']->add('AccountsProxy-randomAccounts-'.$amount, $arr, 0, 5*60);
        return $arr;
    }
	
	public static function latestAccounts($amount=30){
		$sql = sprintf("SELECT * FROM accounts where approved=1 ORDER BY created DESC LIMIT 0,%d",$amount);
        $rows = DBConnectionHelper::getInstance()->query($sql);
        $arr = array();
        foreach($rows AS $row){
			$obj = new AccountsProxy();
        	foreach($row as $key => $value){
            	$obj->{$key} = $value;
			}
            $arr[] = $obj;
		}
		return $arr;
	}
	
	public static function amountApproved(){
		$sql = sprintf("SELECT count(*) as amount FROM accounts WHERE approved=1");
        $rows = DBConnectionHelper::getInstance()->query($sql);
        return $rows[0]['amount'];
	}
		
	public static function getByEMail( $email ){
		$sql = sprintf("SELECT * from accounts WHERE `email`='%s'",$email);
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

	public static function getByEMailAndPassword( $email, $password ){
		$sql = sprintf("SELECT * from accounts WHERE `email`='%s' AND `password`='%s'",$email,$password);
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

	public static function getByUsernameAndPassword( $username, $password ){
		$sql = sprintf("SELECT * from accounts WHERE `username`='%s' AND `password`='%s'",$username,$password);
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
	
	public static function filter( $filter ){
		$sql = sprintf("SELECT * FROM accounts WHERE %s", $filter);
		$rows = DBConnectionHelper::getInstance()->query($sql);
		$arr = array();
		foreach($rows AS $row){
			$obj = new AccountsProxy();
			foreach($row as $key => $value){
				$obj->{$key} = $value;
			}
			$arr[] = $obj;
		}
		return $arr;
	}	
}
