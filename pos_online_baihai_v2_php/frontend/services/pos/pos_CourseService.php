<?php

namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-9-9
 * Time: 下午12:58
 */
class pos_CourseService extends BaseService{
    function __construct(){
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService']."schoolService/Course?wsdl");
    }


    /**
     * 点播课程 创建
     *  wgl 14-11-25 修改
     * @param string $type              课程类型 0：精品课程 1：每周一课
     * @param string $gradeID           年纪ID
     * @param string $subjectID         科目ID
     * @param string $version           教材版本
     * @param string $courseName        课程名称
     * @param string $courseBrief       课程简介
     * @param string $teacherID         授课教师ID

     * @param string $classID	        班名id
     * @param string $stuLimit	        权限（本班学生）0：不可见1：可见 (可为空)
     * @param string $groupMemberLimit	权限（教研组同事）0：不可见1：可见 (可为空)
     * @param string $allMemLimit	    权限（所有）0：不可见1：可见 (可为空)

     * @param string $cost              是否收费（可为空）
     * @param string $provience         省
     * @param string $city              市、地区
     * @param string $country           县城
     * @param string $creatorID         创建者ID
     * @param string $schoolProportion  学校比例（可为空）
     * @param string $teacherProportion 教师比例（可为空）
     * @param string $price             价格
     * @param string $isAgreement       是否达成分账
     * @param string $url               视频地址（可为空）
     * @param string $courseHourList
     * @return ServiceJsonResult
     */
    public function createdibbleCourse($type = '', $gradeID = '', $subjectID = '', $version = '', $courseName = '', $courseBrief = '', $teacherID = '', $classID = '', $stuLimit = '', $groupMemberLimit = '', $allMemLimit = '', $cost = '', $provience = '', $city = '', $country = '', $creatorID = '',  $schoolProportion = '',$teacherProportion = '', $price = '', $isAgreement = '', $isShare = '' , $url = '', $courseHourList = ''){
        $soapResult = $this->_soapClient->createdibbleCourse(array(
            "type" => $type,
            'gradeID'=>$gradeID,
            'subjectID'=>$subjectID,
            'version' => $version,
            'courseName' => $courseName,
            'courseBrif' => $courseBrief,
            'teacherID' => $teacherID,
            'classID' => $classID,
            'stuLimit' => $stuLimit,
            'groupMemberLimit' => $groupMemberLimit,
            'allMemLimit' => $allMemLimit,
            'cost' => $cost,
            'provience' => $provience,
            'city' => $city,
            'country' => $country,
            'creatorID' => $creatorID,
            'schoolProportion' => $schoolProportion,
            'teacherProportion' => $teacherProportion,
            'price' => $price,
            'isAgreement' => $isAgreement,
            'isShare' => $isShare,
            'url' => $url,
            'courseHourList' => $courseHourList
        ));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     * 每周一课
     * @param string $gradeID
     * @param string $subjectID
     * @param string $version
     * @param string $courseName
     * @param string $courseBrief
     * @param string $provience
     * @param string $city
     * @param string $country
     * @param string $creatorID

     * @param string $price
     * @param int $type
     * @param string $teacherID
     * @param string $teacherName
     * @param string $cost
     * @return ServiceJsonResult
     */
    public function createVideoCourse($type, $gradeID, $subjectID, $version, $courseName , $courseBrief , $provience , $city, $country, $creatorID,$classID, $url,$teacherId ){
        return $this->createdibbleCourse(
            $type, $gradeID, $subjectID, $version, $courseName, $courseBrief, $teacherId, $classID, '','','','', $provience, $city, $country, $creatorID,'', '','','','', $url,''
        );
    }


    /**
     * 2.4.3查询点播课程列表
     * @param string $userID        用户ID
     * @param string $subjectID	    科目id（可为空）
     * @param string $gradeID	    年级id（可为空）
     * @param string $version	    教材版本id（可为空）
     * @param string $courseName	课程名（可为空）
     * @param string $courseBrif	课程简介（可为空）
     * @param string $type	        课程类型(可为空)、 0：精品课程、 1：每周一课
     * @param string $classID	    班级ID 当传入班级时 表示查询某个班级的课程 （此参数可为空）
     * @param string $creatorID	    创建人id，（可为空）查询某个教师创建的课程
     * @param string $currPage
     * @param string $pageSize
     * @return array
     *
     * 失败	     返回的JSONB：参考响应代码

        成功	     返回的JSON示例：
                当用户ID为教师时查询结果如下
                        {
                        "data":
                        {
                        "pageSize":"10",
                        "countSize":"2",
                        "courseList":
                        [
                        {

                        “isCollected“:"" 0未收藏 1收藏
                        “collectID“:收藏id

                        "couresID":"10120",课程id
                        "courseBrief":"sfagagef",课程简介
                        "type":"0",课程类型
                        “typeName”课程类型名称
                        "courseName":"课程名称"
                        “creatorID”:创建人ID
                        },
                        {
                        "couresID":"10119",
                        "courseBrief":"sfagagergqerqrfqrf",
                        "type":"1",
                        “typeName”:课程类型名称
                        "courseName":"开学了"
                        “creatorID”:创建人ID
                        }
                        ],
                        "currPage":"1",
                        "totalPages":"1"
                        },
                        "resCode":"000000",
                        "resMsg":"成功"
                        }
     *
     */
    public function querydibbleCourse($userID = '', $subjectID = '', $gradeID = '', $version = '', $courseName = '', $courseBrif = '', $type = '', $classID = '', $creatorID, $currPage = '', $pageSize = ''){
        $soapResult = $this->_soapClient->querydibbleCourse(
            array(
                'userID' => $userID,
                'subjectID' => $subjectID,
                'gradeID' =>$gradeID,
                'version' => $version,
                'courseName' =>$courseName,
                'courseBrif' => $courseBrif,
                'type' => $type,
                'classID' => $classID,
                'creatorID' => $creatorID,
                'currPage' => $currPage,
                'pageSize' => $pageSize
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 点播课程详情
     * @param string $courseID	课程ID
     * @return array
     *
     * token	安全保护措施
        失败	     返回的JSONB：参考响应代码
        成功	     返回的JSON示例：
                当查询的为每周一课时返回如下
                {
                "data":
                {

                "teacherID":"1001",授课教师id
                "gradeID":"101",年级id
                gradeName 年级名
                "courseBrief":"sfagagergqerqrfqrf",简介
                "teacherName":"haidian",授课教师名称
                "url":"asdgargqregqrfafreqfqe",视频地址
                "subjctID":"101",科目id
                subjectName科目名称
                "country":"清河镇",
                "city":"海淀区",
                "provience":"北京市",
                "courseName":"开学了",
                "version":"人教版"
                versionName 版本名称
                },
                "resCode":"000000",
                "resMsg":"成功"
                }

                当课程为精品课程时返回如下
                {
                "data":
                {
                "teacherID":"1001",授课教师id
                "gradeID":"101",年级id
                gradeName 年级名
                "courseBrief":"sfagagergqerqrfqrf",简介
                "teacherName":"haidian",授课教师名称
                "url":"asdgargqregqrfafreqfqe",视频地址
                "subjctID":"101",科目id
                subjectName科目名称
                "country":"清河镇",
                "city":"海淀区",
                "provience":"北京市",
                "courseName":"开学了",
                "version":"人教版"
                versionName 版本名称


                "courseHourList":
                [
                {
                “cNum”: 节次号，如：第1节
                “cName”:节次名
                “type”：知识点或章节 ：0知识点 ，1 章节
                “kcid”:知识点或章节id
                “teachMaterialID": 讲义ID
                “videoUrl” ：视频url
                },
                {
                “cNum”: 节次号，如：第1节
                “cName”:节次名
                “type”：知识点或章节 ：0知识点 ，1 章节
                “kcid”:知识点或章节id
                “teachMaterialID": 讲义ID
                “videoUrl” ：视频url

                },
                ],

                },
                "resCode":"000000",
                "resMsg":"成功"
                }
     */
    public function querydibbleCourseDetailInfo($courseID = ''){
        $soapResult = $this->_soapClient->querydibbleCourseDetailInfo(array('courseID' => $courseID));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        $result = $this->mapperJsonResult($json);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }
    }