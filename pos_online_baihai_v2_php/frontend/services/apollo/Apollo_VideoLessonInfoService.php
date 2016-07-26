<?php

namespace frontend\services\apollo;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-8-14
 * Time: 上午10:50
 */

/**
 * Class VideoLessonInfoService
 */
class Apollo_VideoLessonInfoService extends BaseService
{

    /**
     *
     */
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['apollo_webService'] . "apollo/resource/videoLessonInfo?wsdl");
    }


    /**
     * 注：校外的时候还需要修改todo
     * 2.4.1.课程（视频）录入
     * @param classType    班类型（可为空）
     * @param  videoName    课程视频名称
     * @param  provience    省
     * @param  city    市
     * @param  country    县
     * @param  gradeID    适用年级  关联年级信息表的年级ID
     * @param  subjectID    科目 关联基础信息表的科目ID
     * @param  versionID    版本 关联基础信息表的版本ID
     * @param  imgUrl    广告图片（可为空）
     * @param  introduce    课程介绍
     * @param  lessonInfo   课时(视频)安排，采用json串传递
     * @param  teacher    授课教师
     * @param  isCharge    是否收费 0表示不收费 1表示收费
     * @param  price    价格
     * @param  sProportion    学校比例
     * @param  isAgreement    是否达成协议 0未达成 1已达成
     * @param  token    权限控制
     * @param $model
     * @param $classID
     * @param $lessonInfo
     * @param $matPackId 公文包id
     * {"data":{
     * "lid":""//资料ID
     * },
     * "resCode":"000001",
     * "resMsg":"录入成功"
     * @return ServiceJsonResult
     */
    public function videoLessonAdd($model, $classID, $lessonInfo, $matPackId)
    {
        $arr = array(
            'classType' => $model->type,
            'videoName' => $model->videoName,
            'provience' => $model->provience,
            'city' => $model->city,
            'country' => $model->county,
            'gradeID' => $model->gradeID,
            'subjectID' => $model->subjectID,
            'versionID' => $model->versionID,
            'classID' => $classID,
            'imgUrl' => $model->imgUrl,
            'introduce' => $model->introduce,
            'lessonInfo' => $lessonInfo,
            'teacher' => $model->teacher,
            'isCharge' => $model->isCharge,
            'price' => $model->price,
            'sProportion' => $model->sProportion,
            'isAgreement' => $model->isAgreement == 1 ? 1 : 0,
//	        'isShare' => $isShare,
            'matPackId' => $matPackId
        );
        $soapResult = $this->_soapClient->videoLessonAdd($arr);
        return $this->soapResultToJsonResult($soapResult);

    }

