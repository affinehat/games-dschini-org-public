<?php
/* 
 * The config file!
 *
 * @author	John Weber
 * @date		20/11/08
 * @see		http://John.dschini.org/
 */

ini_set('display_errors',true);
ini_set('error_reporting', E_ALL);
ini_set('log_errors',1);

/* defines here */
define("SECRET", '90dsfg987dfg987dfg132p351x421bh');
define('DATABASE_NAME', 'games_dschini_org');
define('DATABASE_USER', 'John');
define('DATABASE_PASS', 'secret-pwd-pls-change');
define('DATABASE_HOST', 'localhost');
define('DATABASE_PORT', 3306);
define('BASEDIR' ,dirname($_SERVER['DOCUMENT_ROOT']).DIRECTORY_SEPARATOR);

define("THEME_FACEBOOK" ,BASEDIR."themes/facebook/");
define("THEME_DEFAULT" ,BASEDIR."themes/html5up/");

define("ADMIN_EMAIL", 'you@mail.com');

define("RIGHT_PUBLIC", 0);
define("RIGHT_PLAYER", 1);
define("RIGHT_MEMBER", 2);


$GLOBALS['ALLOWED_HOSTS'] = array(
    '192.168.1.100',
    'play.dschini.org',
    'games.dschini.org',
    'gamesdev.dschini.org',
    'games.localhost',
    'crafics.com',
    'dschini.org',
    'dschini.com',
    'dschini.eu',
    'crafics.dev',
    'dschini.de'
);

$GLOBALS['games'][1]['img'] = '/img/findthebug.jpg';
$GLOBALS['games'][1]['title'] = 'Find the Bug';
$GLOBALS['games'][1]['body'] = 'created in April 2006 and inspired by the journey to Tbilisi.';
$GLOBALS['games'][1]['more'] = 'http://John.dschini.org/2006/04/06/find-the-bug/';
$GLOBALS['games'][1]['cleanname'] = 'findthebug';
$GLOBALS['games'][1]['coins'] = 0;
$GLOBALS['games'][1]['color'] = '2f4169';

$GLOBALS['games'][2]['img'] = '/img/findthebang.jpg';
$GLOBALS['games'][2]['title'] = 'Find the Bug (Space)';
$GLOBALS['games'][2]['body'] = 'the second one created in April 2006. Featuring Space stuff.';
$GLOBALS['games'][2]['more'] = 'http://John.dschini.org/2006/04/11/find-the-bug-2/';
$GLOBALS['games'][2]['cleanname'] = 'findthebang';
$GLOBALS['games'][2]['coins'] = 0;
$GLOBALS['games'][2]['color'] = '97b1ec';

$GLOBALS['games'][3]['img'] = '/img/findtheart.jpg';
$GLOBALS['games'][3]['title'] = 'Find the Bug (Art)';
$GLOBALS['games'][3]['body'] = 'the third one mostly featuring Georgian Artists created in April 2006.';
$GLOBALS['games'][3]['more'] = 'http://John.dschini.org/2006/04/18/find-the-bug-iii/';
$GLOBALS['games'][3]['cleanname'] = 'findtheart';
$GLOBALS['games'][3]['coins'] = 0;
$GLOBALS['games'][3]['color'] = 'fa6a22';

$GLOBALS['games'][4]['img'] = '/img/memorymania.jpg';
$GLOBALS['games'][4]['title'] = 'Puzzle Mania';
$GLOBALS['games'][4]['body'] = 'the 4th one created in April 2006. This time not bughunting but Puzzle.';
$GLOBALS['games'][4]['more'] = 'http://John.dschini.org/2006/04/15/memory-mania/';
$GLOBALS['games'][4]['cleanname'] = 'memorymania';
$GLOBALS['games'][4]['coins'] = 0;
$GLOBALS['games'][4]['color'] = 'bac1a2';

$GLOBALS['games'][5]['img'] = '/img/findthesun.jpg';
$GLOBALS['games'][5]['title'] = 'Find the Bug (Sun)';
$GLOBALS['games'][5]['body'] = 'created in October 2007 is a remix of images we find beautyfull.';
$GLOBALS['games'][5]['more'] = 'http://John.dschini.org/2007/10/06/find-the-bug-sun/';
$GLOBALS['games'][5]['cleanname'] = 'findthesun';
$GLOBALS['games'][5]['coins'] = 0;
$GLOBALS['games'][5]['color'] = 'cc5006';

$GLOBALS['games'][6]['img'] = '/img/globetrotter-premium.jpg';
$GLOBALS['games'][6]['title'] = 'Globetrotter XL';
$GLOBALS['games'][6]['body'] = 'XL version of Globetrotter. This version has much more cities and is more difficult.';
$GLOBALS['games'][6]['more'] = 'http://John.dschini.org/2009/01/05/globetrotter/';
$GLOBALS['games'][6]['cleanname'] = 'globetrotterpremium';
$GLOBALS['games'][6]['coins'] = 0;
$GLOBALS['games'][6]['color'] = 'aca684';

$GLOBALS['games'][7]['img'] = '/img/globetrotter.jpg';
$GLOBALS['games'][7]['title'] = 'Globetrotter';
$GLOBALS['games'][7]['body'] = 'created in January 2009 and inspired by the journey to Tbilisi.';
$GLOBALS['games'][7]['more'] = 'http://John.dschini.org/2009/01/05/globetrotter/';
$GLOBALS['games'][7]['cleanname'] = 'globetrotter';
$GLOBALS['games'][7]['coins'] = 0;
$GLOBALS['games'][7]['color'] = '7798b9';

