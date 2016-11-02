<?php
include_once('../config.php');

class userController {

	public static $THEME = THEME_DEFAULT;

    public static function profileAction($username){
        $ret = array();
        $ret['isUsersProfile'] = $username == $_SESSION['user'];
        $ret['user'] = UsersProxy::getByUsername($username);
        if(!$ret['user']){
            RequestHelper::redirect('/');
            return;
        }
        return TemplateHelper::renderToResponse(self::$THEME,"/html/users/profile.phtml",$ret);
    }

    public static function inboxUnreadCountAction(){
        header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
        header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) .  "GMT" );
        header( "Cache-Control: no-cache, must-revalidate" );
        header( "Pragma: no-cache" );
        if($_SESSION['user']){
            $c =  MessagesProxy::statusCount($_SESSION['user'],1,1);
            echo $c>0 ? '('.$c.')' : '';
            return;
        }
        return '';
    }

    public static function inboxAction(){
        $ret = array();
        $ret['box'] = MessagesProxy::getInbox($_SESSION['user'],array(1,2));
        return TemplateHelper::renderToResponse(self::$THEME,"/html/users/inbox.phtml",$ret);
    }

    public static function outboxAction(){
        $ret = array();
        $ret['box'] = MessagesProxy::getOutbox($_SESSION['user'],array(1,2));
        return TemplateHelper::renderToResponse(self::$THEME,"/html/users/outbox.phtml",$ret);
    }

    public static function trashboxAction(){
        $ret = array();
        $ret['box'] = MessagesProxy::getTrashbox($_SESSION['user'],array(1,2));
        return TemplateHelper::renderToResponse(self::$THEME,"/html/users/trashbox.phtml",$ret);
    }

    public static function readAction($id){
        $ret = array();
        $ret['message'] = MessagesProxy::get($id);
        if($ret['message']->sender != $_SESSION['user'] && $ret['message']->receiver != $_SESSION['user']){
            RequestHelper::redirect('/inbox/');
            return;
        }
        $ret['message']->subject = TemplateHelper::wrap($ret['message']->subject,15);
        $ret['message']->body = TemplateHelper::wrap($ret['message']->body,30);
        $ret['message']->status = 2;
        $ret['message']->save();
        return TemplateHelper::renderToResponse(self::$THEME,"/html/users/read.phtml",$ret);
    }

    public static function trashAction($id){
        $ret = array();
        $ret['message'] = MessagesProxy::get($id);
        if( $ret['message']->box == 3 ){
            MessagesProxy::remove($id);
            RequestHelper::redirect('/inbox/');
            return;
        }
        $ret['message']->box = 3;
        $ret['message']->save();
        RequestHelper::redirect('/inbox/');
    }

    public static function composeAction($username,$id=null){
        $ret = array();
        $ret['user'] = UsersProxy::getByUsername($username);
        $ret['reply'] = MessagesProxy::get($id);
        $ret["next"] = isset($_GET["next"]) ? InputFilterHelper::getString($_GET["next"]) : '/inbox/';
        if(!$ret['user']){
            RequestHelper::redirect('/');
        }
        if(RequestHelper::isPost())
        {
            if(!isset($_POST["subject"])){
                $ret["error"] = "Subject missing!";
            } else {
                $ret["subject"] = InputFilterHelper::getString($_POST["subject"]);
                if ( strlen($ret["subject"])>60 || strlen($ret["subject"])<2) {
                    $ret["error"] = "Subject is wrong!";
                }
            }
            if(!isset($_POST["body"])){
                $ret["error"] = "Body is missing!";
            } else {
                $ret["body"] = InputFilterHelper::getString($_POST["body"]);
            }
            /* write to session */
            if(!isset($ret['error'])){

                $inbox = new MessagesProxy();
                $inbox->subject = strip_tags($ret['subject']);
                $inbox->body = $ret['body'];
                $inbox->box = 1;
                $inbox->sender = $_SESSION["user"];
                $inbox->receiver = $username;
                $inbox->status = 1;
                $inbox->save();

                $outbox = new MessagesProxy();
                $outbox->subject = $ret['subject'];
                $outbox->body = $ret['body'];
                $outbox->box = 2;
                $outbox->sender = $_SESSION["user"];
                $outbox->receiver = $username;
                $outbox->status = 2;
                $outbox->save();

                RequestHelper::redirect($ret["next"]);
            }
        }
        return TemplateHelper::renderToResponse(self::$THEME,"/html/users/compose.phtml",$ret);
    }


}
