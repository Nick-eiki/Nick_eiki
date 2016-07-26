<?php
namespace frontend\services\pos;
use app\models\UserInfo;
use ArrayObject;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;
use JsonMapper;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-9-19
 * Time: 下午5:11
 */
class pos_PersonalInformationService extends BaseService
{

    function    __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/personalInformation?wsdl");
    }

    /**
     * @param $userID
     * @param $headImgUrl
     * @return ServiceJsonResult
     *头像修改
     */
    public function updateHeadImg($userID, $headImgUrl)
    {
        $soapResult = $this->_soapClient->updateHeadImg(array(
            "userID" => $userID,
            "headImgUrl" => $headImgUrl
        ));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        if ($this->mapperJsonResult($json)->resCode == self::successCode) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * 3.4.5.    根据用户id加载用户信息
     * @param $userId
     * @return null|object
     */
    public function  loadUserInfoById($userId)
    {
        $soapResult = $this->_soapClient->loadUserInfoById(array("userId" => $userId));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);
        if ($result->resCode == self::successCode) {
           if($result->data===null)
            {
                return null;
            }
            $mapper = new JsonMapper();
             return $mapper->map($result->data, new UserInfo());
        } else {
            return null;
        }

    }

    /*
     * wanggaoling 14-10-29
    * 查询用户头像信息
    * @param string $userID
    * @return array
    *
    */
    public function checkHeadImg($userID)
    {
        $soapResult = $this->_soapClient->checkHeadImg(array("userID" => $userID));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        $result = $this->mapperJsonResult($json);
        if ($result->resCode == self::successCode) {
            return $result->data->headImgUrl;
        }
        return '';
    }

    /**
     * 3.4.7.查询本班师生，手拉手班级师生，同教研组教师，同学校教师
     * @param $userId
     * @param int $sameClassTS 本班师生（1查出，0不查出）
     * @param int $friendClassTS 手拉手班级师生（1查出，0不查出）
     * @param int $sameGroupT 同教研组教师（1查出，0不查出)
     * @param int $sameSchoolT 同学校教师（1查出，0不查出）
     * @return null|object
     */
    public function  querySameUsers($userId, $sameClassTS = 1, $friendClassTS = 1, $sameGroupT = 1, $sameSchoolT = 1)
    {
        $soapResult = $this->_soapClient->querySameUsers(array("userId" => $userId, "sameClassTS" => $sameClassTS, "friendClassTS" => $friendClassTS, "sameGroupT" => $sameGroupT, "sameSchoolT" => $sameSchoolT));

        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);

        return $result;

    }

    /**
     * 3.4.9.    获取两个用户是否在同一个教研
     * @param $userOne
     * @param $userTwo
     * @return ServiceJsonResult
     */
    public function querySameGroupByTwoUser($userOne, $userTwo)
    {
        $soapResult = $this->_soapClient->querySameGroupByTwoUser(
            array(
                "userOne" => $userOne,
                "userTwo" => $userTwo
            ));

        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);
        return $result;
    }

    /**
     * 3.5.13.    用户个人管理中心统计项查询
     * @userID    用户id
     * token    安全保护措施
     * 查询失败    返回的JSONB：
     * {
     * "data":{},
     * "resCode":"应答代码",
     * "resMsg":"应答描述"
     * }
     * 查询成功    {
     * "data": {
     * "teaHwCnt": 0,//老师作业
     * "teaMatCnt": 0,//老师公文包
     * "teaCrsCnt": 0,//老师精品课程
     * "stuHwCnt": 0,//学生作业
     * "stuWrq": 45, //学生错题
     * "stuCrsCnt": 2//学生课程
     * },
     * "resCode": "000000",
     * "resMsg": "查询成功"
     * }
     */
    public function queryUserItemCnt($userId)
    {

        $soapResult = $this->_soapClient->queryUserItemCnt(
            array(
                "userID" => $userId
            ));

        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        $result = $this->mapperJsonResult($json);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }

        return null;
    }

    /**
     * 3.4.10.    获取两个用户是否在同一个班级
     * @param $userOne
     * @param $userTwo
     * @return ServiceJsonResult
     */
    public function querySameClassByTwoUser($userOne, $userTwo)
    {
        $soapResult = $this->_soapClient->querySameClassByTwoUser(
            array(
                "userOne" => $userOne,
                "userTwo" => $userTwo
            ));

        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);
        return $result;
    }

    /**
     * 3.5.11.当前用户是否能进入目标主页
     * @param userID    用户id
     * @param aimID    目标id
     * @param aimType 0学生主页,1教师主页,2教研组主页,3班级主页
     * @return ServiceJsonResult
     */
    public function judgeUserCanIn($userID, $aimID, $aimType)
    {
        $soapResult = $this->_soapClient->judgeUserCanIn(
            array(
                "userID" => $userID,
                "aimID" => $aimID,
                "aimType" => $aimType,
            ));

        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);
        return $result->data;
    }

    /**
     * 3.5.12.当前用户是否能修改目标主页()
     * @param userID    用户id
     * @param aimID    目标id
     * @param aimType 0学生主页,1教师主页,2教研组主页,3班级主页
     * @return ServiceJsonResult
     */
    public function judgeUserCanModify($userID, $aimID, $aimType)
    {
        $soapResult = $this->_soapClient->judgeUserCanModify(
            array(
                "userID" => $userID,
                "aimID" => $aimID,
                "aimType" => $aimType,
            ));

        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        $result = $this->mapperJsonResult($json);
        return $result->data;
    }

    /**
     * @param $aimID
     * @return bool
     */
    public static function isCanModify($aimID)
    {
        $model=  new self();
        $result= $model->judgeUserCanModify(user()->id,$aimID,3);

        if(isset($result->isCanModify) && $result->isCanModify==1){
            return true;
        }
        return false;
    }


    /**
     * 3.5.14.删除原有用户的教研组和班级
     * @param userID    用户id
     * @return ServiceJsonResult
     * {
        "data": {
        },
        "resCode": "000000",
        "resMsg": "删除成功"
        }
     */
    public function deleteOldRelation($userID)
    {
        $soapResult = $this->_soapClient->deleteOldRelation(
            array(
                "userID" => $userID,
            ));

        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);
        if ($result->resCode == self::successCode) {
            return true;
        }else{
            return false;
        }
    }


}