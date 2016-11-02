<?php
class ScoreProxy
{
	public static function save($game_id,$user_id,$level,$score){
		$sql = sprintf("INSERT INTO `highscoresfb`
			(`created`,`game_id`,`user`,`level`,`score`) 
			VALUES 
			(now(),%d,'%s',%d,%d);"
			,DBConnectionHelper::getInstance()->escape($game_id)
			,DBConnectionHelper::getInstance()->escape($user_id)
			,DBConnectionHelper::getInstance()->escape($level)
			,DBConnectionHelper::getInstance()->escape($score)
		);
        	DBConnectionHelper::getInstance()->query($sql);
	}

	public static function delete($user_id,$game_id){
                $sql = sprintf("DELETE FROM `highscoresfb` WHERE `user`='%s' AND `game_id`=%d"
                        ,DBConnectionHelper::getInstance()->escape($user_id)
                        ,DBConnectionHelper::getInstance()->escape($game_id)
                );
                DBConnectionHelper::getInstance()->query($sql);
        }

	public static function getHighestScoreOfUser($user_id,$game_id){
		$sql = sprintf("SELECT score FROM `highscoresfb` WHERE `user`='%s' AND `game_id`=%d"
                        ,DBConnectionHelper::getInstance()->escape($user_id)
                        ,DBConnectionHelper::getInstance()->escape($game_id)
                );
                $rows = DBConnectionHelper::getInstance()->query($sql);
		return isset($rows[0]) ? $rows[0]['score'] : 0;
	}
        
	public static function getScoresByUserlist($userlist,$game_id){
                $in = implode("','",array_keys($userlist));
                $sql = sprintf("SELECT * FROM `highscoresfb` WHERE `user` IN ('%s') AND `game_id`=%d ORDER BY score DESC",$in,$game_id);
                $arr = DBConnectionHelper::getInstance()->query($sql);
                return $arr;
        }	
}
