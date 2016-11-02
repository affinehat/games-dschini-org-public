<?php
include_once('../config.php');

class userdataController {


        public static $ALLOWED_SWF_IDS = array(1,2,3,4,5,6,7,8,10);
        public static $SECRET_SWF_IDS = array(6,7,8,10);

        public static function putAction(){
		if(RequestHelper::isPost())
                {
			if(!isset($_POST['userid'])||!isset($_POST['gameid'])){
                                return;
                        }
			$userid = $_POST['userid'];
			$gameid = $_POST['gameid'];
			$data = $_POST['data'];
			$userdataProxy = new UserdataProxy();
			$userdataProxy->userid = $userid;
			$userdataProxy->gameid = $gameid;
			$userdataProxy->data = $data;
			$userdataProxy->put();
		}
		$ret = array(
			'success'=>true
		);
		echo json_encode($ret);
        }

        public static function getAction(){
		if(RequestHelper::isPost())
                {
			if(!isset($_POST['userid'])||!isset($_POST['gameid'])){
                                return;
                        }
			$userid = $_POST['userid'];
                	$gameid = $_POST['gameid'];
			$data = UserdataProxy::get($userid,$gameid);
			echo json_encode($data);
		}
		return;
        }

        public static function friendsAction(){
		if(RequestHelper::isPost())
                {
			if(!isset($_POST['userid'])||!isset($_POST['gameid'])){
				return;
			}
			$userid = $_POST['userid'];
                	$gameid = $_POST['gameid'];
			$map = UserdataProxy::friends($userid,$gameid);
			$map[$userid]=1; //add yourself
			$users = UserdataProxy::get_users_by_friendsmap($map);
			$scores = ScoreProxy::getScoresByUserlist($map,$gameid);
			$ret = array();
			for($i=0;$i<count($scores);$i++){
				$ret[$scores[$i]['user']] = array(
					'position'=>$i+1,
					'score'=>$scores[$i]['score'],
					'level'=>$scores[$i]['level'],
					'created'=>$scores[$i]['created']
				);
			}
			for($i=0;$i<count($users);$i++){
				$ret[$users[$i]['id']]['status'] = $map[$users[$i]['id']];
				$ret[$users[$i]['id']]['user'] = $users[$i]['id'];
				$ret[$users[$i]['id']]['name'] = $users[$i]['name'];
				$ret[$users[$i]['id']]['link'] = $users[$i]['link'];
				$ret[$users[$i]['id']]['gender'] = $users[$i]['gender'];
				$ret[$users[$i]['id']]['verified'] = $users[$i]['verified'];
			}
			$data = array();
			foreach($ret as $item){
				$data[] = $item;
			}
			echo json_encode($data);
		}
        }

}
