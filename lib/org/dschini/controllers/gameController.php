<?php
include_once('../config.php');

class gameController {

	public static $THEME = THEME_DEFAULT;
	
	public static $ALLOWED_SWF_IDS = array(1,2,3,4,5,6,7,8,10);
	public static $SECRET_SWF_IDS = array(6,7,8,10);

	public static function playByGameIdAction($swf_id){
		$ret = array();
		if(!$swf_id || !in_array($swf_id,self::$ALLOWED_SWF_IDS)){
			RequestHelper::redirect('/');
		}
		if($GLOBALS['games'][$swf_id]['coins']>0){
			if(!isset($_SESSION['accountId'])){
				RequestHelper::redirect('/login/');
			}
			$accountProduct = AccountsProductsProxy::getUnlockedProductByAccount($_SESSION['accountId'],$swf_id);
			if(!$accountProduct){
				RequestHelper::redirect('/game/'.$swf_id.'/'.$GLOBALS['games'][$swf_id]['cleanname'].'/?m=notaproduct');
			}
		}
		switch($swf_id){
			case 1: $ret['bgColor'] = '#efe4c9'; break;
			case 2: $ret['bgColor'] = '#0d2e52'; break;
			case 3: $ret['bgColor'] = '#a5bc8d'; break;
			case 4: $ret['bgColor'] = '#cccc99'; break;
			case 5: $ret['bgColor'] = '#f5ba6e'; break;
			case 6: $ret['bgColor'] = '#B9B699'; break;
			case 7: $ret['bgColor'] = '#99B3CC'; break;
            case 8: $ret['bgColor'] = '#6FB670'; break;
            case 10: $ret['bgColor'] = '#b4aaa5'; break;
		}
		$ret['swf_id'] = $ret['game_id'] = $swf_id;
		$ret['game_name'] = $GLOBALS['games'][$swf_id]['title'];
		$ret['cleanname'] = $GLOBALS['games'][$swf_id]['cleanname'];
		$ret['user'] = isset($_SESSION["user"]) ? $_SESSION["user"] : 'undefined';

		$ret['allTimeStarsByGameId'] = HighscoresProxy::allTimeStarsByGameId($swf_id);
        $ret['currentLeadershipByGameId'] = HighscoresProxy::currentLeadershipByGameId($swf_id);
        $ret['amountOfTotalGameplaysByGameId'] = HighscoresProxy::amountOfTotalGameplaysByGameId($swf_id);
        $ret['currentGameplaysByGameId'] = HighscoresProxy::currentGameplaysByGameId($swf_id);

		if(isset($_SESSION['accountId'])){
            $ret['myacount'] = AccountsProxy::get($_SESSION['accountId']);
            $ret['amountCoins'] = CoinsProxy::amountByAccountId($_SESSION['accountId']);
            $ret['accountProduct'] = AccountsProductsProxy::getUnlockedProductByAccount($_SESSION['accountId'],$swf_id);
        }

		$logsProxy = new LogsProxy();
		$logsProxy->type = LogsProxy::$TYPE_GAMESTART;
		$accountsProxy = AccountsProxy::getByUsername($ret['user']);
		if($accountsProxy){
			$logsProxy->value = serialize(array($ret['user'],$swf_id,$accountsProxy->email));
		} else {
			$logsProxy->value = serialize(array($ret['user'],$swf_id));
		}
		$logsProxy->public = 1;
		$logsProxy->save();
		return TemplateHelper::renderToResponse(self::$THEME,"html/games/play.phtml",$ret);
	}

