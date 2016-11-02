<?php
define('DATABASE_NAME', 'games_dschini_org');
define('DATABASE_USER', 'John');
define('DATABASE_PASS', 'secret-pwd-pls-change');
define('DATABASE_HOST', 'localhost');
define('DATABASE_PORT', 3306);
define('BASEDIR' ,dirname($_SERVER['DOCUMENT_ROOT']).DIRECTORY_SEPARATOR);

$type = 'wavatar';
$size = 30;

$link = mysql_pconnect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS);
mysql_select_db(DATABASE_NAME);
$query = sprintf('SELECT email FROM accounts WHERE approved=1 ORDER BY created LIMIT 2001,2500');
$result = mysql_query($query) or die('Query failed: ' . mysql_error);
if($result){
	while($row = mysql_fetch_assoc($result)){
		$gurl = "http://www.gravatar.com/avatar/".(md5($row['email'])).($type ? "?s=".$size."&d=".$type : '');
		$fcontents = file_get_contents($gurl);
		file_put_contents("htdocs/img/avatars/".md5($row['email']), $fcontents);
		echo "writing: ".$row['email']."\n";
		
	}
}

mysql_free_result($result);
mysql_close($link);
