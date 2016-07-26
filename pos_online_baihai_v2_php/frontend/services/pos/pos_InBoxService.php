<?php
namespace frontend\services\pos;
use ArrayObject;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: a
 * Date: 14-6-27
 * Time: 上午10:59
 */

/**
 * Class TeacherService
 */
class pos_InBoxService extends BaseService
{


    /**
     * @return ServiceJsonResult
     */
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/inbox?wsdl");
    }


    /**
     *  发送私信
     * @param $senderId int 发信息人ID
     * @param $receiverId int 收件人ID
     * @param $content string 正文
     * @param $mailBoxId int 组ID
     * @return array
     */
    public function   senderMailBox($senderId, $receiverId, $content, $mailBoxId = null)
    {
        $soapResult = $this->_soapClient->senderMailBox(array("senderId" => $senderId, 'receiverId' => $receiverId, 'content' => $content, 'mailBoxId' => $mailBoxId));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);

        return $result;
    }

    /**
     * 返回指定用户发送和接收的私信
     * @param $userId int  用户ID
     * @param $currPage  int  当前页
     * @param $pageSize  int 页尺寸
     * @return array
     */
    public function  myMailBox($userId, $currPage = 1, $pageSize = 10)
    {
        $soapResult = $this->_soapClient->myMailBox(array("userId" => $userId, 'currPage' => $currPage, 'pageSize' => $pageSize));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);
        if ($result->resCode == self::successCode) {
            return array("currPage" => $result->data['currPage'],
                "countSize" => $result->data['countSize'],
                "pageSize" => $result->data['pageSize'],
                "totalPages" => $result->data['totalPages'],
                "myMailBoxList" => $result->data['myMailBoxList']);
        }
        return array();
    }


    /**
     * 对话详情（私信对话内容）
     * @param $userId int 当前用户ID
     * @param $mailBoxId int 私信组ID
     * @param $currPage int 当前显示页码，可以为空,默认值1
     * @param $pageSize int  每页显示的条数，可以为空，默认值10
     * @return array
     */
    public function  mailBoxList($userId, $mailBoxId, $currPage = 1, $pageSize = 10)
    {
        $soapResult = $this->_soapClient->mailBoxList(array("userId" => $userId, 'currPage' => $currPage, 'pageSize' => $pageSize, 'mailBoxId' => $mailBoxId));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);
        if ($result->resCode == self::successCode) {
            return array("currPage" => $result->data['currPage'],
                "countSize" => $result->data['countSize'],
                "pageSize" => $result->data['pageSize'],
                "mailBoxList" => $result->data['mailBoxList'],
                "otherName" => $result->data['otherName'],
                "otherId" => $result->data['otherId'],
                "senderName" => $result->data['senderName'],
                "receiverName" => $result->data['receiverName'],
                "receiverId" => $result->data['receiverId'],
                "senderId" => $result->data['senderId']);
        }
        return array();
    }

    /**
     * .删除私信组
     * @param $userId int   当前用户ID
     * @param $mailBoxId  int  私信组ID。可以为空
     * @return bool
     */
    public function  deleteMailBox($userId, $mailBoxId)
    {
        $soapResult = $this->_soapClient->deleteMailBox(array("userId" => $userId, 'mailBoxId' => $mailBoxId));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);

        return $result;
    }

    /**
     * .删除私信组
     * @param $userId int   当前用户ID
     * @param $mailBoxMessageId  int   私信ID。可以为空
     * @return bool
     */
    public function  deleteMailMessageBox($userId, $mailBoxMessageId)
    {
        $soapResult = $this->_soapClient->deleteMailMessageBox(array("userId" => $userId, 'mailBoxMessageId' => $mailBoxMessageId));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);

        return $result;
    }

    /**
     * .变成以读状态私信组
     * @param $userId int   当前用户ID
     * @param $mailBoxMessageId  int   私信ID。可以为空
     * @return bool
     */
    public function  readMessage($userId, $mailBoxId)
    {
        $soapResult = $this->_soapClient->readMessage(array("userId" => $userId, 'mailBoxId' => $mailBoxId));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return true;
        }
        return true;
    }


}