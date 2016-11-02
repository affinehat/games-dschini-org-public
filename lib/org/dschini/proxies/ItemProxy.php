<?php
class ItemProxy
{
        public $id;
        public $code;
	public $author;
	public $cat;
        public $title;
        public $desc;
	public $price;
	public $image_url;
	public $product_url;
	public $game_id;
	public $data;

	public static function set( $id,$code,$author,$cat,$title,$desc,$price,$image_url,$product_url,$data,$game_id ){
		if($id){
			$sql = sprintf("UPDATE items SET `cat`=%d,`title`='%s',`desc`='%s',`price`=%d,`image_url`='%s',`product_url`='%s' WHERE id=%d"
				,DBConnectionHelper::getInstance()->escape($cat)
				,DBConnectionHelper::getInstance()->escape($title)
				,DBConnectionHelper::getInstance()->escape($desc)
				,DBConnectionHelper::getInstance()->escape($price)
				,DBConnectionHelper::getInstance()->escape($image_url)
				,DBConnectionHelper::getInstance()->escape($product_url)
				,DBConnectionHelper::getInstance()->escape($id)
			);
			DBConnectionHelper::getInstance()->query($sql);
			$sql = sprintf("UPDATE items_data SET `data`='%s' WHERE item_id=%d"
				,DBConnectionHelper::getInstance()->escape($data)
				,DBConnectionHelper::getInstance()->escape($id)
			);
			DBConnectionHelper::getInstance()->query($sql);
		} else {
			$sql = sprintf("INSERT INTO items (`code`,`author`,`cat`,`title`,`desc`,`price`,`image_url`,`product_url`,`game_id`) VALUES ('%s','%s',%d,'%s','%s',%d,'%s','%s',%d)"
				,DBConnectionHelper::getInstance()->escape($code)
				,DBConnectionHelper::getInstance()->escape($author)
                	        ,DBConnectionHelper::getInstance()->escape($cat)
                	        ,DBConnectionHelper::getInstance()->escape($title)
                	        ,DBConnectionHelper::getInstance()->escape($desc)
                	        ,DBConnectionHelper::getInstance()->escape($price)
                	        ,DBConnectionHelper::getInstance()->escape($image_url)
                	        ,DBConnectionHelper::getInstance()->escape($product_url)
                	        ,DBConnectionHelper::getInstance()->escape($game_id)
			);
			DBConnectionHelper::getInstance()->query($sql);
			$item_id = DBConnectionHelper::getInstance()->insert_id();
			$sql = sprintf("INSERT INTO items_data (`item_id`,`data`) VALUES (%d,'%s')"
				,$item_id
				,DBConnectionHelper::getInstance()->escape($data)
			);
			DBConnectionHelper::getInstance()->query($sql);
		}
	}

	public static function del( $code ){
		$sql = sprintf("DELETE FROM items WHERE code='%s'"
			,DBConnectionHelper::getInstance()->escape($code)
		);
		DBConnectionHelper::getInstance()->query($sql);
	}

        public static function get( $code ){
                $sql = sprintf("SELECT * from items i,items_data ia WHERE `code`='%s' AND i.id=ia.item_id",$code);
                $row = DBConnectionHelper::getInstance()->query($sql);
                if(sizeof($row)>0){
                        $obj = new ItemProxy();
                        foreach($row[0] as $key => $value){
                                $obj->{$key} = $value;
                        }
			$obj->data = json_decode($obj->data);
                       return $obj;
                }
                return null;
        }

	public static function getAllOfAuthor( $facebook_id, $game_id=9 ){
                $sql = sprintf("SELECT i.*,ia.data FROM items i, items_data ia WHERE ia.item_id=i.id AND i.author='%s' AND i.game_id=%d",
					DBConnectionHelper::getInstance()->escape($facebook_id)
					,$game_id);
                $rows = DBConnectionHelper::getInstance()->query($sql);
                for($i=0;$i<count($rows);$i++){
                        $rows[$i]['data'] = json_decode($rows[$i]['data']);
                }
                return $rows;
        }

	public static function getAllOfUser( $facebook_id, $game_id=9 ){
		$sql = sprintf("SELECT i.*,fi.facebook_id,fi.created,fi.amount FROM fbaccount_items fi, items i WHERE fi.item_id=i.id AND fi.facebook_id='%s' AND i.game_id=%d"
			,DBConnectionHelper::getInstance()->escape($facebook_id)
			,$game_id);

		$rows = DBConnectionHelper::getInstance()->query($sql);
		//for($i=0;$i<count($rows);$i++){
		//	$rows[$i]['data'] = json_decode($rows[$i]['data']);
		//}
		return $rows;
	}

}
