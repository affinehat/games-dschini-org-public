<?php
include_once('../config.php');

class accountController {

	public static $THEME = THEME_DEFAULT;

    public static function latestAccountsAction(){
        header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
        header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) .  "GMT" );
        header( "Cache-Control: no-cache, must-revalidate" );
        header( "Pragma: no-cache" );
        $ret = array();
        $ret['latestAccounts'] = AccountsProxy::latestAccounts(10);

        /*
        $ret['swf_id'] = $ret['game_id'] = $game_id;
        $ret['game_name'] = $GLOBALS['games'][$game_id]['title'];
        $ret['cleanname'] = $GLOBALS['games'][$game_id]['cleanname'];
        $ret['logs'] = LogsProxy::logsByGameId($game_id);
        */
        return TemplateHelper::renderToResponse(self::$THEME,"html/accounts/latest.phtml",$ret);

    }

    public static function usernameAction(){
		$ret = array();
		$ret["next"] = isset($_GET["next"]) ? InputFilterHelper::getString($_GET["next"]) : '/';
		if(isset($_SESSION["user"])){
			RequestHelper::redirect('/');
		}
		if(RequestHelper::isPost())
		{
			/* security check */
			if(!isset($_SERVER['HTTP_REFERER'])){
				RequestHelper::redirect('/');
			}
			$url = parse_url($_SERVER['HTTP_REFERER']);
			if(!isset($url['host']) || !in_array($url['host'],$GLOBALS['ALLOWED_HOSTS'])){
				RequestHelper::redirect('/');
			}

            if(!isset($_POST["user"])){
                $ret["error"] = "Please enter a Username!";
            } else {
                $ret["user"] = InputFilterHelper::getString($_POST["user"]);
                if (!preg_match('/^[a-z\d_]{4,15}$/i', $ret["user"])) {
                    $ret["error"] = "The Username is not valid.";
                }
                /* check if user exists */
                if(!isset($ret['error'])){
                    $accountsProxy = AccountsProxy::getByUsername($ret["user"]);
                    if($accountsProxy){
                        $ret["error"] = "The Username is already taken.";
                    }
                }
            }
			/* write to session */
			if(!isset($ret['error'])){
				$_SESSION["user"] = $ret["user"];
				RequestHelper::getInstance()->addUserRight($_SESSION['userRight'],RIGHT_PLAYER);
				RequestHelper::redirect($ret["next"]);
			}
		}
		return TemplateHelper::renderToResponse(self::$THEME,"/html/accounts/username.phtml",$ret);
	}

	public static function loginAction(){
		$ret = array();
		$ret["next"] = isset($_GET["next"]) ? InputFilterHelper::getString($_GET["next"]) : '/lobby/';
		if(RequestHelper::isPost())
		{
            if(!isset($_POST["password"])){
                $ret["error"] = "Please enter a EMail address!";
            } else {
                $ret["password"] = InputFilterHelper::getString($_POST["password"]);
                if (!preg_match('/^[a-z\d_]{4,15}$/i', $ret["password"])) {
                    $ret["error"] = "Password is not valid.";
                }
            }
            if(!isset($_POST["user"])){
                $ret["error"] = "Please enter a Username!";
            } else {
                $ret["user"] = InputFilterHelper::getString($_POST["user"]);
                if (!preg_match('/^[a-z\d_]{4,15}$/i', $ret["user"])) {
                    $ret["error"] = "Username is not valid.";
                }
                /* check if user exists */
                if(!isset($ret['error'])){
                    $accountsProxy = AccountsProxy::getByUsernameAndPassword($ret["user"],$ret["password"]);
                    if(!$accountsProxy){
                        $ret["error"] = "Could not login!";
                    }
                }
            }
			/* write to session */
			if(!isset($ret['error'])){
				$accountsProxy->points++;
				$accountsProxy->save();
				$_SESSION["accountId"] = $accountsProxy->id;
				$_SESSION["user"] = $ret["user"];
				$_SESSION["approved"] = true;
				$_SESSION["email"] = $accountsProxy->email;
				$logsProxy = new LogsProxy();
				$logsProxy->type = LogsProxy::$TYPE_LOGIN;
				$logsProxy->value = serialize(array($accountsProxy->username,$accountsProxy->email));
				$logsProxy->public = 1;
				$logsProxy->save();
				RequestHelper::getInstance()->addUserRight($_SESSION['userRight'],RIGHT_PLAYER);
				RequestHelper::getInstance()->addUserRight($_SESSION['userRight'],RIGHT_MEMBER);
				RequestHelper::redirect($ret["next"]);
			}
		}
		return TemplateHelper::renderToResponse(self::$THEME,"/html/accounts/login.phtml",$ret);
	}

	public static function logoutAction(){
		$ret = array();
		$_SESSION['user'] = null;
		$_SESSION['userRight'] = null;
		$_SESSION['approved'] = null;
		$_SESSION['accountId'] = null;
		unset($_SESSION['user']);
		unset($_SESSION['userRight']);
		unset($_SESSION['approved']);
		unset($_SESSION['accountId']);
		return TemplateHelper::renderToResponse(self::$THEME,"/html/accounts/logout.phtml",$ret);
	}
	
	public static function activateAction($uid){
		$uid = InputFilterHelper::getString($uid);
		if(!$uid){
			RequestHelper::redirect('/register/');
		}
		
		$ret = array();
		$ret["account"] = AccountsProxy::getByUID($uid);
		if(!$ret["account"]){
			RequestHelper::redirect('/register/');
		}
		if($ret["account"]->approved == 1){
			RequestHelper::redirect('/');
		}
		if($ret["account"]->approved == 0){
			$ret["account"]->approved = 1;
			$ret["account"]->points = 10;
			$ret["account"]->save();
			$ret["account"]->sendAccountMail();
		}
		/*
		$coinsProxy = new CoinsProxy();
		$coinsProxy->account_id = $ret["account"]->id;
		$coinsProxy->type = CoinsProxy::$TYPE_GIFT;
		$coinsProxy->amount = 1;
		$coinsProxy->spent = 0;
		$coinsProxy->body = 'Present from games.dschini.org';
		$coinsProxy->save();
		*/
		$logsProxy = new LogsProxy();
		$logsProxy->type = LogsProxy::$TYPE_ACCOUNTAPPROVED;
		$logsProxy->value = serialize(array($ret["account"]->username,$ret["account"]->email));
		$logsProxy->public = 1;
		$logsProxy->save();
		return TemplateHelper::renderToResponse(self::$THEME,"/html/accounts/activate.phtml",$ret);
	}
	
	public static function lostpasswordstep1Action(){
		$ret = array();
		if(RequestHelper::isPost())
		{
            $nr1 = InputFilterHelper::getInt($_POST["nr1"]);
            $nr2 = InputFilterHelper::getInt($_POST["nr2"]);
            $total = InputFilterHelper::getInt($_POST["total"]);
            if($nr1+$nr2!=$total){
                $ret["error"] = "Failed security check";
            }
            // email
            if(!isset($_POST["email"])){
                $ret["error"] = "Please enter a EMail address!";
            } else {

                $ret["email"] = InputFilterHelper::getString($_POST["email"]);

                if(!isset($ret['error'])){
                    $accountsProxy = AccountsProxy::getByEMail($ret['email']);
                    if($accountsProxy){
                        $accountsProxy->sendAccountMail();
                        $accountsProxy->save();
                        RequestHelper::redirect('/lostpassword/step2/');
                    } else {
                        $ret["error"] = "This EMail address does not exist.";
                    }
                }

                if(strlen($ret["email"])>100){
                    $ret["error"] = "The Email address you entered is not valid.";
                }
                if(!preg_match("/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/", $ret["email"])){
                    $ret["error"] = "The Email address you entered is not valid.";
                }
            }

	    }
		$ret["nr1"] = rand(0,9);
		$ret["nr2"] = rand(0,9);
		return TemplateHelper::renderToResponse(self::$THEME,"/html/accounts/lostpassword_step1.phtml",$ret);
	}
	
	public static function lostpasswordstep2Action(){
		$ret = array();
		return TemplateHelper::renderToResponse(self::$THEME,"/html/accounts/lostpassword_step2.phtml",$ret);
	}

    public static function registerstep1Action(){
		$ret = array();
		if(isset($_SESSION['user'])){
			$ret['user'] = $_SESSION['user'];
		}
		$ret['informme'] = 1;
		
		if(RequestHelper::isPost())
		{
            /* security check */
            $nr1 = InputFilterHelper::getInt($_POST["nr1"]);
            $nr2 = InputFilterHelper::getInt($_POST["nr2"]);
            $total = InputFilterHelper::getInt($_POST["total"]);
            if($nr1+$nr2!=$total){
                $ret["error"] = "Failed security check";
            }

            // user
            if(!isset($_POST["user"])){
                $ret["error"] = "Please enter a username!";
            } else {
                $ret["user"] = InputFilterHelper::getString($_POST["user"]);
                if (!preg_match('/^[a-z\d_]{4,15}$/i', $ret["user"])) {
                    $ret["error"] = "Username is invalid.";
                }
                if($ret["user"]){
                    $ret['account'] = AccountsProxy::getByUsername($ret["user"]);
                }
                if(isset($ret['account'])){
                    $ret["error"] = "The username already exists.";
                }
            }
            // email
            if(!isset($_POST["email"])){
                $ret["error"] = "Please enter a email address.";
            } else {
                $ret["email"] = InputFilterHelper::getString($_POST["email"]);
                if(strlen($ret["email"])>100){
                    $ret["error"] = "The email address is wrong.";
                }
                if(!preg_match("/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/", $ret["email"])){
                    $ret["error"] = "The email address is not valid.";
                }
                if(!isset($_POST['informme'])){
                    $ret['informme'] = 0;
                }
            }
			/* check if account exists */
			if(!isset($ret['error'])){
				$accountsProxy = AccountsProxy::getByEMail($ret['email']);
				if($accountsProxy){
					$ret["error"] = "The email already exists.";
				}
			}

			/* account not exists - create new account */
			if(!isset($ret['error'])){
				if(!$accountsProxy){
					$accountsProxy = new AccountsProxy();
					$accountsProxy->uid = UIDHelper::MD5();
					$accountsProxy->approved = 0;
					$accountsProxy->username = $ret["user"];
					$accountsProxy->email = $ret['email'];
					$accountsProxy->password = PasswordHelper::create();
					$accountsProxy->informme = $ret['informme'];
					$accountsProxy->sendActivateMail();
					$accountsProxy->save();
					$_SESSION["user"] = $ret["user"];
				}
				RequestHelper::redirect('/register/step2/');
			}
	    }
		$ret["nr1"] = rand(0,9);
		$ret["nr2"] = rand(0,9);
		return TemplateHelper::renderToResponse(self::$THEME,"/html/accounts/register_step1.phtml",$ret);
	}
	
	public static function registerstep2Action(){
		$ret = array();
		return TemplateHelper::renderToResponse(self::$THEME,"/html/accounts/register_step2.phtml",$ret);
	}

}
