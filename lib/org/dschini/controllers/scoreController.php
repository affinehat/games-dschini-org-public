<?php
include_once('../config.php');

class scoreController {

    public static $ALLOWED_SWF_IDS = array(1,2,3,4,5,6,7,8,10);
    public static $SECRET_SWF_IDS = array(6,7,8,10);

	public static $BOARD_ID = '4fe066c2217cba78';

        public static function submitAction(){
		if(RequestHelper::isPost())
                {

			if(!isset($_POST['userid'])||!isset($_POST['gameid'])||!isset($_POST['secret'])||!isset($_POST['level'])||!isset($_POST['score'])){
                return;
            }
			$secret = $_POST['secret'];
			$userid = $_POST['userid'];
			$gameid = $_POST['gameid'];
			$level = $_POST['level'];
                        $score = $_POST['score'];
			if(md5(self::$BOARD_ID.$score)==$secret){
				$highestScore = ScoreProxy::getHighestScoreOfUser($userid,$gameid);
				if($highestScore<$score){
					ScoreProxy::delete($userid,$gameid);
					ScoreProxy::save($gameid,$userid,$level,$score);
				}
				userdataController::friendsAction();
			}
		}
		/*$ret = array(
			'success'=>true
		);
		echo json_encode($ret);*/
        }

}
