<?php
/**
 * Created by PhpStorm.
 * User: Johnweber
 * Date: 28.05.14
 * Time: 00:36
 */

include("lib/org/dschini/proxies/HighscoresProxy.php");
include("lib/org/dschini/helpers/MySQLiDriverHelper.php");
//include("lib/org/dschini/helpers/TemplateHelper.php");

define('DATABASE_NAME', 'games_dschini_org');
define('DATABASE_USER', 'John');
define('DATABASE_PASS', 'secret-pwd-pls-change');
define('DATABASE_HOST', 'localhost');
define('DATABASE_PORT', 3306);

$gameIds = array(1,2,3,4,5,6,7,8,9,10);

//$game_id = 10;

$ret['overallTopScores'] = array();
foreach( $gameIds as $game_id ){
    $ret['overallTopScores'][$game_id] = HighscoresProxy::overallTopScoresByGameId($game_id);
}
file_put_contents('cron/dailyOverallTopScores.json',json_encode($ret));
