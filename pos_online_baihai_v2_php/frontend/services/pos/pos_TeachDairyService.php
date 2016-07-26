<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-9-19
 * Time: 下午2:24
 */
class pos_TeachDairyService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/teachDairy?wsdl");
    }

    /**
     * 高 2014.9.19
     * 2.4.15查询日记
     * 接口地址    http://主机地址:端口号/ schoolService / teachDairy?wsdl
     * 方法名    queryTeachDairy
     * @param $userID       用户ID
     * @param $headline     标题
     * @param $diaryType    日记类别(1:评课，2：课题，3：随笔)
     * @param $createTime   日记创建时间
     * @param $diaryID      日记id
     * @param $currPage     当前显示页码，可以为空,默认值1
     * @param $pageSize     每页显示的条数，可以为空，默认值10
     * @param $token        用于安全控制，暂时为空
     *
     * "data":{
     * "currPage":"当前页码",
     * "totalPages":"总页数",
     * "countSize":"总记录数",
     * "pageSize":"每页数据的条数",
     * "list":[//列表
     * { "diaryID":"",日记id
     * "headline":"",//标题
     * "diaryType":"",//日记类别
     * "lectureID":"",//听课计划id
     * "lectureTitle":"",//听课计划标题
     * "teacherID":"",//主讲人id
     * "teacherName":"",//主讲人姓名
     * "chapterId":"",//章节id
     * "chapterName":"",//章节名称
     * "courseID":"",//课题id
     * "courseName":"",//课题名称
     * " diaryInfo":""//日记内容
     * "creatorID":"",//教师id
     * "creatorName":"",//教师名称
     * "createTime":"",//日记创建时间
     * "updateTime":"",//最后一次修改时间
     * "limitsOfReading":""阅读权限0：不公开1：公开2：学校内部可见默认值：0
     * },
     * ...
     * ]
     * @return ServiceJsonResult
     */
    public function queryTeachDairy($diaryID = '', $userID = '', $headline = '', $diaryType = '', $currPage = '', $pageSize = '', $diaryInfo = '', $lectureID = '', $courseID = '', $createTime = '')
    {
        $soapResult = $this->_soapClient->queryTeachDairy(
            array(
                "userID" => $userID,
                "headline" => $headline,
                "diaryType" => $diaryType,
                "createTime" => $createTime,
                "diaryID" => $diaryID,
                "diaryInfo" => $diaryInfo,
                "lectureID" => $lectureID,
                "courseID" => $courseID,
                "currPage" => $currPage,
                "pageSize" => $pageSize,
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 高 2014.9.19
     * 2.4.14写日记
     * 接口地址    http://主机地址:端口号/ schoolService / teachDairy?wsdl
     *方法名    createTeachDairy
     * @param $headline     标题
     * @param $diaryType    日记类别(1:评课，2：课题，3：随笔)
     * @param $lectureID    听课计划id
     * @param $courseID     课题id
     * @param $diaryInfo    日记内容
     * @param $creatorID    创建人id
     * @param $token        于安全控制，暂时为空
     *
     * resCode":"000000",
     * "resMsg":"创建成功",
     * "data":{
     * }
     * @return ServiceJsonResult
     */
    public function createTeachDairy($headline = '', $diaryType = '', $lectureID = '', $courseID = '', $diaryInfo = '', $creatorID = '')
    {
        $soapResult = $this->_soapClient->createTeachDairy(
            array(
                "headline" => $headline,
                "diaryType" => $diaryType,
                "lectureID" => $lectureID,
                "courseID" => $courseID,
                "diaryInfo" => $diaryInfo,
                "creatorID" => $creatorID,
            ));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 更新日记
     * @param $id
     * @param string $headline
     * @param string $diaryType
     * @param string $lectureID
     * @param string $courseID
     * @param string $diaryInfo
     * @param string $creatorID
     * @return ServiceJsonResult
     */
    public function updateTeachDairy($id, $headline = '', $diaryType = '', $lectureID = '', $courseID = '', $diaryInfo = '', $creatorID = '')
    {
        $soapResult = $this->_soapClient->updateTeachDairy(
            array(
                "diaryID" => $id,
                "headline" => $headline,
                "diaryType" => $diaryType,
                "lectureID" => $lectureID,
                "courseID" => $courseID,
                "diaryInfo" => $diaryInfo,
                "creatorID" => $creatorID,
            ));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.12.5.查询上一篇日记
     * 接口地址	http://主机地址:端口号/ schoolService / teachDairy?wsdl
     * 方法名	queryPreviousPageByid
     * @param $userID   用户ID
     * @param $diaryID  日记id
     * {
    "resCode":"000000",
    "resMsg":"查询成功",
    "data":{
    "list":[//列表
    { "diaryID":"",日记id
    "headline":"",//标题
    "diaryType":"",//日记类别
    "lectureID":"",//听课计划id
    "lectureTitle":"",//听课计划标题
    "teacherID":"",//主讲人id
    "teacherName":"",//主讲人姓名
    "chapterId":"",//章节id
    "chapterName":"",//章节名称
    "courseID":"",//课题id
    "courseName":"",//课题名称
    " diaryInfo":""//日记内容
    "creatorID":"",//教师id
    "creatorName":"",//教师名称
    "createTime":"",//日记创建时间
    "updateTime":"",//最后一次修改时间
    "limitsOfReading":""阅读权限0：不公开1：公开2：学校内部可见默认值：0
    },
    ...
    ]
    }
    }
     *
     * @return ServiceJsonResult
     */
    public function queryPreviousPageByid($userID,$diaryID,$diaryType){
        $soapResult = $this->_soapClient->queryPreviousPageByid(
            array(
                "userID" => $userID,
                "diaryID" => $diaryID,
                "diaryType"=>$diaryType
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return null;
    }

    /**
     * 3.12.6.查询下一篇日记
     *  接口地址	http://主机地址:端口号/ schoolService / teachDairy?wsdl
     *  方法名	queryNextPageByid
     * @param $userID   用户ID
     * @param $diaryID  日记id
     * {
    "resCode":"000000",
    "resMsg":"查询成功",
    "data":{
    "list":[//列表
    { "diaryID":"",日记id
    "headline":"",//标题
    "diaryType":"",//日记类别
    "lectureID":"",//听课计划id
    "lectureTitle":"",//听课计划标题
    "teacherID":"",//主讲人id
    "teacherName":"",//主讲人姓名
    "chapterId":"",//章节id
    "chapterName":"",//章节名称
    "courseID":"",//课题id
    "courseName":"",//课题名称
    " diaryInfo":""//日记内容
    "creatorID":"",//教师id
    "creatorName":"",//教师名称
    "createTime":"",//日记创建时间
    "updateTime":"",//最后一次修改时间
    "limitsOfReading":""阅读权限0：不公开1：公开2：学校内部可见默认值：0
    },
    ...
    ]
    }
    }
     *
     * @return ServiceJsonResult
     */
    public function queryNextPageByid($userID,$diaryID,$diaryType){
        $soapResult = $this->_soapClient->queryNextPageByid(
            array(
                "userID" => $userID,
                "diaryID" => $diaryID,
                "diaryType"=>$diaryType
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return null;
    }

}