<?php
/**
 * Created by PhpStorm.
 * User: Johnweber
 * Date: 28.05.14
 * Time: 00:36
 */

include("lib/org/dschini/proxies/StatisticsProxy.php");
include("lib/org/dschini/helpers/MySQLiDriverHelper.php");
//include("lib/org/dschini/helpers/TemplateHelper.php");

define('DATABASE_NAME', 'games_dschini_org');
define('DATABASE_USER', 'John');
define('DATABASE_PASS', 'secret-pwd-pls-change');
define('DATABASE_HOST', 'localhost');
define('DATABASE_PORT', 3306);

$newAccounts = StatisticsProxy::monthlyNewAccounts();
$newAccountsApproved = StatisticsProxy::monthlyNewAccounts(true);
file_put_contents('cron/monthlyNewAccounts.json',json_encode(
    array(
        'all' => $newAccounts,
        'approved' => $newAccountsApproved
    )
));
