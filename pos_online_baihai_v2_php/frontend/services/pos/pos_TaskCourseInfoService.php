<?php

namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-9-17
 * Time: 上午10:21
 */
class pos_TaskCourseInfoService extends BaseService{
    function __construct(){
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService']."schoolService/taskCourseInfo?wsdl");
    }

    /**
     * 高 2014.9.17
     * 3.7.1.教研课题搜索
     * 接口地址	http://主机地址:端口号/ schoolService /taskCourseInfo?wsdl
     * 方法名	taskCourseSearch
     * @param $courseName       课题名(可为空)
     * @param $gradeID          年级id（可为空）
     * @param $creatorID        创建者id（可为空）
     * @param $teachingGroupID  教研组id（可为空）
     * @param $ispage           是否分页，0:不分页，1：分页，默认0
     * @param $currPage         当前页数
     * @param $pageSize         每页条数
     * @param $token            安全保护措施
     * {"countSize":"1",
    "pageSize":"2",
    "currPage":"1"
    ,"courseList":[{
    “courseID”:”课题id”
    "courseName":"课题名",
    "gradeID":"年级id",
    “gradeName”:”年级名”
    "brief":"课题描述"
    “courseMembers”:”成员”
    },
    ],
    "totalPages":"1"},
    "resCode":"000000",
    "resMsg":"查询成功"}
     *
     * @return array
     */
    public function taskCourseSearch($type,$courseName,$gradeID,$creatorID,$teachingGroupID,$ispage,$currPage,$pageSize){
        $soapResult = $this->_soapClient->taskCourseSearch(array('type'=>$type,'courseName'=>$courseName,'gradeID' => $gradeID,'creatorID'=>$creatorID,'teachingGroupID'=>$teachingGroupID,'ispage'=>$ispage,'currPage'=>$currPage,'pageSize'=>$pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 高 2014.9.17
     * 3.7.2.教研课题添加
     * 接口地址	http://主机地址:端口号/ schoolService /taskCourseInfo?wsdl
     * 方法名	taskCourseAdd
     * @param $courseName       课题名
     * @param $gradeID          年级id
     * @param $creatorID        创建者id
     * @param $courseMembers    课题成员，多个成员id之间用“，”分隔
     * @param $teachingGroupID  教研组id
     * @param $brief            课题描述
     * @param $url              课题要求附件URL
     * @param $token            安全保护措施
     * {"data":
    {
    “courseID”：””
    },
    "resCode":"000000",
    "resMsg":"添加成功"}
     * @return ServiceJsonResult
     */
    public function taskCourseAdd($type,$courseName,$gradeID,$creatorID,$courseMembers,$teachingGroupID,$brief,$url,$token){
        $soapResult = $this->_soapClient->taskCourseAdd(array('type'=>$type,'courseName' => $courseName,'gradeID'=>$gradeID,'creatorID'=>$creatorID,'courseMembers'=>$courseMembers,'teachingGroupID'=>$teachingGroupID,'brief'=>$brief,'url'=>$url,'token'=>$token));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 高 2014.9.17
     * 3.7.3.教研课题修改
     * 接口地址	http://主机地址:端口号/ schoolService /taskCourseInfo?wsdl
     * 方法名	taskCourseModify
     * @param $courseID         课题id
     * @param $courseName       课题名
     * @param $gradeID          年级id
     * @param $courseMembers    课题成员，多个成员id之间用“，”分隔
     * @param $teachingGroupID  教研组id
     * @param $brief            课题描述
     * @param $url              课题要求附件URL
     * @param $token            安全保护措施
     * {"data":
    {
    “courseID”：””
    },
    "resCode":"000000",
    "resMsg":"修改成功"}}
     * @return ServiceJsonResult
     */
    public function taskCourseModify($courseID,$courseName,$gradeID,$courseMembers,$teachingGroupID,$brief,$url,$token){
        $soapResult = $this->_soapClient->taskCourseModify(array("courseID" => $courseID,'courseName'=>$courseName,'gradeID'=>$gradeID,'courseMembers'=>$courseMembers,'teachingGroupID'=>$teachingGroupID,'brief'=>$brief,'url'=>$url,'token'=>$token));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 高 2014.9.17
     * 教研课题详情查询（通过id）
     * 接口地址	http://主机地址:端口号/ schoolService /taskCourseInfo?wsdl
     * 方法名	taskCourseSearchById
     * @param $courseID     课题id
     * @param $token        安全保护措施
     * {"data":
    {
    “courseID”:”课题id”
    "courseName":"课题名",
    "gradeID":"年级id",
    “gradeName”:”年级名”
    "brief":"课题描述"
    “url”:”url”
    “courseMemberList”:[//成员列表
    {“memberID”:”成员id”
    “memberName”：”成员名”
    }
     * @return ServiceJsonResult
     */
    public function taskCourseSearchById($courseID,$token){
        $soapResult = $this->_soapClient->taskCourseSearchById(array('courseID' => $courseID,'token'=>$token));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 高 2014.9.23
     * 3.18.6.教研报告列表（日记）查询
     * 接口地址	http://主机地址:端口号/ schoolService /taskCourseInfo?wsdl
     *方法名	dairySearchByTaskCourseID
     * @param $courseID     课题id
     * @param $currPage     当前页码
     * @param $pageSize     每页条数
     * @param $token        安全保护措施
     * {
    "currPage":"当前页码",
    "totalPages":"总页数",
    "countSize":"总记录数",
    "pageSize":"每页数据的条数"
    “courseDairyList”:[//日记列表
    {“diaryID”:”日记id”
    “headline”：”日记名”
    “teacherID”:”教师id”
    “teacherName”:”教师名”
    “createTime”:”日记创建时间”
    }]
     * @return ServiceJsonResult
     */
    public function dairySearchByTaskCourseID($courseID,$currPage,$pageSize,$token){
        $soapResult = $this->_soapClient->dairySearchByTaskCourseID(array('courseID' => $courseID,'currPage'=>$currPage,'pageSize'=>$pageSize,'token'=>$token));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        $result = $this->mapperJsonResult($json);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 课题成员查询所能见课题（通过成员id）
     * @param $memberID
     * @return array
     */
    public function taskCourseSearchByMember($memberID){
        $soapResult = $this->_soapClient->taskCourseSearchByMember(array('memberID' => $memberID));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        $result = $this->mapperJsonResult($json);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 3.18.8.课题成员查询所能见课题（通过成员id）（展示列表需要分页）
     * 接口地址	http://主机地址:端口号/ schoolService /taskCourseInfo?wsdl
     * 方法名	searchByMemberPager
     * @param $memberID 成员id，用户id
     * @param $currPage
     * @param $pageSize
     * {
    {"data":
    {"countSize":"1",
    "pageSize":"2",
    "currPage":"1"
    ,"courseList":[{
    "courseID":"课题id"
    "courseName":"课题名",
    "gradeID":"年级id",
    "gradeName":"年级名"
    "brief":"课题描述"
   },
    "resCode":"000000",
    "resMsg":"查询成功"}}
     * @return array
     */
    public function searchByMemberPager($memberID,$gradeID,$currPage,$pageSize){
        $soapResult = $this->_soapClient->searchByMemberPager(array('memberID' => $memberID,'gradeID'=>$gradeID,'currPage'=>$currPage,'pageSize'=>$pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }
}
