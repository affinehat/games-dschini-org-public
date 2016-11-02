<?php
include_once('../config.php');

class pageController {

	public static $THEME = THEME_DEFAULT;

    public static function indexAction(){
        $ret = array();
        $ret['games'] = $GLOBALS['games'];
        //$ret['amountOfTotalGameplays'] = HighscoresProxy::amountOfTotalGameplays();
        if(isset($_SESSION['accountId'])){
            $ret['myacount'] = AccountsProxy::get($_SESSION['accountId']);
            //$ret['amountCoins'] = CoinsProxy::amountByAccountId($_SESSION['accountId']);
        }
        //shuffle($ret['games']);
        return TemplateHelper::renderToResponse(self::$THEME,"html/pages/index.phtml",$ret);
    }

	public static function lobbyAction(){
		$ret = array();
		$overallTopScores = json_decode(file_get_contents(BASEDIR.'cron/dailyOverallTopScores.json'),true);
		$ret['overallTopScores'] = $overallTopScores['overallTopScores'];


		$ret['leadershipAccounts'] = AccountsProxy::leadershipAccounts(10);
		$ret['randomAccounts'] = AccountsProxy::randomAccounts(10);

		$ret['games'] = $GLOBALS['games'];
		//$ret['amountOfTotalGameplays'] = HighscoresProxy::amountOfTotalGameplays();
		if(isset($_SESSION['accountId'])){
			$ret['myacount'] = AccountsProxy::get($_SESSION['accountId']);
			//$ret['amountCoins'] = CoinsProxy::amountByAccountId($_SESSION['accountId']);
		}
		//shuffle($ret['games']);
		return TemplateHelper::renderToResponse(self::$THEME,"html/pages/lobby.phtml",$ret);
	}

	public static function chatAction(){
		$ret = array();
		return TemplateHelper::renderToResponse(self::$THEME,"html/pages/chat.phtml",$ret);
	}
	
	public static function gameAction($game_id){
		$ret = array();
		if(!$game_id || !in_array($game_id,gameController::$ALLOWED_SWF_IDS)){
                        RequestHelper::redirect('/');
                }
		$ret['logs'] = LogsProxy::latestPublic(4);
		//$ret['leadershipAccounts'] = AccountsProxy::leadershipAccounts(7);
		$ret['game'] = $GLOBALS['games'][$game_id];
		$ret['game_name'] = $GLOBALS['games'][$game_id]['title'];
		$ret['cleanname'] = $GLOBALS['games'][$game_id]['cleanname'];
		$ret['swf_id'] = $ret['game_id'] = $game_id;
		$ret['allTimeStarsByGameId'] = HighscoresProxy::allTimeStarsByGameId($game_id);
		$ret['currentLeadershipByGameId'] = HighscoresProxy::currentLeadershipByGameId($game_id);
		$ret['amountOfTotalGameplaysByGameId'] = HighscoresProxy::amountOfTotalGameplaysByGameId($game_id);
		$ret['currentGameplaysByGameId'] = HighscoresProxy::currentGameplaysByGameId($game_id);
		if(isset($_SESSION['accountId'])){
			$ret['myacount'] = AccountsProxy::get($_SESSION['accountId']);
			$ret['amountCoins'] = CoinsProxy::amountByAccountId($_SESSION['accountId']);
			$ret['accountProduct'] = AccountsProductsProxy::getUnlockedProductByAccount($_SESSION['accountId'],$game_id);
		}
		//shuffle($ret['games']);
		return TemplateHelper::renderToResponse(self::$THEME,"html/pages/game.phtml",$ret);
	}
	
	public static function aboutAction(){
		$ret = array();
		$ret['swf_id'] = 'live';
		$ret['bgColor'] = '#ffffff';
		$ret['latestAccounts'] = AccountsProxy::latestAccounts(5);
		$ret['logs'] = LogsProxy::latestPublic(5);
		if(isset($_SESSION['accountId'])){
			$ret['amountCoins'] = CoinsProxy::amountByAccountId($_SESSION['accountId']);
		}
		return TemplateHelper::renderToResponse(self::$THEME,"html/pages/about.phtml",$ret);
	}

	public static function termsconditionsAction(){
		$ret = array();
		if(isset($_SESSION['accountId'])){
			$ret['amountCoins'] = CoinsProxy::amountByAccountId($_SESSION['accountId']);
		}
		return TemplateHelper::renderToResponse(self::$THEME,"html/pages/termsconditions.phtml",$ret);
	}

	public static function donateAction(){
		$ret = array();
		return TemplateHelper::renderToResponse(self::$THEME,"html/pages/donate.phtml",$ret);
	}

}
