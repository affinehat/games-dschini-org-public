<?php
class MessagesProxy
{
	public $id;
	public $created;
	public $sender;
	public $receiver;
	public $box;
	public $subject;
	public $body;
	public $status;

	public function __construct(){
	}

	public function save(){
		if(!isset($this->id)){
			$sql = sprintf("INSERT INTO `messages`
				(`created`,`sender`,`receiver`,`box`,`subject`,`body`,`status`)
				VALUES 
				(now(),'%s','%s',%d,'%s','%s',%d);"
				,DBConnectionHelper::getInstance()->escape($this->sender)
				,DBConnectionHelper::getInstance()->escape($this->receiver)
				,DBConnectionHelper::getInstance()->escape($this->box)
				,DBConnectionHelper::getInstance()->escape($this->subject)
				,DBConnectionHelper::getInstance()->escape($this->body)
				,DBConnectionHelper::getInstance()->escape($this->status)
				);
			DBConnectionHelper::getInstance()->execute($sql);
			$this->id = DBConnectionHelper::getInstance()->insert_id();
		} else {
			$sql = sprintf("UPDATE `messages`
				SET `box`=%d, `status`=%d WHERE id=%d"
                ,DBConnectionHelper::getInstance()->escape($this->box)
                ,DBConnectionHelper::getInstance()->escape($this->status)
				,DBConnectionHelper::getInstance()->escape($this->id)
				);
			DBConnectionHelper::getInstance()->execute($sql);
		}
	}

    public static function remove( $id ){
        $sql = sprintf("DELETE from messages WHERE `id`=%d",$id);
        DBConnectionHelper::getInstance()->query($sql);
        return;
    }

    public static function get( $id ){
        $sql = sprintf("SELECT * from messages WHERE `id`=%d",$id);
        $row = DBConnectionHelper::getInstance()->query($sql);
        if(sizeof($row)>0){
            $obj = new MessagesProxy();
            foreach($row[0] as $key => $value){
                $obj->{$key} = $value;
            }
            return $obj;
        }
        return null;
    }

    public static function statusCount( $receiver, $box, $status ){
        $sql = sprintf("SELECT count(*) as c from messages
                        WHERE `receiver`='%s' and `box`=%d and `status`=%d",
            DBConnectionHelper::getInstance()->escape($receiver),
            $box, $status);
        $row = DBConnectionHelper::getInstance()->query($sql);
        return $row[0]['c'];
    }

    public static function getInbox( $receiver, $status=array(1,2) ){
        $sql = sprintf("SELECT * from messages WHERE `receiver`='%s' and box=%d and status in (%s) ORDER BY created DESC"
            ,DBConnectionHelper::getInstance()->escape($receiver)
            ,1
            ,implode(',',$status)
        );
        $rows = DBConnectionHelper::getInstance()->query($sql);
        $arr = array();
        foreach($rows AS $row){
            $obj = new MessagesProxy();
            foreach($row as $key => $value){
                $obj->{$key} = $value;
            }
            $arr[] = $obj;
        }
        return $arr;
    }

    public static function getOutbox( $receiver, $status=array(1,2) ){
        $sql = sprintf("SELECT * from messages WHERE `sender`='%s' and box=%d and status in (%s) ORDER BY created DESC"
            ,DBConnectionHelper::getInstance()->escape($receiver)
            ,2
            ,implode(',',$status)
        );
        $rows = DBConnectionHelper::getInstance()->query($sql);
        $arr = array();
        foreach($rows AS $row){
            $obj = new MessagesProxy();
            foreach($row as $key => $value){
                $obj->{$key} = $value;
            }
            $arr[] = $obj;
        }
        return $arr;
    }

    public static function getTrashbox( $receiver, $status=array(1,2) ){
        $sql = sprintf("SELECT * from messages WHERE `sender`='%s' and box=%d and status in (%s) ORDER BY created DESC"
            ,DBConnectionHelper::getInstance()->escape($receiver)
            ,3
            ,implode(',',$status)
        );
        $rows = DBConnectionHelper::getInstance()->query($sql);
        $arr = array();
        foreach($rows AS $row){
            $obj = new MessagesProxy();
            foreach($row as $key => $value){
                $obj->{$key} = $value;
            }
            $arr[] = $obj;
        }
        return $arr;
    }


}