//	public function videoLessonAddEx($model,$classID,$lessonInfo,$matPackId)
//	{
//	return $this->videoLessonAdd();
//
//	}


    /**
     * 2.4.4.课程（视频）查询
     * 接口地址    http://主机地址:端口号/resource/videoLessonInfo?wsdl
     * 方法名    videoLessonSearch
     * @param $lid              课程id（可为空）不为空时通过id查询一个视频
     * @param string $classType 班类型（可为空）
     * @param string $videoName 课程视频名称（可为空）
     * @param string $provience 省（可为空）
     * @param string $city 市（可为空）
     * @param string $country 县（可为空）
     * @param string $gradeID 适用年级（可为空）
     * @param string $subjectID 科目（可为空）
     * @param string $versionID 版本（可为空）
     * @param string $classID 班名id（可为空）
     * @param string $introduce 课程介绍（可为空）
     * @param string $teacher 授课教师（可为空）
     * @param string $isCharge 是否收费 0表示不收费 1表示收费（可为空）
     * @param string $minPrice 价格下限（可为空）
     * @param string $maxPrice 价格上限（可为空）
     * @param string $currPage 当前显示页码，可以为空,默认值1
     * @param string $pageSize 每页显示的条数，可以为空，默认值10
     * @param string $token 安全控制
     * {"data":{
     * "currPage":"当前页码",
     * "totalPages":"总页数",
     * "countSize":"总记录数",
     * "pageSize":"每页数据的条数"
     * "videoLessonList":
     * [
     * {
     * "lid":1002,视频ID
     * "provience":"2", 省
     * "city":"3", 市
     * "country":"4", 县
     * "gradeID":"5", 年级
     * "gradeName":"二年级",
     * "subjectID":"6", 科目id
     * "subjectName":null,科目名称
     * "versionID":"7",版本id
     * "versionName":"",版本名称
     * "classType":"a1"班级类型
     * ,
     * "classID":"8",班级id
     * "lessoninfo":[
     * {
     * ”lvid“ :”节次id”
     * “cNum”: 节次号，如：第1节
     * “cName”:节次名
     * “type”：知识点或章节 ：0知识点 ，1 章节
     * “kcid”:知识点或章节id
     * “teachMaterialID": 讲义ID
     * “videoUrl” ：视频url
     * },
     * {
     * ”lvid“ :”节次id”
     * “cNum”: 节次号，如：第1节
     * “cName”:节次名
     * “type”：知识点或章节 ：0知识点 ，1 章节
     * “kcid”:知识点或章节id
     * “teachMaterialID": 讲义ID
     * “videoUrl” ：视频url
     *
     * },
     * ]
     * "teacherID":"11",教师id
     * “teacherName”:”教师名称“
     * "introduce":"9",简介
     * "isCharge":"1",是否收费
     * "price":"1000",价格
     * "tproportion":"0.6",教师分账比例
     * "isAgreement":"0",是否审核
     * "creattime":"2014-08-12 09:08:33" 创建时间
     * }]
     * },
     * "resCode":"000000",
     * "resMsg":"录入成功" }
     *
     * @return ServiceJsonResult
     */
    public function videoLessonSearch($lid, $classType = null, $userID = null, $videoName = null, $provience = null, $city = null, $country = null, $gradeID = null, $subjectID = null, $versionID = null, $classID = null, $introduce = null, $teacher = null, $isCharge = null, $minPrice = null, $maxPrice = null, $currPage = null, $pageSize = null)
    {

        $soapResult = $this->_soapClient->videoLessonSearch(array('lid' => $lid, 'classType' => $classType, 'userID' => $userID,
            'videoName' => $videoName, 'provience' => $provience, 'city' => $city, 'country' => $country, 'gradeID' => $gradeID, 'subjectID' => $subjectID, 'versionID' => $versionID, 'classID' => $classID,
            'introduce' => $introduce, 'teacher' => $teacher, 'isCharge' => $isCharge, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'currPage' => $currPage, 'pageSize' => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();

    }


    /**
     * 查询平台视频列表
     * @param $currPage
     * @param $pageSize
     * @return ServiceJsonResult
     */
    public function searchVideo($userID, $videoName, $provience, $city, $country, $gradeID, $subjectID, $currPage, $pageSize)
    {
        return $this->videoLessonSearch(null, null, $userID, $videoName, $provience, $city, $country, $gradeID, $subjectID, null, null, null, null, null, null, null, $currPage, $pageSize);
    }

    /**
     * 查询视频详情 一条
     * @param $id
     * @param $userID
     * @return ServiceJsonResult
     */
    public function searchVideoDetailById($id, $userID)
    {
        $result = $this->videoLessonSearch($id, null, $userID, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
        if (!empty($result->videoLessonList)) {
            return $result->videoLessonList[0];
        }
        return null;
    }

    /**
     * 添加视频内容
     * @param $lid
     * @param $lessonInfo
     * @return array
     */
    public function lessonInfoAdd($lid, $model)
    {
        $arr = array(
            'lid' => $lid,
            'cNum' => '第一节',
            'cName' => $model->name,
            'teachNotesUrl' => $model->doc,
            'videoUrl' => $model->video,
            'type' => $model->type,
            'kcid' => $model->kid,
        );
        $soapResult = $this->_soapClient->lessonInfoAdd($arr);
        $result = $this->soapResultToJsonResult($soapResult);

        return array($result->resCode, $result->data);
    }


    /**
     * 视频修改
     * @param $lid
     * @param $classType
     * @param $areaID1
     * @param $areaID2
     * @param $areaID3
     * @param $gradeid
     * @param $subjectid
     * @param $versionid
     * @param $className
     * @param $imgUrl
     * @param $introduce
     * @param $lessonInfo
     * @param $teacher
     * @param $isCharge
     * @param $price
     * @param $tProportion
     * @param $isAgreement
     * @return array
     */
    public function videoLessonModify($lid, $classType, $areaID1, $areaID2, $areaID3, $gradeid, $subjectid, $versionid, $className, $imgUrl, $introduce, $lessonInfo, $teacher, $isCharge, $price, $tProportion, $isAgreement)
    {
        $soapResult = $this->_soapClient->videoLessonModify(array('lid' => $lid, "classType" => $classType, 'areaID1' => $areaID1, 'areaID2' => $areaID2, 'areaID3' => $areaID3, 'gradeid' => $gradeid, 'subjectid' => $subjectid, 'versionid' => $versionid, 'className' => $className, 'imgUrl' => $imgUrl, 'introduce' => $introduce, 'lessonInfo' => $lessonInfo, 'teacher' => $teacher, 'isCharge' => $isCharge, 'price' => $price, 'tProportion' => $tProportion, 'isAgreement' => $isAgreement));
        $result = $this->soapResultToJsonResult($soapResult);

        return array($result->resCode, $result->data);
    }

    /**
     * 教研组-视频录入
     * @param classType    班类型（可为空）
     * @param  videoName    课程视频名称
     * @param  provience    省
     * @param  city    市
     * @param  country    县
     * @param  gradeID    适用年级  关联年级信息表的年级ID
     * @param  subjectID    科目 关联基础信息表的科目ID
     * @param  versionID    版本 关联基础信息表的版本ID
     * @param  imgUrl    广告图片（可为空）
     * @param  introduce    课程介绍
     * @param  lessonInfo   课时(视频)安排，采用json串传递
     * @param  teacher    授课教师
     * @param  isCharge    是否收费 0表示不收费 1表示收费
     * @param  price    价格
     * @param  sProportion    学校比例
     * @param  isAgreement    是否达成协议 0未达成 1已达成
     * @param  token    权限控制
     * @param $model
     * @param $classID
     * @param $lessonInfo
     * @param $matPackId 公文包id
     * @param $groupID 教研组id
     * {"data":{
     * "lid":""//资料ID
     * },
     * "resCode":"000001",
     * "resMsg":"录入成功"
     * @return ServiceJsonResult
     */
    public function videoLessonAddOrgUse($model, $classID = null, $lessonInfo, $isShare = '1', $matPackId = null, $groupId, $creatorID)
    {
        $arr = array(
            'classType' => $model->type,
            'videoName' => $model->courseName,
            'provience' => $model->provience,
            'city' => $model->city,
            'country' => $model->country,
            'gradeID' => $model->gradeID,
            'subjectID' => $model->subjectID,
            'versionID' => $model->version,
            'imgUrl' => $model->imgUrl,
            'introduce' => $model->introduce,
            'teacher' => $model->teacherID,
            'isCharge' => $model->isCharge,
            'price' => $model->price,
            'sProportion' => $model->schoolProportion,
            'tProportion' => $model->teacherProportion,
            'isAgreement' => $model->isAgreement == 1 ? 1 : 0,
            'schoolID' => $model->school,
            'classID' => $classID,
            'lessonInfo' => $lessonInfo,
            'isShare' => $isShare,
            'matPackId' => $matPackId,
            'groupID' => $groupId,
            'creatorID' => $creatorID
        );
        $soapResult = $this->_soapClient->videoLessonAddOrgUse($arr);
        return $this->soapResultToJsonResult($soapResult);

    }

    /**
     * 2.5.2.教研组课程（视频）查询
     * 接口地址    http://主机地址:端口号/resource/videoLessonInfo?wsdl
     * 方法名    videoLessonSearchOrgUse
     * @param $lid              课程id（可为空）不为空时通过id查询一个视频
     * @param string $classType 班类型（可为空）
     * @param string $videoName 课程视频名称（可为空）
     * @param string $provience 省（可为空）
     * @param string $city 市（可为空）
     * @param string $country 县（可为空）
     * @param string $gradeID 适用年级（可为空）
     * @param string $subjectID 科目（可为空）
     * @param string $versionID 版本（可为空）
     * @param string $classID 班名id（可为空）
     * @param string $introduce 课程介绍（可为空）
     * @param string $teacher 授课教师（可为空）
     * @param string $isCharge 是否收费 0表示不收费 1表示收费（可为空）
     * @param string $minPrice 价格下限（可为空）
     * @param string $maxPrice 价格上限（可为空）
     * @param string $groupID 教研组id
     * @param string $currPage 当前显示页码，可以为空,默认值1
     * @param string $pageSize 每页显示的条数，可以为空，默认值10
     * @param string $token 安全控制
     * {"data":{
     * "currPage":"当前页码",
     * "totalPages":"总页数",
     * "countSize":"总记录数",
     * "pageSize":"每页数据的条数"
     * "videoLessonList":
     * [
     * {
     * "lid":1002,视频ID
     * videoName:视频名称
     * "provience":"2", 省
     * "city":"3", 市
     * "country":"4", 县
     * “schoolID”:””学校ID
     * “schoolName”:””学校名称
     * "gradeID":"5", 年级
     * "gradeName":"二年级",
     * "subjectID":"6", 科目id
     * "subjectName":null,科目名称
     * "versionID":"7",版本id
     * "versionName":"",版本名称
     * "classType":"a1"班级类型
     * ,
     * "classID":"8",班级id
     * "lessoninfo":[
     * {
     * ”lvid“ :”节次id”
     * “cNum”: 节次号，如：第1节
     * “cName”:节次名
     * “type”：知识点或章节 ：0知识点 ，1 章节
     * “kcid”:知识点或章节id
     * “teachMaterialID": 讲义ID
     * “teachMaterialName": 讲义名称
     * “videoUrl” ：视频url
     * },
     * {
     * ”lvid“ :”节次id”
     * “cNum”: 节次号，如：第1节
     * “cName”:节次名
     * “type”：知识点或章节 ：0知识点 ，1 章节
     * “kcid”:知识点或章节id
     * “teachMaterialID": 讲义ID
     * “videoUrl” ：视频url
     *
     * },
     *
     * ]
     * "teacherID":"11",教师id
     * “teacherName”:”教师名称“
     * "introduce":"9",简介
     * "isCharge":"1",是否收费
     *
     * "price":"1000",价格
     * "tproportion":"0.6",教师分账比例
     * "isAgreement":"0",是否审核
     * "creattime":"2014-08-12 09:08:33" 创建时间
     *
     * isFavorite,0未收藏，1已收藏
     * collectID收藏id
     * }]
     * },
     * "resCode":"000000",
     * "resMsg":"录入成功" }
     *
     * @return ServiceJsonResult
     */
    public function videoLessonSearchOrgUse($lid, $classType = null, $userID = null, $videoName = null, $provience = null, $city = null, $country = null, $gradeID = null, $subjectID = null, $versionID = null, $classID = null, $introduce = null, $teacher = null, $isCharge = null, $minPrice = null, $maxPrice = null, $groupID = null, $currPage = null, $pageSize = null)
    {

        $soapResult = $this->_soapClient->videoLessonSearchOrgUse(array('lid' => $lid, 'classType' => $classType, 'userID' => $userID,
            'videoName' => $videoName, 'provience' => $provience, 'city' => $city, 'country' => $country, 'gradeID' => $gradeID, 'subjectID' => $subjectID, 'versionID' => $versionID, 'classID' => $classID,
            'introduce' => $introduce, 'teacher' => $teacher, 'isCharge' => $isCharge, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'groupID' => $groupID, 'currPage' => $currPage, 'pageSize' => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();

    }

}