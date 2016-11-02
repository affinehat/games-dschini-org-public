<?php
class HighscoresProxy
{
	public $id;
	public $created;
	public $game_id;
	public $user;
	public $level;
	public $score;

	public function save(){
		if(!isset($this->id)){
			$sql = sprintf("INSERT INTO `highscores`
							(`created`,`game_id`,`user`,`level`,`score`) 
							VALUES 
							(now(),%d,'%s',%d,%d);"
				,DBConnectionHelper::getInstance()->escape($this->game_id)
				,DBConnectionHelper::getInstance()->escape($this->user)
				,DBConnectionHelper::getInstance()->escape($this->level)
				,DBConnectionHelper::getInstance()->escape($this->score)
				);
				DBConnectionHelper::getInstance()->execute($sql);
			$this->id = DBConnectionHelper::getInstance()->insert_id();
		} else {
			$sql = sprintf("UPDATE `highscores` SET `game_id`=%d, `user`='%s', `level`=%d, `score`=%d WHERE id=%d"
				,DBConnectionHelper::getInstance()->escape($this->game_id)
				,DBConnectionHelper::getInstance()->escape($this->user)
				,DBConnectionHelper::getInstance()->escape($this->level)
				,DBConnectionHelper::getInstance()->escape($this->score)
				,DBConnectionHelper::getInstance()->escape($this->id)
				);
			DBConnectionHelper::getInstance()->execute($sql);
		}
	}

	public static function get( $id ){
		$sql = sprintf("SELECT * from highscores WHERE `id`=%d",$id);
		$row = DBConnectionHelper::getInstance()->query($sql);
		if(sizeof($row)>0){
			$obj = new HighscoresProxy();
			foreach($row[0] as $key => $value){
				$obj->{$key} = $value;
			}
			return $obj;
		}
		return null;
	}

    public static function latestByGameId($game_id){
        $sql = sprintf("SELECT * FROM highscores WHERE game_id = %d ORDER BY score DESC LIMIT 0,30",
            $game_id);
        $rows = DBConnectionHelper::getInstance()->query($sql);
        $arr = array();
        foreach($rows AS $row){
            $obj = new HighscoresProxy();
            foreach($row as $key => $value){
                $obj->{$key} = $value;
            }
            $arr[] = $obj;
        }
        return $arr;
    }

    public static function overallTopScoresByGameId($game_id,$amount=5){
        $sql = sprintf("SELECT h.user, MAX(h.score) AS score, DATE(h.created) AS created, a.approved
          FROM highscores h left join accounts a on h.user=a.username WHERE h.game_id=%d
          GROUP BY h.user ORDER BY score DESC LIMIT 0, %d",
            $game_id,
            $amount
        );

        $rows = DBConnectionHelper::getInstance()->query($sql);
        if(!$rows){
            return 0;
        }
        return $rows;
    }

    public static function amountOfTotalGameplaysByGameId($game_id){
        $sql = sprintf("SELECT count(id) as amountOfTotalGameplaysByGameId
						FROM highscores WHERE game_id = %d LIMIT 0,1",
            $game_id);
        $rows = DBConnectionHelper::getInstance()->query($sql);
        if(!$rows){
            return 0;
        }
        return $rows[0]['amountOfTotalGameplaysByGameId'];
    }
	
	public static function currentLeadershipByGameId($game_id){

/*
echo '<pre>';
$stats = $GLOBALS['memcache']->getExtendedStats();
print_r($stats);
echo '</pre>';
*/
		$result = $GLOBALS['memcache']->get('HighscoresProxy-currentLeadershipByGameId-'.$game_id);
		if($result != false) {
			return $result;
	        //return $GLOBALS['memcache']->get('HighscoresProxy-currentLeadershipByGameId-'.$game_id);
	    }
		/*
		$sql = sprintf("SELECT * from highscores
						WHERE game_id = %d
						AND MONTH( created ) = MONTH( CURDATE( ) )
			            AND YEAR( created ) = YEAR( CURDATE( ) )
						ORDER BY score DESC 
						LIMIT 0 , 5"
					,$game_id);
		*/
	    $sql = sprintf("select user, max(level) as level, max(score) as score from highscores
						where game_id=%d
						AND YEAR( highscores.created ) = YEAR( CURDATE( ) )
						AND MONTH( highscores.created ) = MONTH( CURDATE( ) )
						group by user order BY score
						DESC LIMIT 0,12"
					,$game_id);
		$scores = DBConnectionHelper::getInstance()->query($sql);
		$result = $GLOBALS['memcache']->replace('HighscoresProxy-currentLeadershipByGameId-'.$game_id, $scores, 0, 5*60);
		if( $result == false ){
			$GLOBALS['memcache']->set('HighscoresProxy-currentLeadershipByGameId-'.$game_id, $scores, 0, 5*60);
		}
		return $scores;
	}
	
