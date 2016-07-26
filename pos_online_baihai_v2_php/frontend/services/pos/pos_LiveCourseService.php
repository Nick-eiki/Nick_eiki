<?php
namespace frontend\services\pos;
use ArrayObject;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/12
 * Time: 10:44
 */
class pos_LiveCourseService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService']."schoolService/liveCourse?wsdl");
    }

    /**
     * 直播课程->创建课程
     * wgl 14-11-5 13:18
     *
     * @param string creatorID      课程创建人
     * @param string courseName     课程名称
     * @param string filesID        资源ID的字符串，多个用逗号隔开(知识点或者章节的ID)
     * @param string connetID       课程相关类型Id 0:知识点 1:章节
     * @param string handoutID      义ID 字符串
     * @param string beginTime      上课开始时间
     * @param string finishTime     上课结束时间
     * @param string url            广告URL
     * @param string courseBrief    课程介绍
     * @param string classId        班级id
     * @param string subjectID      科目id
     * @param string teacherID      上课老师id。如果有多个老师同时上课，老师id之间使用逗号分隔。
     * @param string versionID	    教材版本
     * @param string gradeID	    年级ID
                        失败      	返回的JSONB：参考响应代码
                        成功	         返回的JSON示例：
                                    {
                                    "data": {
                                    “courseID”:”直播课程ID”
                                    "resCode": "000000",
                                    "resMsg": "添加成功"
                                    }
     */


    public function createLiveInfo($creatorID, $courseName, $filesID, $connetID, $handoutID, $beginTime, $finishTime, $url, $courseBrief, $classId, $subjectID, $teacherID, $versionID, $gradeID )
    {
        $soapResult = $this->_soapClient->createLiveInfo(
            array(
                'creatorID' => $creatorID,
                'courseName' => $courseName,
                'filesID' => $filesID,
                'connetID' => $connetID,
                'handoutID' => $handoutID,
                'beginTime' => $beginTime,
                'finishTime' => $finishTime,
                'url' => $url,
                'courseBrief' => $courseBrief,
                'classId' => $classId,
                'subjectID' => $subjectID,
                'teacherID' => $teacherID,
                'versionID' =>$versionID,
                'gradeID' =>$gradeID
            ));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /*
     * 课程列表
     * wgl 14-11-12
     *
     * @param string courseName     课程名称（可为空）
     * @param string gradeID        年级Id（可为空）
     * @param string subjectID      科目id（可为空）
     * @param string userID       	用户ID可为空，用于判断讲义是否收藏
     * @param string currPage	    当前页码
     * @param string pageSize	    每页条数
     * 失败	返回的JSONB：参考响应代码
     * 成功	返回的JSON示例：
                {
                "data": {
                "countSize":"1",记录条数
                "pageSize":"2",每页条数
                "currPage":"1"当前页数
                "totalPages":"1"总页数
                “courseList”：[课程列表
                {
                “courseID”:”课程ID”
                     “courseName”：“课程名称”，
                        “beginTime”：”上课开始时间”，
                 “finishTime”: 上课结束时间
                 “courseBrief”:”课程介绍”
                  teacherName:”教师姓名”
                   subjectName 科目名称
                    className 班级名称
                   “subjectID”:“科目ID”
                   “gradeID” :”年级ID”
                “url”:”广告图片URL’，
                }
                ]
                    "resCode": "000000",
                    "resMsg": "查询成功"
                }
     */
    public function searchLiveCourse($courseName = '', $gradeID = '', $subjectID = '', $userID = '', $currPage = '', $pageSize = ''){
        $soapResult = $this->_soapClient->searchLiveCourse(
            array(
                'courseName' => $courseName,
                'gradeID' => $gradeID,
                'subjectID' => $subjectID,
                'userID' => $userID,
                'currPage' => $currPage,
                'pageSize' => $pageSize
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /*
     * 课程详情
     * wgl 14-11-13
     *
     * @param string courseID	课程ID
     * @param string userID	    用户ID可为空，用于判断讲义是否收藏
     *
     * token	安全保护措施
                失败	返回的JSONB：参考响应代码
                成功	返回的JSON示例：
                {
                "data": {
                “courseID”:”课程ID”
                 “courseName”：“课程名称”，
                 “beginTime”：”上课时间”，
                “courseBrief”:”课程介绍”
                 “url”:”广告图片URL’，
                “connetID”:”知识点或者章节类型”
                teacherName:”教师姓名”
                 “connetIDList”:[｛
                     filesID : “知识点或者章节的资源ID”
                ｝]
                    "resCode": "000000",
                    "resMsg": "查询成功"
                }
     *
     */
    public function searchLiveCourseByID($courseID,$userID){
        $soapResult = $this->_soapClient->searchLiveCourseByID(
            array(
                'courseID' => $courseID,
                'userID' => $userID
            ));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        return  $this->mapperJsonResult($json);
    }

    /**
     * 3.29.4.上传讲义
     * wgl 14-12-4
     *
     * @PARAM string courseID	课程id
     * @PARAM string handoutID	讲义id
     * @PARAM string creatorID	创建人id
     *
     * 失败	 返回的JSONB：

        成功	 返回的JSON示例：
                {
                "data": {

                "resCode": "000000",
                "resMsg": "上传成功"
                }
     */

    public function uploadHandoutToLiveCourse($courseID, $handoutID, $creatorID)
    {
        $soapResult = $this->_soapClient->uploadHandoutToLiveCourse(
            array(
                'courseID' => $courseID,
                'handoutID' => $handoutID,
                'creatorID' => $creatorID
            ));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        return  $this->mapperJsonResult($json);
    }

    /**
     * 3.29.5.进入课堂（用于生成上课记录）
     * 接口地址	http://主机地址:端口号/ schoolService / liveCourse?wsdll
     * 方法名	goToLiveCourse
     * 请求参数
     * @PARAM string courseID	课程id
     * @PARAM string studentID	学生id
     *          token	安全保护措施
     *            失败（被教师踢出后，无法进入课堂）	返回的JSONB：
                        {
                        "data": {

                        "resCode": "100001",
                        "resMsg": "进入失败"
                        }

                        成功	返回的JSON示例：
                        {
                        "data": {
                        “recordID”记录id
                        "resCode": "000000",
                        "resMsg": "进入成功"
                        }
     */
    public function goToLiveCourse($courseID, $studentID)
    {
        $soapResult = $this->_soapClient->goToLiveCourse(
            array(
                'courseID' => $courseID,
                'studentID' => $studentID,
            ));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        return  $this->mapperJsonResult($json);
    }

    /**
     * 3.29.6.离开课堂
     * 接口地址	http://主机地址:端口号/ schoolService / liveCourse?wsdll
     * 方法名	leaveLiveCourse
     * 请求参数
     * @PARAM string $courseID 学生ID
     * @PARAM string $studentID 学生id
     * @param string $outRemark 离开原因
     *          token	安全保护措施
     *            失败	返回的JSONB：
                    返回的JSON示例：
                    {
                    "data": {

                    "resCode": "000000",
                    "resMsg": "离开成功"
                    }
     */
    public function leaveLiveCourse($courseID, $studentID, $outRemark)
    {
        $soapResult = $this->_soapClient->leaveLiveCourse(
            array(
                'courseID' => $courseID,
                'studentID' => $studentID,
                'outRemark' => $outRemark
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 3.29.2.修改课程
     * 接口地址	http://主机地址:端口号/ schoolService / liveCourse?wsdl
     * 方法名	modifyLiveInfo
     * 请求参数
     * @param string $courseID	课程id
     * @param string $userID	当前用户ID，只有创建人能修改
     * @param string $courseName	课程名称
     * @param string $filesID	知识点或者章节的ID，多个用逗号隔开
     * @param string $connetID	课程相关类型Id 0:知识点 1:章节
     * @param string $handoutID	讲义ID
     * @param string $beginTime	上课开始时间
     * @param string $finishTime	上课结束时间
     * @param string $url	广告URL
     * @param string $courseBrief	课程介绍
     * @param string $classId	班级id
     * @param string $subjectID	科目id
     * @param string $teacherID	上课老师id。如果有多个老师同时上课，老师id之间使用逗号分隔。
     * @param string $versionID  教材版本
     * @param string $gradeID	年级ID
     *                  token	安全保护措施
     *  应答	             失败	返回的JSONB：参考响应代码
                        成功	     返回的JSON示例：
                                {
                                "data": {

                                "courseID":"直播课程ID"

                                "resCode": "000000",
                                "resMsg": "修改成功"
                                }
     */
    public function modifyLiveInfo($courseID,$userID,$courseName,$filesID, $connetID, $handoutID, $beginTime, $finishTime, $url, $courseBrief, $classId, $subjectID, $teacherID, $versionID, $gradeID){
        $soapResult = $this->_soapClient->modifyLiveInfo(
            array(
                "courseID" => $courseID,
                "userID" => $userID,
                "courseName" => $courseName,
                "filesID" => $filesID,
                "connetID" => $connetID,
                "handoutID" => $handoutID,
                "beginTime" => $beginTime,
                "finishTime" => $finishTime,
                "url" => $url,
                "courseBrief" => $courseBrief,
                "classId" => $classId,
                "subjectID" => $subjectID,
                "teacherID" => $teacherID,
                "versionID" => $versionID,
                "gradeID" => $gradeID
            ));
         return  $this->soapResultToJsonResult($soapResult);
    }
    /**
     * 3.30.5.查询今日课程列表
     * 接口地址	http://主机地址:端口号/ schoolService / liveCourse?wsdl
     * 方法名	searchTodayLiveCourse
     *
     * @param string $classId	班级Id
     * @param string $subjectID	科目Id（可为空）
     * @param string $currPage	当前页码
     * @param string $pageSize	每页条数
     *                  token	安全保护措施
     * 应答	             失败	返回的JSONB：参考响应代码
                        成功	返回的JSON示例：
                        {
                        "data": {
                        "countSize":"1",记录条数
                        "pageSize":"2",每页条数
                        "currPage":"1"当前页数
                        "totalPages":"1"总页数
                        "courseList"：[课程列表
                        {
                        "courseID":"课程ID"
                        "courseName"："课程名称"，
                        "beginTime"："上课时间"，
                        "finishTime
                        "courseBrief":"课程介绍"
                        "url":"广告图片URL"，
                        "classId":"班级id"，
                        "className":"班级名称"，
                        "subjectID":"科目id"，
                        "subjectName":"科目名称"，
                        "creatorID":"创建人"，
                        "createTime":"创建时间"，
                        "connetID":"知识点或者章节类型0:知识点 1:章  节"
                        "filesID":"知识点或章节"，
                        "handoutID":"讲义id"，
                        "teacherID":"上课老师id"，
                        "teacherName:"教师姓名
                        }
                        ]
                        "resCode": "000000",
                        "resMsg": "查询成功"
                        }
     */
    public function searchTodayLiveCourse($classId = '', $subjectID = '', $currPage = '', $pageSize = ''){
        $soapResult = $this->_soapClient->searchTodayLiveCourse(
            array(
                'classId' => $classId,
                'subjectID' => $subjectID,
                'currPage' => $currPage,
                'pageSize' => $pageSize
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }
}