$GLOBALS['games'][8]['img'] = '/img/hippopotamus.jpg';
$GLOBALS['games'][8]['title'] = 'Hippopotamus';
$GLOBALS['games'][8]['body'] = 'created in July 2009. I wanted to do something new! Something with words ...';
$GLOBALS['games'][8]['more'] = 'http://John.dschini.org/2009/07/02/new-game-hippopotamus/';
$GLOBALS['games'][8]['cleanname'] = 'hippopotamus';
$GLOBALS['games'][8]['coins'] = 0;
$GLOBALS['games'][8]['color'] = 'c8af91';

$GLOBALS['games'][9]['img'] = '/img/molecules.jpg';
$GLOBALS['games'][9]['title'] = 'Molecules';
$GLOBALS['games'][9]['body'] = 'The object of the game is to assemble molecules from compound atoms by moving the atoms on a two-dimensional playfield
Molecules is a clone of  the original Atomix game but holds some additional information and different molecules!';
$GLOBALS['games'][9]['more'] = 'http://www.mochigames.com/game/molecules_v793134/';
$GLOBALS['games'][9]['cleanname'] = 'molecules';
$GLOBALS['games'][9]['coins'] = 0;
$GLOBALS['games'][9]['color'] = '3e5d3d';

$GLOBALS['games'][10]['img'] = '/img/binary.jpg';
$GLOBALS['games'][10]['title'] = 'Binary';
$GLOBALS['games'][10]['body'] = 'Slide numbered tiles on a four-by-four grid, combine values to get to a higher level.';
$GLOBALS['games'][10]['more'] = '';
$GLOBALS['games'][10]['cleanname'] = 'binary';
$GLOBALS['games'][10]['coins'] = 0;
$GLOBALS['games'][10]['color'] = 'c1b7ad';

/* PAYPAL */
define('API_USERNAME', 'your-api-username');
define('API_PASSWORD', 'your-api-pwd');
define("PAYPAL_EMAIL", 'your-email');
define('API_SIGNATURE', 'your-api-signature');
define('API_ENDPOINT', 'https://api-3t.paypal.com/nvp');
define('PAYPAL_URL', 'https://www.paypal.com/webscr&cmd=_express-checkout&token=');

//define('PROXY_HOST', '127.0.0.1');
//define('PROXY_PORT', '808');
define('USE_PROXY',FALSE);
define('VERSION', '53.0');

$GLOBALS['memcache'] = new Memcache();
//$GLOBALS['memcache']->addServer('localhost',11211) or die('Memcache not found');
//$cache->addServer('localhost') or die('Memcache not found');
$GLOBALS['memcache']->connect("localhost",11211);
//$GLOBALS['memcache']->flush();

/* 
 * PHP Libraries
 * Add you own libraries!!!
 */

/* Helpers */
include(BASEDIR."lib/org/dschini/helpers/RequestHelper.php");
include(BASEDIR."lib/org/dschini/helpers/CallerServiceHelper.php");
include(BASEDIR."lib/org/dschini/helpers/UIDHelper.php");
include(BASEDIR."lib/org/dschini/helpers/PasswordHelper.php");
include(BASEDIR."lib/org/dschini/helpers/InputFilterHelper.php");
include(BASEDIR."lib/org/dschini/helpers/AvatarHelper.php");
//include(BASEDIR."lib/org/dschini/helpers/PDOMySQLDriverHelper.php");
include(BASEDIR."lib/org/dschini/helpers/MySQLiDriverHelper.php");
include(BASEDIR."lib/org/dschini/helpers/TemplateHelper.php");
include(BASEDIR."lib/org/dschini/helpers/DateFormatHelper.php");
include(BASEDIR."lib/org/dschini/helpers/URLHelper.php");
/* Proxies */
include(BASEDIR."lib/org/dschini/proxies/StatisticsProxy.php");
include(BASEDIR."lib/org/dschini/proxies/ScoreProxy.php");
include(BASEDIR."lib/org/dschini/proxies/ItemProxy.php");
include(BASEDIR."lib/org/dschini/proxies/UserdataProxy.php");
include(BASEDIR."lib/org/dschini/proxies/UsersProxy.php");
include(BASEDIR."lib/org/dschini/proxies/MessagesProxy.php");
include(BASEDIR."lib/org/dschini/proxies/CoinsProxy.php");
include(BASEDIR."lib/org/dschini/proxies/LogsProxy.php");
include(BASEDIR."lib/org/dschini/proxies/AccountsProxy.php");
include(BASEDIR."lib/org/dschini/proxies/AccountsProductsProxy.php");
include(BASEDIR."lib/org/dschini/proxies/HighscoresProxy.php");
include(BASEDIR."lib/org/dschini/proxies/PaymentsProxy.php");
/* Controllers */
include(BASEDIR."lib/org/dschini/controllers/userController.php");
include(BASEDIR."lib/org/dschini/controllers/scoreController.php");
include(BASEDIR."lib/org/dschini/controllers/shopController.php");
include(BASEDIR."lib/org/dschini/controllers/userdataController.php");
include(BASEDIR."lib/org/dschini/controllers/pageController.php");
include(BASEDIR."lib/org/dschini/controllers/accountController.php");
include(BASEDIR."lib/org/dschini/controllers/paymentController.php");
include(BASEDIR."lib/org/dschini/controllers/gameController.php");
include(BASEDIR."lib/org/dschini/controllers/statisticsController.php");

/* start session */
session_start();

/* Saving the rights in a session is not secure!!! */
RequestHelper::getInstance()->addUserRight($_SESSION['userRight'],RIGHT_PUBLIC);
