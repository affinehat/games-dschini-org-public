<?php
    $header = 'From: Dschini.org <games@dschini.org>' . "\r\n";

define('DATABASE_NAME', 'games_dschini_org');
define('DATABASE_USER', 'John');
define('DATABASE_PASS', 'secret-pwd-pls-change');
define('DATABASE_HOST', 'localhost');
define('DATABASE_PORT', 3306);

    $link = mysql_pconnect(DATABASE_HOST,DATABASE_USER,DATABASE_PASS);
    mysql_select_db(DATABASE_NAME);
    $query = sprintf('SELECT username,email FROM accounts WHERE approved=1 and informme=1 ORDER BY created LIMIT 0,6000');
    $result = mysql_query($query) or die('Query failed: ' . mysql_error);
    if($result){
      while($row = mysql_fetch_assoc($result)){
        $body = 'Hello '.$row['username'].',';
        $body.= "\n\n";
	$body.= '';
        $body.= 'Globetrotter for iPhone and Android has been released. Get your version now!' . "\n\n";
        $body.= '';
	$body.= 'The versions are different and have been redesigned from scratch. The new features like zooming and highscores make a compelling gameplay.' . "\n\n";
	$body.= 'Globetrotter is a fun, fast-paced world geography game. You are presented with a map of the entire world, and asked to locate a number of cities. If you can locate them correctly enough, you will proceed to the next level.'."\n";
	$body.= 'Bonus multipliers are given for guessing within a few hundred kilometers, and an even greater multiplier for guessing within around kilometers.';
        $body.= '';
	$body.= "\n\n";
	$body.= 'More information can be found here' . "\n";
	$body.= '';
	$body.= 'http://dschini.org/globetrottermobile/' . "\n\n";
        $body.= '';
        $body.= 'Have fun playing!';
        $body.= "\n";
        $body.= "Dschini.org";
        $body.= "\n";
        mail($row['email'], 'Globetrotter for iPhone and Android released!',$body, $header);
	echo "email sent to: ".$row['email']. "\n";
        //echo $body;
	flush();
      }
    }
    mysql_free_result($result);
    mysql_close($link);