	public static function positionsByGameIdAction($game_id){
		header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) .  "GMT" );
		header( "Cache-Control: no-cache, must-revalidate" );
		header( "Pragma: no-cache" );
		$ret = array();
		if(!$game_id){
			exit();
		}
		$ret['swf_id'] = $ret['game_id'] = $game_id;
		$ret['game_name'] = $GLOBALS['games'][$game_id]['title'];
		$ret['cleanname'] = $GLOBALS['games'][$game_id]['cleanname'];
		$user = isset($_SESSION["user"]) ? $_SESSION["user"] : 'undefined';
		$ret['positions'] = HighscoresProxy::positionsByGameId($game_id,$user);
		return TemplateHelper::renderToResponse(self::$THEME,"html/games/positions.phtml",$ret);
	}
	
	public static function scoresByGameIdAction($game_id,$page_no){
		$ret = array();
		if(!$game_id){
			exit();
		}
		if(!$game_id || !in_array($game_id,self::$ALLOWED_SWF_IDS)){
			RequestHelper::redirect('/');
		}
		
		$ret['amountDisplay'] = 30;
		$ret['swf_id'] = $ret['game_id'] = $game_id;
		$ret['game_name'] = $GLOBALS['games'][$game_id]['title'];
		$ret['cleanname'] = $GLOBALS['games'][$game_id]['cleanname'];
		$ret['limit_from'] = $page_no * $ret['amountDisplay'];
		$ret['limit_to'] = $ret['amountDisplay'] + 1;
		$ret['page_no'] = $page_no;
        $ret['games'] = $GLOBALS['games'];
		$ret['scores'] = HighscoresProxy::currentScoresByGameId($game_id,$ret['limit_from'],$ret['limit_to']);
		$ret['amountOfScores'] = HighscoresProxy::currentAmountOfScoresByGameId($game_id);
        $ret['position'] = HighscoresProxy::positionByGameId($game_id,$_SESSION["user"]);
        $monthlyTopscoresByGameId = json_decode(file_get_contents(BASEDIR.'cron/monthlyTopscoresByGameId'.$game_id.'.json'),true);
        $ret['monthlyTopscoresByGameId'] = $monthlyTopscoresByGameId['monthlyTopscoresByGameId'];
        return TemplateHelper::renderToResponse(self::$THEME,"html/games/scores.phtml",$ret);
	}
	
	public static function winnersByGameIdAction($game_id){
		$ret = array();
		if(!$game_id){
			exit();
		}
		if(!$game_id || !in_array($game_id,self::$ALLOWED_SWF_IDS)){
			RequestHelper::redirect('/');
		}
		$ret['swf_id'] = $ret['game_id'] = $game_id;
		$ret['game_name'] = $GLOBALS['games'][$game_id]['title'];
		$ret['cleanname'] = $GLOBALS['games'][$game_id]['cleanname'];
		$ret['position'] = HighscoresProxy::positionByGameId($game_id,$_SESSION["user"]);
		$ret['monthlyTopscoresByGameId'] = HighscoresProxy::monthlyTopscoresByGameId($game_id);
		return TemplateHelper::renderToResponse(self::$THEME,"html/games/winners.phtml",$ret);
	}
	
	public static function logsTimestampAction($timestamp = null,$amount = 18){
		if(!$timestamp){
			$timestamp = mktime();
		}
		header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) .  "GMT" );
		header( "Cache-Control: no-cache, must-revalidate" );
		header( "Pragma: no-cache" );
		$ret = array();
		$logs = LogsProxy::logsFromTimestamp($timestamp,10);
		$now = mktime();
		foreach($logs as $log){
			if(!isset($first)){
				$first = strtotime($log->created);
			}
			$created = strtotime($log->created)-$first;
			$ret[] = array(
						'created' => strtotime($log->created),
						'timeoutSec' => abs($created),
						'type' => $log->type,
						'value' => unserialize($log->value)
					);
		}
		echo json_encode($ret);
	}
	
	public static function logsLatestAction($amount = 10){
        $ret = array();
        $ret['logs'] = LogsProxy::logsByGameId(10);
        return TemplateHelper::renderToResponse(self::$THEME,"html/games/logs.phtml",$ret);
	}

	public static function logsByGameIdAction($game_id){
		header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) .  "GMT" );
		header( "Cache-Control: no-cache, must-revalidate" );
		header( "Pragma: no-cache" );
		$ret = array();
		if(!$game_id){
			exit();
		}
		$ret['swf_id'] = $ret['game_id'] = $game_id;
		$ret['game_name'] = $GLOBALS['games'][$game_id]['title'];
		$ret['cleanname'] = $GLOBALS['games'][$game_id]['cleanname'];
		$ret['logs'] = LogsProxy::logsByGameId($game_id);
		return TemplateHelper::renderToResponse(self::$THEME,"html/games/logs.phtml",$ret);
	}

	public static function savescoreAction($game_id){

		$ret = array();
		if(!$game_id || !in_array($game_id,self::$ALLOWED_SWF_IDS)){
			exit();
		}
		$game_id = InputFilterHelper::getInt($game_id);
		if(!$game_id){
			$ret["error"]["game_id"] = "game_id_invalid";
		}
		if(!in_array($game_id,self::$ALLOWED_SWF_IDS)){
			$ret["error"]["game_id"] = "game_id_invalid";
		}
		if(RequestHelper::isPost()){
			$ret["user"] = InputFilterHelper::getString($_POST['user']);

			if(strlen($ret["user"])<3 || strlen($ret["user"])>100){
				$ret["error"]["user"] = "user_invalid";
			}
			$ret["level"] = InputFilterHelper::getInt($_POST['level']);
			$ret["score"] = InputFilterHelper::getInt($_POST['score']);
			
			if(in_array($game_id,self::$SECRET_SWF_IDS)){
				$ret["secret"] = InputFilterHelper::getString($_POST['secret']);
				if(!$ret["secret"]){
					$ret["error"]["secret"] = "no_secret";
				}
				if(md5($ret["score"].SECRET)!=$ret["secret"]){
					$ret["error"]["secret"] = "secret_invalid";
				}
			}
			if(!isset($ret['error'])){
				$highscoresProxy = new HighscoresProxy();
				$highscoresProxy->game_id = $game_id;
				$highscoresProxy->user = $ret["user"];
				$highscoresProxy->level = $ret["level"];
				$highscoresProxy->score = $ret["score"];
				$highscoresProxy->save();
				
				$logsProxy = new LogsProxy();
				$logsProxy->type = LogsProxy::$TYPE_SAVESCORE;
				$accountsProxy = AccountsProxy::getByUsername($ret["user"]);
				
				if($accountsProxy){
					$earnedPoints = 0;
					if($ret["level"]>=5){
						$earnedPoints = 1;
					}
					if($ret["level"]>=10){
						$earnedPoints = 2;
					}
					$accountsProxy->points+=$earnedPoints;
					$accountsProxy->save();
					$logsProxy->value = serialize(array($ret["user"],$game_id,$ret["level"],$ret["score"],$accountsProxy->email));
					if($earnedPoints>0){
						$logsProxyPoints = new LogsProxy();
						$logsProxyPoints->type = LogsProxy::$TYPE_EARNEDPOINTS;
						$logsProxyPoints->value = serialize(array($ret["user"],$game_id,$earnedPoints,$accountsProxy->email));
						$logsProxyPoints->public = 1;
						$logsProxyPoints->save();
					}
				} else {
					$logsProxy->value = serialize(array($ret["user"],$game_id,$ret["level"],$ret["score"]));
				}
				$logsProxy->public = 1;
				$logsProxy->save();
			}
		}
	}

}
