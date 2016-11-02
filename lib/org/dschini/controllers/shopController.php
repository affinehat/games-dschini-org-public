<?php
include_once('../config.php');

class shopController {


        public static $ALLOWED_SWF_IDS = array(1,2,3,4,5,6,7,8);
        public static $SECRET_SWF_IDS = array(6,7,8);

	public static function delitemAction(){
                if(RequestHelper::isPost())
                {
			$code = $_POST['code'];
			$folder = "/tmp";
                        switch($_POST['game_id']){
                                case 10:
                                        $folder = "/srv/www/vhosts/facebook.dschini.org/worldmasters/items/";
                                        break;
                        }
			ItemProxy::del($code);
			unlink($folder.$code.".png");
		}
		echo json_encode(true);
	}

        public static function setitemAction(){
                if(RequestHelper::isPost())
                {
			$folder = "/tmp";
			switch($_POST['game_id']){
				case 10:
					$folder = "/srv/www/vhosts/facebook.dschini.org/worldmasters/items/";
					break;
			}
			$data = base64_decode($_POST['image']);
			$im = imagecreatefromstring($data);
			$code = empty($_POST['code']) ? uniqid(rand(0,999),true) : $_POST['code'];
			$product_url = $image_url = $code.".png";
			$result = imagepng($im, $folder.$image_url);
			imagedestroy($im);
			$id = isset($_POST['id']) ? $_POST['id'] : null;
			$author = $_POST['author'];
			$cat = $_POST['cat'];
			$title = $_POST['title'];
			$desc = $_POST['desc'];
			$price = $_POST['price'];
			$data = $_POST['data'];
			$game_id = $_POST['game_id'];
			ItemProxy::set( $id,$code,$author,$cat,$title,$desc,$price,$image_url,$product_url,$data,$game_id );
                }
		echo json_encode(true);
        }

        public static function getitemAction(){
		if(RequestHelper::isPost())
                {
			$code = $_POST['code'];
			$data  = ItemProxy::get($code);
                        $data->image_url = 'http://facebook.dschini.org/molecules/items/'.$data->image_url;
			$data->product_url = 'http://facebook.dschini.org/molecules/items/'.$data->product_url;
			echo json_encode($data);
		}
		$ret = array(
			'success'=>true
		);
		echo json_encode($ret);
        }

	public static function getallitemsofauthorAction(){
		if(RequestHelper::isPost())
                {
                        $facebook_id = $_POST['facebook_id'];
                        $game_id = isset($_POST['game_id']) ? $_POST['game_id'] : 9;
                        $data = ItemProxy::getAllOfAuthor($facebook_id,$game_id);
                        echo json_encode($data);

                }
	}

	public static function getallitemsofuserAction(){
                if(RequestHelper::isPost())
                {
                        $facebook_id = $_POST['facebook_id'];
			$game_id = isset($_POST['game_id']) ? $_POST['game_id'] : 9;
                        $data = ItemProxy::getAllOfUser($facebook_id,$game_id);
                        echo json_encode($data);

                }
        }

}
