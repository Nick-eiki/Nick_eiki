<?php

namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-3-23
 * Time: 上午10:47
 */
class pos_MessageSendByUserService extends  BaseService{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/messageSentByUser?wsdl");
    }
    /**s
     * 3.44.1.	通过id发送消息
     * @param $objectID
     * @param $messageType
     * @return ServiceJsonResult
     */
    public function sendMessageByObjectId($objectID,$messageType,$fromUserid,$receiverUserID=""){
        $array=array(
            "objectId"=>$objectID,
            "messageType"=>$messageType,
            "fromUserid"=>$fromUserid,
            "receiverUserID"=>$receiverUserID
        );
        $soapResult = $this->_soapClient->sendMessageByObjectId($array);
        return $this->soapResultToJsonResult($soapResult);
    }

}