	public static function allTimeStarsByGameId($game_id){
		$result = $GLOBALS['memcache']->get('HighscoresProxy-allTimeStarsByGameId-'.$game_id);
		if( $result != false ) {
			return $result;
		}

		//if($GLOBALS['memcache']->get('HighscoresProxy-allTimeStarsByGameId-'.$game_id)) {
		/*
		echo '<pre>';
		echo '<h1>HighscoresProxy-allTimeStarsByGameId-'.$game_id.'</h1>';
		print_r($GLOBALS['memcache']->get('HighscoresProxy-allTimeStarsByGameId-'.$game_id));
		echo '</pre>';
	        */
		//return $GLOBALS['memcache']->get('HighscoresProxy-allTimeStarsByGameId-'.$game_id);
	    	//}
		/*
		$sql = sprintf("SELECT * from highscores
						WHERE game_id = %d
						ORDER BY score DESC 
						LIMIT 0 , 5"
					,$game_id);
		*/
		$sql = sprintf("select user, max(level) as level, max(score) as score from highscores
						where game_id=%d
						group by user order BY score DESC
						LIMIT 0,12"
					,$game_id);
		//echo $sql;
		$scores = DBConnectionHelper::getInstance()->query($sql);
		$result = $GLOBALS['memcache']->replace('HighscoresProxy-allTimeStarsByGameId-'.$game_id, $scores, 0, 5*60);
		if( $result == false){
			$GLOBALS['memcache']->set('HighscoresProxy-allTimeStarsByGameId-'.$game_id, $scores, 0, 5*60);
		}
		return $scores;
	}
	
	public static function currentGameplaysByGameId($game_id){
		$result = $GLOBALS['memcache']->get('HighscoresProxy-currentGameplaysByGameId-'.$game_id);
		if( $result!=false ) {
	        	return $result;
	    	}
		$sql = sprintf("SELECT count(id) as currentGameplaysByGameId 
						FROM highscores 
						WHERE game_id = %d 
						AND MONTH( created ) = MONTH( CURDATE( ) )
			            AND YEAR( created ) = YEAR( CURDATE( ) )
			            AND DAY( created ) = DAY( CURDATE( ) )
			            LIMIT 0,1",
						$game_id);
		$rows = DBConnectionHelper::getInstance()->query($sql);
	        if(!$rows){
	        	return 0;
	        }
		$result = $GLOBALS['memcache']->replace('HighscoresProxy-currentGameplaysByGameId-'.$game_id, $rows[0]['currentGameplaysByGameId'],0, 5*60);
		if( $result==false ){
			$GLOBALS['memcache']->set('HighscoresProxy-currentGameplaysByGameId-'.$game_id, $rows[0]['currentGameplaysByGameId'],0, 5*60);
		}
	        return $rows[0]['currentGameplaysByGameId'];
	}
	
