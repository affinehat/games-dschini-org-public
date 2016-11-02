<?php
class UserdataProxy
{
        public $id;
        public $userid;
        public $data;
        public $gameid;

        public function put(){
		$obj = self::get($this->userid,$this->gameid);
		if(!$obj){	
                        $sql = sprintf("INSERT INTO `userdata`
                                                        (`userid`,`data`,`gameid`)
                                                        VALUES
                                                        ('%s','%s',%d);"
                                ,DBConnectionHelper::getInstance()->escape($this->userid)
                                ,DBConnectionHelper::getInstance()->escape($this->data)
                                ,DBConnectionHelper::getInstance()->escape($this->gameid)
                                );
                                DBConnectionHelper::getInstance()->execute($sql);
                        $this->id = DBConnectionHelper::getInstance()->insert_id();
                } else {
                        $sql = sprintf("UPDATE `userdata` SET `data`='%s' WHERE userid='%s' AND gameid=%d"
                                ,DBConnectionHelper::getInstance()->escape($this->data)
                                ,DBConnectionHelper::getInstance()->escape($this->userid)
								,DBConnectionHelper::getInstance()->escape($this->gameid)
						);
                        DBConnectionHelper::getInstance()->execute($sql);
                }
        }

        public static function get( $userid, $gameid ){
                $sql = sprintf("SELECT * from userdata WHERE `userid`='%s' AND `gameid`=%d"
			,DBConnectionHelper::getInstance()->escape($userid)
			,DBConnectionHelper::getInstance()->escape($gameid)
		);
                $row = DBConnectionHelper::getInstance()->query($sql);
                if(sizeof($row)>0){
                        $obj = new UserdataProxy();
						$obj->id = $row[0]['id'];
						$obj->userid = $row[0]['userid'];
						$obj->gameid = $row[0]['gameid'];
						$obj->data = json_decode($row[0]['data']);
                        return $obj;
                }
                return null;
        }
	
	public static function clear( $userid, $gameid ){
		$sql = sprintf("DELETE from userdata WHERE `userid`='%s' AND `gameid`=%d"
			,DBConnectionHelper::getInstance()->escape($userid)
			,DBConnectionHelper::getInstance()->escape($gameid)
		);
		DBConnectionHelper::getInstance()->query($sql);
	}

	public static function friends($userid,$gameid){
		$query = sprintf("SELECT friend_uid FROM `friends` WHERE `user_uid` = '%s' AND `game_id`=%d",
			DBConnectionHelper::getInstance()->escape($userid),
    			DBConnectionHelper::getInstance()->escape($gameid));
		$arr1 = DBConnectionHelper::getInstance()->query($query);
		$query = sprintf("SELECT user_uid FROM `friends` WHERE `friend_uid` = '%s' AND `game_id`=%d",
			DBConnectionHelper::getInstance()->escape($userid),
                        DBConnectionHelper::getInstance()->escape($gameid));
		$arr2 = DBConnectionHelper::getInstance()->query($query);
		$array = array();
		for($i=0;$i<count($arr1);$i++){
			$array[$arr1[$i]['friend_uid']] = 0;
		}
		for($i=0;$i<count($arr2);$i++){
			$array[$arr2[$i]['user_uid']] = isset($array[$arr2[$i]['user_uid']]) ? 1 : 2;
		}
		return $array;
	}

	public static function get_users_by_friendsmap($friendsmap){
		$in = implode("','",array_keys($friendsmap));
		$sql = sprintf("SELECT * FROM `fbaccounts` WHERE `id` IN ('%s')",$in);
		$arr = DBConnectionHelper::getInstance()->query($sql);
		return $arr;
	}

}
