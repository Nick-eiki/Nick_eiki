<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/17
 * Time: 19:10
 */

namespace common\services;


use Httpful\Mime;
use Httpful\Request;
use Yii;
use yii\base\Exception;

class HomeworkPushService {

    private $Uri = null;

    function __construct(){
        $this->Uri = Yii::$app->params['homeworkPush']."/posidon/messagemanager/send.se";
    }

    /**
     * xmpp推送作业消息
     */
    public function Push($pushUserId , $receiverId , $mainType , $messageType , $obejectID , $messageTitle , $messageContent){
        try{
            $result= Request::post($this->Uri)
                ->body(http_build_query(["senderId"=>$pushUserId,
                    "receiverId"=>$receiverId,
                    "mainType"=>$mainType,
                    "messageType"=>$messageType
                    ,"objectID"=>$obejectID,
                    "messageTitle"=>$messageTitle,
                    "messageContent"=>$messageContent]))
                ->sendsType(Mime::FORM)
                ->send();
            return    $result->body;
        }catch (Exception $e){
            return '';
        }
    }

} 