	public static function amountOfTotalGameplays(){
		if($GLOBALS['memcache']->get('HighscoresProxy-amountOfTotalGameplays')) {
	        return $GLOBALS['memcache']->get('HighscoresProxy-amountOfTotalGameplays');
	    }
		$sql = sprintf("SELECT game_id, COUNT( id ) AS amountOfTotalGameplays
						FROM highscores GROUP BY game_id");
        $rows = DBConnectionHelper::getInstance()->query($sql);
        $arr = array();
        if(!$rows){
        	return null;
        }
        foreach($rows as $row){
			$arr[$row['game_id']] = $row['amountOfTotalGameplays'];
        }
        $GLOBALS['memcache']->set('HighscoresProxy-amountOfTotalGameplays', $arr, 0, 5*60);
        return $arr;
	}
	
	public static function amountOfCurrentMonthGameplaysByGameId($game_id){
		return 34567;
		$sql = sprintf("SELECT count(id) as amountOfCurrentMonthGameplaysByGameId 
						FROM highscores 
						WHERE 
			            MONTH( created ) = MONTH( CURDATE( ) )
			            AND YEAR( created ) = YEAR( CURDATE( ) )
						AND game_id = %d LIMIT 0,1",
						$game_id);
        $rows = DBConnectionHelper::getInstance()->query($sql);
        if(!$rows){
        	return 0;
        }
        return $rows[0]['amountOfCurrentMonthGameplaysByGameId'];
	}

    public static function getMonthlyTopscoresByGameId($game_id){
        $arr = array();
        $sql = sprintf("SELECT
						YEAR(created) as y,
						MONTH(created) as m
						FROM
						highscores
						WHERE
						game_id = %d
						GROUP BY
						YEAR(created),
						MONTH(created)
						ORDER BY YEAR(created) DESC,MONTH(created) DESC",
            $game_id);
        $months = DBConnectionHelper::getInstance()->query($sql);
        if(!$months){
            return null;
        }
        foreach($months as $month){
            $sql = sprintf("SELECT
        					max(highscores.score) as score,
        					max(highscores.level) as level,
        					highscores.user as user,
        					highscores.created as created,
							accounts.approved as approved,
							accounts.email as email
							FROM highscores LEFT JOIN accounts
							ON highscores.user = accounts.username
							WHERE highscores.game_id = %d
							AND YEAR( highscores.created ) = %d
							AND MONTH( highscores.created ) = %d
							GROUP BY highscores.user
							ORDER BY score DESC
							LIMIT 0 , 5
						",
                $game_id,$month['y'],$month['m']);
            $scores = DBConnectionHelper::getInstance()->query($sql);
            if(!$scores){
                $arr[$month['y']][$month['m']][] = array('score'=>0);
            } else {
                foreach($scores as $score){
                    $arr[$month['y']][$month['m']][] = array(
                        'score'=>$score['score'],
                        'level'=>$score['level'],
                        'user'=>$score['user'],
                        'created'=>$score['created'],
                        'approved'=>$score['approved'],
                        'email'=>$score['email']
                    );
                }
            }
        }
        return $arr;
    }

    public static function monthlyTopscoresByGameId($game_id){
        return self::getMonthlyTopscoresByGameId($game_id);
        /*
		$result = $GLOBALS['memcache']->get('HighscoresProxy-monthlyTopscoresByGameId-'.$game_id);
		if($result != false) {
			return $result;
	    }
        $arr = self::getMonthlyTopscoresByGameId($game_id);
		$result = $GLOBALS['memcache']->replace('HighscoresProxy-monthlyTopscoresByGameId-'.$game_id, $arr, 0, 24*60*60);
		if( $result == false ){
			$GLOBALS['memcache']->set('HighscoresProxy-monthlyTopscoresByGameId-'.$game_id, $arr, 0, 24*60*60);
		}
        return $arr;
        */
	}

	public static function currentAmountOfScoresByGameId($game_id){
		$sql = sprintf("SELECT count(id) as amount
						FROM highscores
			            WHERE game_id = %d
			            AND MONTH( created ) = MONTH( CURDATE( ) )
			            AND YEAR( created ) = YEAR( CURDATE( ) )
			            LIMIT 0 , 1",
			            $game_id);
        $rows = DBConnectionHelper::getInstance()->query($sql);
		if(!$rows){
			return -1;
		}
		return $rows[0]['amount'];
	}
	
	public static function currentScoresByGameId($game_id,$limit_from=0,$limit_to=100){
		$arr = array();
		$sql = sprintf("SELECT 
							highscores.id as id,
							highscores.created as created,
							highscores.game_id as game_id,
							highscores.user as user,
							highscores.level as level,
							highscores.score as score,
							accounts.approved as approved,
							accounts.points as points,
							accounts.email as email
			        	FROM highscores LEFT JOIN accounts
			        	ON highscores.user = accounts.username
			            WHERE highscores.game_id = %d
			            AND MONTH( highscores.created ) = MONTH( CURDATE( ) )
			            AND YEAR( highscores.created ) = YEAR( CURDATE( ) )
			            ORDER BY highscores.score DESC
			            LIMIT %d,%d",
			            $game_id,$limit_from,$limit_to);
		$rowsHighscore = DBConnectionHelper::getInstance()->query($sql);
		foreach($rowsHighscore AS $row){
			$obj = new HighscoresProxy();
            foreach($row as $key => $value){
            	$obj->{$key} = $value;
			}
            $arr[] = $obj;
		}
		return $arr;
	}
	
	public static function positionByGameId($game_id,$user){
		
 		/* mine */
        $arr = array();
        $sql = sprintf("SELECT *
			        	FROM highscores
			            WHERE game_id = %d
			            AND user =  '%s'
			            AND MONTH( created ) = MONTH( CURDATE( ) )
			            AND YEAR( created ) = YEAR( CURDATE( ) )
			            ORDER BY score DESC
			            LIMIT 0 , 1",
			            $game_id,$user);
        $rowsHighscore = DBConnectionHelper::getInstance()->query($sql);
		if(!$rowsHighscore){
			return null;
		}
        foreach($rowsHighscore AS $row){
			$obj = new HighscoresProxy();
            foreach($row as $key => $value){
            	$obj->{$key} = $value;
			}
            $arr[] = $obj;
		}
		/* rank */
		if(!$rowsHighscore){
			$rowsHighscore = array();
			$rowsHighscore[0] = array('user'=>$user,'score'=>0);
		}
		$sql = sprintf("SELECT count(*) as rank FROM highscores
						WHERE game_id = %d
						AND score>=%d
						AND MONTH(created)=MONTH(CURDATE())
						AND YEAR(created)=YEAR(CURDATE())",
						$game_id,$rowsHighscore[0]['score']);
		$rowsRank = DBConnectionHelper::getInstance()->query($sql);
		return array(
			'positions' => $arr,
			'rank' => $rowsRank[0]['rank']
		);
		return $arr;
	}
	
	public static function positionsByGameId($game_id,$user){
		
 		/* mine */
        $arr = array();
        $sql = sprintf("SELECT *
			        	FROM highscores
			            WHERE game_id = %d
			            AND user =  '%s'
			            AND MONTH( created ) = MONTH( CURDATE( ) )
			            AND YEAR( created ) = YEAR( CURDATE( ) )
			            ORDER BY score DESC
			            LIMIT 0 , 1",
			            $game_id,$user);
        $rowsHighscore = DBConnectionHelper::getInstance()->query($sql);
		if(!$rowsHighscore){
        	$rowsHighscore = array();
            $rowsHighscore[0] = array('user'=>$user,'score'=>0);
		}
        foreach($rowsHighscore AS $row){
			$obj = new HighscoresProxy();
            foreach($row as $key => $value){
            	$obj->{$key} = $value;
			}
            $arr[] = $obj;
		}

		/* previous */
        $arrPrev = array();
		$sql = sprintf("SELECT * FROM highscores
						WHERE game_id = %d
			        	AND user!='%s'
			            AND score>=%d
			            AND MONTH(created)=MONTH(CURDATE())
			            AND YEAR(created)=YEAR(CURDATE())
			            ORDER BY score ASC LIMIT 0,6",
			            $game_id,$user,$rowsHighscore[0]['score']);
            $rowsHighscorePrev = DBConnectionHelper::getInstance()->query($sql);
            $rowsHighscorePrev = array_reverse($rowsHighscorePrev);
            foreach($rowsHighscorePrev AS $row){
            	$obj = new HighscoresProxy();
				foreach($row as $key => $value){
					$obj->{$key} = $value;
				}
				$arrPrev[] = $obj;
			}

			/* next */
			$arrNext = array();
			$sql = sprintf("SELECT * FROM highscores
							WHERE game_id = %d
							AND user!='%s'
							AND score<=%d
							AND MONTH(created)=MONTH(CURDATE())
							AND YEAR(created)=YEAR(CURDATE())
							ORDER BY score DESC LIMIT 0,7",
							$game_id,$user,$rowsHighscore[0]['score']);
			$rowsHighscoreNext = DBConnectionHelper::getInstance()->query($sql);
			foreach($rowsHighscoreNext AS $row){
				$obj = new HighscoresProxy();
				foreach($row as $key => $value){
					$obj->{$key} = $value;
				}
				$arrNext[] = $obj;
			}
			
			/* rank */
			if(!$rowsHighscore){
				$rowsHighscore = array();
				$rowsHighscore[0] = array('user'=>$user,'score'=>0);
			}
			$sql = sprintf("SELECT count(*) as rank FROM highscores
							WHERE game_id = %d
							AND score>=%d
							AND MONTH(created)=MONTH(CURDATE())
							AND YEAR(created)=YEAR(CURDATE())",
							$game_id,$rowsHighscore[0]['score']);
			$rowsRank = DBConnectionHelper::getInstance()->query($sql);
			return array(
				'positions' => array_merge($arrPrev,$arr,$arrNext),
				'rank' => $rowsRank[0]['rank'],
				'countRowsHighscorePrev' => count($rowsHighscorePrev),
				'countRowsHighscore' => count($rowsHighscore),
				'countRowsHighscoreNext' => count($rowsHighscoreNext),
			);
	}
	
	public static function filter( $filter ){
		$sql = sprintf("SELECT * FROM highscores WHERE %s", $filter);
		$rows = DBConnectionHelper::getInstance()->query($sql);
		$arr = array();
		foreach($rows AS $row){
			$obj = new HighscoresProxy();
			foreach($row as $key => $value){
				$obj->{$key} = $value;
			}
			$arr[] = $obj;
		}
		return $arr;
	}	
}
