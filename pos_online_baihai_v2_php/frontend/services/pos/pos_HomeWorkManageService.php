<?php

namespace frontend\services\pos;
use ArrayObject;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-5
 * Time: 上午10:05
 */
class pos_HomeWorkManageService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/homeworkManage?wsdl");
    }

    /**
     * 3.22.1.    上传作业
     * @param $classID
     * @param $name
     * @param $provience
     * @param $city
     * @param $country
     * @param $gradeId
     * @param $subjectId
     * @param $version
     * @param $knowledgeId
     * @param $homeworkDescribe
     * @param $creator
     * @param $deadlineTime
     * @param $imageUrls
     * @return ServiceJsonResult
     */
    public function uploadHomework($classID,
                                   $name,
                                   $provience,
                                   $city,
                                   $country,
                                   $gradeId,
                                   $subjectId,
                                   $version,
                                   $knowledgeId,
                                   $homeworkDescribe,
                                   $creator,
                                   $deadlineTime,
                                   $imageUrls
    )
    {
        $imageUrlArray = array();
        foreach (explode(",", $imageUrls) as $v) {
            array_push($imageUrlArray, array("url" => $v));

        }

        $dataArray["images"] = $imageUrlArray;
        $soapResult = $this->_soapClient->uploadHomework(
            array(
                "classID" => $classID,
                "name" => $name,
                "provience" => $provience,
                "city" => $city,
                "country" => $country,
                "gradeId" => $gradeId,
                "subjectId" => $subjectId,
                "version" => $version,
                "knowledgeId" => $knowledgeId,
                "homeworkDescribe" => $homeworkDescribe,
                "creator" => $creator,
                "deadlineTime" => $deadlineTime,
                "imageUrls" => json_encode($dataArray),
            )
        );
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.22.2.    修改上传作业
     * @param $homeworId
     * @param $classID
     * @param $name
     * @param $provience
     * @param $city
     * @param $country
     * @param $gradeId
     * @param $subjectId
     * @param $version
     * @param $knowledgeId
     * @param $homeworkDescribe
     * @param $creator
     * @param $deadlineTime
     * @param $imageUrls
     * @return ServiceJsonResult
     */
    public function updateUploadHomework($homeworkId,
                                         $classID,
                                         $name,
                                         $provience,
                                         $city,
                                         $country,
                                         $gradeId,
                                         $subjectId,
                                         $version,
                                         $knowledgeId,
                                         $homeworkDescribe,
                                         $creator,
                                         $deadlineTime,
                                         $imageUrls
    )
    {
        $imageUrlArray = array();
        foreach (explode(",", $imageUrls) as $v) {
            array_push($imageUrlArray, array("url" => $v));

        }

        $dataArray["images"] = $imageUrlArray;
        $soapResult = $this->_soapClient->updateUploadHomework(
            array(
                "homeworkId" => $homeworkId,
                "classID" => $classID,
                "name" => $name,
                "provience" => $provience,
                "city" => $city,
                "country" => $country,
                "gradeId" => $gradeId,
                "subjectId" => $subjectId,
                "version" => $version,
                "knowledgeId" => $knowledgeId,
                "homeworkDescribe" => $homeworkDescribe,
                "creator" => $creator,
                "deadlineTime" => $deadlineTime,
                "imageUrls" => json_encode($dataArray),
            )
        );
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.22.2.    修改上传作业
     * @param $homeworId
     * @param $imageUrls
     * @return ServiceJsonResult
     * {
            "resCode":"000000",
            "resMsg":"成功",
            "data":{
            }
            }
            {
            "resCode":"000001",
            "resMsg":"失败",
            "data":{
            }
        }
     */
    public function uploadHomeworkImages($homeworkId,$imageUrls)
    {
        $imageUrlArray = array();
        foreach ($imageUrls as $v) {
            array_push($imageUrlArray, array("url" => $v));

        }

        $dataArray["images"] = $imageUrlArray;
        $soapResult = $this->_soapClient->uploadHomeworkImages(
            array(
                "homeworkId" => $homeworkId,
                "imageUrls" => json_encode($dataArray),
            )
        );
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.22.6.    查询作业
     * @param $getType
     * @param $classID
     * @param $userID
     * @param $homeworkId
     * @param $name
     * @param $provience
     * @param $city
     * @param $country
     * @param $gradeId
     * @param $subjectId
     * @param $version
     * @param $knowledgeId
     * @param $author
     * @param $homeworkDescibe
     * @param $creator
     * @param $deadlineTime
     * @param $homeworkType
     * @param $mainTitle
     * @param $subTitle
     * @param $scope
     * @param $examTime
     * @param $studentInput
     * @param $attention
     * @param $currPage
     * @param $pageSize
     * @return ServiceJsonResult
     */
    public function queryHomework(
        $getType,
        $classID,
        $userID,
        $currPage,
        $pageSize,
        $homeworkId = "",
        $name = "",
        $provience = "",
        $city = "",
        $country = "",
        $gradeId = "",
        $subjectId = "",
        $version = "",
        $knowledgeId = "",
        $author = "",
        $homeworkDescibe = "",
        $creator = "",
        $deadlineTime = "",
        $homeworkType = "",
        $mainTitle = "",
        $subTitle = "",
        $scope = "",
        $examTime = "",
        $studentInput = "",
        $attention = ""
    )
    {
        $soapResult = $this->_soapClient->queryHomework(
            array(
                "getType" => $getType,
                "classID" => $classID,
                "userID" => $userID,
                "homeworkId" => $homeworkId,
                "name" => $name,
                "prvovience" => $provience,
                "city" => $city,
                "country" => $country,
                "gradeId" => $gradeId,
                "subjectId" => $subjectId,
                "version" => $version,
                "knowledgeId" => $knowledgeId,
                "author" => $author,
                "homeworkDescribe" => $homeworkDescibe,
                "creator" => $creator,
                "deadlineTime" => $deadlineTime,
                "homeworkType" => $homeworkType,
                "mainTitle" => $mainTitle,
                "subTitle" => $subTitle,
                "scope" => $scope,
                "examTime" => $examTime,
                "studentInput" => $studentInput,
                "attention" => $attention,
                "currPage" => $currPage,
                "pageSize" => $pageSize
            ));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        if ($this->mapperJsonResult($json)->resCode == BaseService::successCode) {
            return $this->mapperJsonResult($json)->data;
        }
        return array();
    }

    /** 查找一个条
     * @param $homeworkId
     * @return null
     */
    public function  queryHomeworkById($homeworkId)
    {
        $result = $this->queryHomework(null, null, null, null,null, $homeworkId);

        if (isset($result->list) && isset($result->list[0])) {
            return $result->list[0];
        }
        return null;

    }

    /**
     * 3.22.22.    查询作业列表（通过学生id）
     * @param $studentID
     * @param $classID
     * @param $subjectId
     * @param $currPage
     * @param $pageSize
     * @return mixed
     */
    public function queryHomeworkListByStudent($studentID,$classID, $subjectId,$currPage = "", $pageSize = "")
    {
        $soapResult = $this->_soapClient->queryHomeworkListByStudent(
            array(
                "studentID" => $studentID,
                "classID" => $classID,
                "subjectId" => $subjectId,
                "currPage" => $currPage,
                "pageSize" => $pageSize
            )
        );
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        if ($this->mapperJsonResult($json)->resCode == BaseService::successCode) {
            return $this->mapperJsonResult($json)->data;
        }
    }

    /**
     * 3.22.18.    查询作业详情通过测验id
     * @param $homeworkId
     * @return ServiceJsonResult
     */
    public function queryHomeworkInfoByID($homeworkId)
    {
        $soapResult = $this->_soapClient->queryHomeworkInfoByID(
            array("homeworkId" => $homeworkId)
        );
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        if ($this->mapperJsonResult($json)->resCode == pos_HomeWorkManageService::successCode) {
            return $this->mapperJsonResult($json)->data;
        } else {
            return array();
        };
    }

    /**
     * 3.22.23.    上传作业答案(用于题库组卷试卷作答)
     * @param $studentID
     * @param $homeworkId
     * @param $answerList
     */
    public function uploadHomeworkAnswerQuestion($studentID, $relId, $answerList)
    {
        $soapResult = $this->_soapClient->uploadHomeworkAnswerQuestion(
            array(
                "relId" => $relId,
                "studentID" => $studentID,
                "answerList" => $answerList
            )
        );
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.22.19.    上传测验答案图片(用于图片类型试卷作答)修改答案也使用此接口
     * @param $studentID
     * @param $homeworkId
     * @param $imageList
     * @return ServiceJsonResult
     */
    public function uploadHomeworkAnswerImageUrl($studentID, $relId, $imageList)
    {
        $soapResult = $this->_soapClient->uploadHomeworkAnswerImageUrl(
            array(
                "relId" => $relId,
                "studentID" => $studentID,
                "imageList" => $imageList
            )
        );
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.22.21.    查询作业学生的答案(图片类型作业)
     * @param $studentID
     * @param $relId
     * @param $homeworkAnswerID
     * @return ServiceJsonResult
     */
    public function queryHomeworkAnswerImages($studentID,$relId, $homeworkAnswerID = "")
    {
        $soapResult = $this->_soapClient->queryHomeworkAnswerImages(
            array(
                "relId" => $relId,
                "studentID" => $studentID,
                "homeworkAnswerID" => $homeworkAnswerID
            )
        );
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        if ($this->mapperJsonResult($json)->resCode == BaseService::successCode) {
            return $this->mapperJsonResult($json)->data;
        } else {
            return array();
        }
    }

    /**
     * 3.22.14.    查询作业答案列表及教师批改(旧接口 下面是新接口)
     * @param $homeworkId
     * @param $currPage
     * @param $type	0:未批改，1已批改 ，2未提交
     * @param $classID 班级id
     * @param $pageSize
     * @return array
     */
    public function queryHomeworkAllAnswerList($homeworkId,$type,$classID, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->queryHomeworkAllAnswerList(
            array(
                "homeworkId" => $homeworkId,
                "type"=>$type,
                'classID'=>$classID,
                "currPage" => $currPage,
                "pageSize" => $pageSize
            )
        );
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        if ($this->mapperJsonResult($json)->resCode == BaseService::successCode) {
            return $this->mapperJsonResult($json)->data;
        } else {
            return array();
        }
    }

	/**
	 * 1.1.1.	查询作业答案列表及教师批改（14到23为新增接口）（11.15修改）
	 * @param $relId	作业分配关联ID
	 * @param $currPage
	 * @param $type	0:未批改，1已批改 ，2未提交
	 * @param $pageSize
	 * @return array
	 */

	public function newQueryHomeworkAllAnswerList($relId, $type, $currPage, $pageSize)
	{
		$soapResult = $this->_soapClient->queryHomeworkAllAnswerList(
			array(
				"relId" => $relId,
				"type"=>$type,
				"currPage" => $currPage,
				"pageSize" => $pageSize
			)
		);
		$jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
		$json = json_decode($jsonStr);
		if ($this->mapperJsonResult($json)->resCode == BaseService::successCode) {
			return $this->mapperJsonResult($json)->data;
		} else {
			return array();
		}
	}

    /**
     * 3.22.15.    批阅试卷
     * @param $teacherID
     * @param $homeworkAnswerID
     * @param $tID
     * @param $checkInfoJson
     * @return ServiceJsonResult
     */
    public function  commitCheckInfo($teacherID, $homeworkAnswerID, $tID, $checkInfoJson)
    {
        $soapResult = $this->_soapClient->commitCheckInfo(
            array(
                "teacherID" => $teacherID,
                "homeworkAnswerID" => $homeworkAnswerID,
                "tID" => $tID,
                "checkInfoJson" => $checkInfoJson
            )
        );
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.22.16.    学生答案批改状态修改
     * @param $homeworkAnswerID
     * @param $teacherID
     * @param $isCheck
     * @param $summary
     * @param $testScore
     * @return ServiceJsonResult
     */
    public function updateCheckState($homeworkAnswerID, $teacherID, $isCheck = 1, $summary = "", $homeworkScore = "")
    {
        $soapResult = $this->_soapClient->updateCheckState(
            array(
                "teacherID" => $teacherID,
                "homeworkAnswerID" => $homeworkAnswerID,
                "isCheck" => $isCheck,
                "summary" => $summary,
                "homeworkScore" => $homeworkScore
            )
        );
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.22.25.    查询今日发布的作业
     * @param $classID
     * @param $currPage
     * @param $pageSize
     * @return array
     */
    public function queryTodayHomework($classID, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->queryTodayHomework(
            array(
                "classID" => $classID,
                "currPage" => $currPage,
                "pageSize" => $pageSize
            )
        );
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        if ($this->mapperJsonResult($json)->resCode == BaseService::successCode) {
            return $this->mapperJsonResult($json)->data;
        } else {
            return array();
        }
    }

    /**
     *3.21.3.组织试卷
     * creator    创建人
     * token    用于安全控制，暂时为空
     * 成功    {
     * "resCode":"000000",
     * "resMsg":"成功",
     * "data":{
     * "paperId":"",//试卷id
     * "uploadTime":"",//上传时间
     * "subjectId":"",//科目id
     * "subjectname":"",//科目名称
     * "provience":"",//省
     * "city":"",//市
     * "country":"",//区县
     * "gradeId":"",//年级id
     * "gradename":"",//年级名称
     * "version":"",//版本
     * "versionname":"",//版本名称
     * "knowledgeId":"",//知识点
     * "name":"",//试卷名称
     * "getType":"",//试卷组织类型（0上传，1组卷）
     * "author":"",//作者（数据字典 0本校 1教师）
     * "paperDescribe":"",//试卷简介
     * "paperType":"",//试卷类型（数据字典 1标准 2小测验 3作业 4自定义）,
     * "status":"",//试卷状态(0临时，1正式)
     * pageMain:{
     * "main_title": {
     * "ischecked": 1,
     * "title": "2013-2014学年度xx学校xx月考卷"
     * },
     * "sub_title": {
     * "ischecked": 1,
     * "title": "试卷副标题"
     * },
     * "line": {
     * "ischecked": 1
     * },
     * "secret_sign": {
     * "ischecked": 1,
     * "title": "绝密★启用前"
     * },
     * "info": {
     * "ischecked": 1,
     * "title": "考试范围：xxx；考试时间：100分钟；命题人：xxx",
     * },
     * "student_input": {
     * "ischecked": 1,
     * "title": "学校：___________姓名：___________班级：___________考号：___________"
     * },
     * "performance": {
     * "ischecked": 1
     * },
     * "pay_attention": {
     * "ischecked": 1,
     * "content": "1. 答题前填写好自己的姓名、班级、考号等信息<br />2. 请将答案正确填写在答题卡上"
     * },
     * "win_paper_typeone": {
     * "id": ,
     * "type": 1,
     * "ischecked": 0,
     * "title": "分卷I",
     * "content": "分卷I 注释",
     * "types":[
     * {
     * "id": 1,
     * "ischecked": 1,
     * "ischeckedscore": 1,
     * "title": "单选题",
     * "content": "注释"
     * }
     * ]
     * },
     * "win_paper_typetwo": {
     * "id": ,
     * "type": 2,
     * "ischecked": 1,
     * "title": "分卷II",
     * "content": "分卷II 注释",
     * "types":[
     * {
     * "id": 1,
     * "ischecked": 1,
     * "ischeckedscore": 1,
     * "title": "单选题",
     * "content": "注释"
     * }
     * ]
     * }
     * }
     * }
     * }
     * @param $userId
     * @return array
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function createHomeworkHeader($userId, $classID)
    {
        $soapResult = $this->_soapClient->createHomeworkHeader(
            array(
                'creator' => $userId,
                'classID' => $classID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 3.21.5.修改组织试卷头部
     * paperId    试卷id（不为空）
     * name    试卷名称
     * provience    省
     * city    市
     * country    区
     * gradeId    年级Id
     * subjectId    科目Id
     * version    版本
     * knowledgeId    知识点
     * author    作者（数据字典 0本校 1教师）
     * paperDescribe    试卷简介
     * creator    创建人
     * paperType    试卷类型（数据字典 1标准 2小测验 3作业 4自定义）
     * pageMain    pageMain:{
     * "main_title": {
     * "ischecked": 1,
     * "title": "2013-2014学年度xx学校xx月考卷"
     * },
     * "sub_title": {
     * "ischecked": 1,
     * "title": "试卷副标题"
     * },
     * "line": {
     * "ischecked": 1
     * },
     * "secret_sign": {
     * "ischecked": 1,
     * "title": "绝密★启用前"
     * },
     * "info": {
     * "ischecked": 1,
     * "title": "考试范围：xxx；考试时间：100分钟；命题人：xxx",
     * },
     * "student_input": {
     * "ischecked": 1,
     * "title": "学校：___________姓名：___________班级：___________考号：___________"
     * },
     * "performance": {
     * "ischecked": 1
     * },
     * "pay_attention": {
     * "ischecked": 1,
     * "content": "1. 答题前填写好自己的姓名、班级、考号等信息<br />2. 请将答案正确填写在答题卡上"
     * },
     * "win_paper_typeone": {
     * "id": ,
     * "type": 1,
     * "ischecked": 0,
     * "title": "分卷I",
     * "content": "分卷I 注释",
     * "types":[
     * {
     * "id": 1,
     * "ischecked": 1,
     * "ischeckedscore": 1,
     * "title": "单选题",
     * "content": "注释"
     * }
     * ]
     * },
     * "win_paper_typetwo": {
     * "id": ,
     * "type": 2,
     * "ischecked": 1,
     * "title": "分卷II",
     * "content": "分卷II 注释",
     * "types":[
     * {
     * "id": 1,
     * "ischecked": 1,
     * "ischeckedscore": 1,
     * "title": "单选题",
     * "content": "注释"
     * }
     * ]
     * }
     * }
     *
     *
     * @param $paperId
     * @param $name
     * @param $provience
     * @param $city
     * @param $country
     * @param $gradeId
     * @param $subjectId
     * @param $version
     * @param $knowledgeId
     * @param $author
     * @param $paperDescribe
     * @param $creator
     * @param $paperType
     * @param $pageMainJsonObj
     * @return ServiceJsonResult
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  updateHomeworkHead($homeworkId, $classID, $name, $provience, $city, $country,
                                        $gradeId, $subjectId, $version, $knowledgeId,
                                        $author, $homeworkDescribe,
                                        $creator, $homeworkType, $deadLineTime
    )
    {
        $param = ['homeworkId' => $homeworkId,
            'classID' => $classID,
            'name' => $name,
            'provience' => $provience,
            'city' => $city,
            'country' => $country,
            'gradeId' => $gradeId,
            'subjectId' => $subjectId,
            'version' => $version,
            'knowledgeId' => $knowledgeId,
            'author' => $author,
            'homeworkDescribe' => $homeworkDescribe,
            'creator' => $creator,
            'homeworkType' => $homeworkType,
            'deadlineTime' => $deadLineTime
        ];

        $soapResult = $this->_soapClient->updateHomeworkHead($param);
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;

    }

    /**paperId    试卷id（不为空）
     * name    试卷名称
     * provience    省
     * city    市
     * country    区
     * gradeId    年级Id
     * subjectId    科目Id
     * version    版本
     * knowledgeId    知识点
     * author    作者（数据字典 0本校 1教师）
     * paperDescribe    试卷简介
     * creator    创建人
     * paperType    试卷类型（数据字典 1标准 2小测验 3作业 4自定义）
     * pageMain    pageMain:{
     * "main_title": {
     * "ischecked": 1,
     * "title": "2013-2014学年度xx学校xx月考卷"
     * },
     * "sub_title": {
     * "ischecked": 1,
     * "title": "试卷副标题"
     * },
     * "line": {
     * "ischecked": 1
     * },
     * "secret_sign": {
     * "ischecked": 1,
     * "title": "绝密★启用前"
     * },
     * "info": {
     * "ischecked": 1,
     * "title": "考试范围：xxx；考试时间：100分钟；命题人：xxx",
     * },
     * "student_input": {
     * "ischecked": 1,
     * "title": "学校：___________姓名：___________班级：___________考号：___________"
     * },
     * "performance": {
     * "ischecked": 1
     * },
     * "pay_attention": {
     * "ischecked": 1,
     * "content": "1. 答题前填写好自己的姓名、班级、考号等信息<br />2. 请将答案正确填写在答题卡上"
     * },
     * "win_paper_typeone": {
     * "id": ,
     * "type": 1,
     * "ischecked": 0,
     * "title": "分卷I",
     * "content": "分卷I 注释",
     * "types":[
     * {
     * "id": 1,
     * "ischecked": 1,
     * "ischeckedscore": 1,
     * "title": "单选题",
     * "content": "注释"
     * }
     * ]
     * },
     * "win_paper_typetwo": {
     * "id": ,
     * "type": 2,
     * "ischecked": 1,
     * "title": "分卷II",
     * "content": "分卷II 注释",
     * "types":[
     * {
     * "id": 1,
     * "ischecked": 1,
     * "ischeckedscore": 1,
     * "title": "单选题",
     * "content": "注释"
     * }
     * ]
     * }
     * }
     *
     *
     * @param  $paperId
     * @param $pageMainJsonObj
     * @return ServiceJsonResult
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  updateHomeworkContent($homeworkId, $pageMainJsonObj)
    {
        $param = ['homeworkId' => $homeworkId,
            'pageMain' => json_encode($pageMainJsonObj),
        ];

        $soapResult = $this->_soapClient->updateHomeworkContent($param);
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;

    }

    /**
     * 3.21.3.查询临时试卷
     * 查询临时组卷试卷
     * {
     * "resCode":"000000",
     * "resMsg":"查询成功",
     * "data":{
     * "paperId":"",//试卷id
     * "uploadTime":"",//上传时间
     * "subjectId":"",//科目id
     * "subjectname":"",//科目名称
     * "provience":"",//省
     * "city":"",//市
     * "country":"",//区县
     * "gradeId":"",//年级id
     * "gradename":"",//年级名称
     * "version":"",//版本
     * "versionname":"",//版本名称
     * "knowledgeId":"",//知识点
     * "name":"",//试卷名称
     * "getType":"",//试卷组织类型（0上传，1组卷）
     * "author":"",//作者（数据字典 0本校 1教师）
     * "paperDescribe":"",//试卷简介
     * "paperType":"",//试卷类型（数据字典 1标准 2小测验 3作业 4自定义）,
     * "status":"",//试卷状态(0临时，1正式)
     * "imageUrls":[{"id":"","url":""}],
     * //试卷图片
     *
     * pageMain:{
     * "main_title": {
     * "ischecked": true,
     * "title": "2013-2014学年度xx学校xx月考卷"
     * },
     * "sub_title": {
     * "ischecked": true,
     * "title": "试卷副标题"
     * },
     * "line": {
     * "ischecked": 1
     * },
     * "secret_sign": {
     * "ischecked": true,
     * "title": "绝密★启用前"
     * },
     * "info": {
     * "ischecked": true,
     * "title": "考试范围：xxx；考试时间：100分钟；命题人：xxx",
     * },
     * "student_input": {
     * "ischecked": true,
     * "title": "学校：___________姓名：___________班级：___________考号：___________"
     * },
     * "performance": {
     * "ischecked": 1
     * },
     * "pay_attention": {
     * "ischecked": true,
     * "content": "1. 答题前填写好自己的姓名、班级、考号等信息<br />2. 请将答案正确填写在答题卡上"
     * },
     * "win_paper_typeone": {
     * "id": ,
     * "type": 1,
     * "ischecked": 0,
     * "title": "分卷I",
     * "content": "分卷I 注释",
     * "types":[
     * {
     * "id": 1,
     * "ischecked": true,
     * "ischeckedscore": 1,
     * "title": "单选题",
     * "content": "注释"
     * }
     * ]
     * },
     * "win_paper_typetwo": {
     * "id": ,
     * "type": 2,
     * "ischecked": true,
     * "title": "分卷II",
     * "content": "分卷II 注释",
     * "types":[
     * {
     * "id": 1,
     * "ischecked": true,
     * "ischeckedscore": 1,
     * "title": "单选题",
     * "content": "注释"
     * }
     *
     * }
     *
     * },
     * ...
     * ]
     * }
     * }
     * @param $paperId
     * @return ServiceJsonResult
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  queryTempHomework($homeworkId)
    {
        $param = ['homeworkId' => $homeworkId];

        $soapResult = $this->_soapClient->queryTempHomework($param);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }

        return null;
    }

    /**
     * 3.21.9.试卷题目打分
     * @param $paperId 试卷ID
     * @param $creator 录入人
     * @param $scoreJson 题目分数 json [{"id":"","score":""}]
     * @return ServiceJsonResult
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  scoreAllQuestion($homeworkId, $creator, $scoreJson)
    {
        $param = ['homeworkId' => $homeworkId,
            'creator' => $creator,
            'scoreJson' => $scoreJson];

        $soapResult = $this->_soapClient->scoreAllQuestion($param);
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;


    }

    /**  3.21.8.更新试卷全部题目
     * @param $paperId
     * @param $creator
     * @param $questionsJson
     * @return ServiceJsonResult
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  updateQuestionAll($homeworkId, $creator, $questionsJson)
    {
        $param = ['homeworkId' => $homeworkId,
            'creator' => $creator,
            'questionsJson' => $questionsJson];

        $soapResult = $this->_soapClient->updateQuestionAll($param);
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;


    }

    /**
     *  请求试卷 类型
     * @param $paperId 试卷ID
     * @param $creator 创建人
     * @return  array
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  queryQuestions($homeworkId, $creator)
    {
        $param = ['homeworkId' => $homeworkId,
            'creator' => $creator,
        ];

        $soapResult = $this->_soapClient->queryQuestions($param);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data->list;
        }

        return array();

    }

    /**
     *3.22.26.    查询作业详情通过测验id
     * @param $homeworkId
     * @param $userID
     * @param string $pagesize
     * @param $currPage
     * @return array
     */
    public function queryHomeworkInfoByIDOrgType($relId, $userID = "", $pageSize = "", $currPage = "")
    {
        $param = ['relId' => $relId,
            'userID' => $userID,
            'pageSize' => $pageSize,
            'currPage' => $currPage
        ];

        $soapResult = $this->_soapClient->queryHomeworkInfoByIDOrgType($param);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.22.28.用于批改的答案查询组卷类型的作业
     * @param $homeworkAnswerID
     * @return array
     */
    public function queryHomeworkAllAnswerPicList($homeworkAnswerID)
    {
        $array = array(
            "homeworkAnswerID" => $homeworkAnswerID,
        );
        $soapResult = $this->_soapClient->queryHomeworkAllAnswerPicList($array);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.22.27.    批阅试卷（组卷类型使用）
     * @param $teacherID
     * @param $homeworkAnswerID
     * @param $picID
     * @param $answerID
     * @return ServiceJsonResult
     */
    public function commitCheckInfoForOrgPaper($teacherID, $homeworkAnswerID, $picID, $answerID, $checkJson)
    {
        $array = array(
            "teacherID" => $teacherID,
            "homeworkAnswerID" => $homeworkAnswerID,
            "picId" => $picID,
            "answerId" => $answerID,
            "checkJson" => $checkJson
        );
        $soapResult = $this->_soapClient->commitCheckInfoForOrgPaper($array);
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 3.22.29.    查询一个大题下所图片和批改
     * @param $homeworkAnswerID
     * @param $questionID
     * @return array
     */
    public function queryHomeworkAnswerPicListByQue($homeworkAnswerID, $questionID)
    {
        $array = array(
            "homeworkAnswerID" => $homeworkAnswerID,
            "questionId" => $questionID
        );
        $soapResult = $this->_soapClient->queryHomeworkAnswerPicListByQue($array);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.22.29.    置相互判卷
     * @param $teacherID
     * @param $homeworkId
     * @return ServiceJsonResult
     */
    public function studentCrossCheckTest($teacherID, $homeworkId)
    {
        $array = array(
            "teacherID" => $teacherID,
            "homeworkId" => $homeworkId
        );
        $soapResult = $this->_soapClient->studentCrossCheckTest($array);
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 3.23.2.更新组织作业全部题目（20150418）
     *接口地址	http://主机地址:端口号/ /schoolService/ homeworkManage?wsdl
     * 方法名	updateQuestionAllNew
     * @param $homeworkId 作业id
     * @param $questions
     * @return ServiceJsonResult
     */
    public function updateQuestionAllNew($homeworkId,$questions){
        $array = array(
            "homeworkId" => $homeworkId,
            "questions" => $questions
        );
        $soapResult = $this->_soapClient->updateQuestionAllNew($array);
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 3.23.1.布置作业（20150418）
     * 接口地址	http://主机地址:端口号/ /schoolService/ homeworkManage?wsdl
     * 方法名	organizeHomework
     * @param $classID      班级id
     * @param $name         作业名称
     * @param $provience    省
     * @param $city         市
     * @param $country      区
     * @param $gradeId      年级Id
     * @param $subjectId    科目Id
     * @param $version      版本
     * @param $knowledgeId  知识点
     * @param $homeworkDescribe 作业简介
     * @param $creator          创建人
     * @param $deadlineTime     交作业截至时间
     * {
    "resCode":"000000",
    "resMsg":"成功",
    "data":{
    "homeworkId":""
    }
    }
     * @return ServiceJsonResult
     */
    public function organizeHomework($classID,$name,$provience,$city,$country,$gradeId,$subjectId,$version,$knowledgeId,$homeworkDescribe,$creator,$deadlineTime){
        $array = array(
            "classID" => $classID,
            "name" => $name,
            "provience"=>$provience,
            "city"=>$city,
            "country"=>$country,
            "gradeId"=>$gradeId,
            "subjectId"=>$subjectId,
            "version"=>$version,
            "knowledgeId"=>$knowledgeId,
            "homeworkDescribe"=>$homeworkDescribe,
            "creator"=>$creator,
            "deadlineTime"=>$deadlineTime,
        );
        $soapResult = $this->_soapClient->organizeHomework($array);
        $result = $this->soapResultToJsonResult($soapResult);

        return $result;
    }

    /**
     * 3.23.37.查询作业类型
     * 接口地址	http://主机地址:端口号/ /schoolService/ paperManage?wsdl
     * 方法名	queryHomTypeById
     * @param $relId           作业id
     * @param $homeworkAnswerID     作业答案id
     * {
    "data": {
    "homeworkId": 101186,//作业id
    "getType": "0",//作业组织类型（0上传，1组卷）
    "name": "4月18的单元作业"//作业名称
    },
    "resCode": "000000",
    "resMsg": "查询成功"
    }
     * @return ServiceJsonResult
     */
    public function queryHomTypeById($relId,$homeworkAnswerID){
        $param = [
	        'relId' => $relId,
            'homeworkAnswerID' => $homeworkAnswerID
        ];

        $soapResult = $this->_soapClient->queryHomTypeById($param);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return null;

    }

    /**
     * 3.23.30.	查询作业学生的答案(组卷类型作业)
     * @param $studentID
     * @param $homeworkId
     * @param $homeworkAnswerID
     * @return array
     */
    public function queryHomeworkAnswerQuestion($studentID, $relId,$homeworkAnswerID)
    {
        $array = array(
           "studentID"=>$studentID,
            "relId"=>$relId,
            "homeworkAnswerID"=>$homeworkAnswerID
        );
        $soapResult = $this->_soapClient->queryHomeworkAnswerQuestion($array);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.23.39.	统计班级未完成作业的学生
     * @param $studentID
     * @param $homeworkId
     * @param $homeworkAnswerID
     * @return array
     * {
    "data": {
    "listSize": 7,
    "list": [
    {
    "stuID": "2323",//学号
    "userID": "101457",//用户id
    "userName": "小愣子",//姓名
    "10010,语文": 35,
    "10011,数学": 1,
    "sumCnt": 36//未完成总数
    },
    {
    "stuID": "",
    "userID": "101399",
    "userName": null,
    "10010,语文": 35,
    "10011,数学": 1,
    "sumCnt": 36
    },
    {
    "stuID": "",
    "userID": "101485",
    "userName": "学生k",
    "10010,语文": 35,
    "10011,数学": 1,
    "sumCnt": 36
    },
    {
    "stuID": "0001",
    "userID": "101482",
    "userName": "yang_student",
    "10010,语文": 33,
    "10011,数学": 1,
    "sumCnt": 34
    },
    {
    "stuID": "",
    "userID": "101502",
    "userName": "啦啦啦",
    "10010,语文": 33,
    "10011,数学": 1,
    "sumCnt": 34
    },
    {
    "stuID": "",
    "userID": "101463",
    "userName": "小张同学",
    "10010,语文": 30,
    "10011,数学": 1,
    "sumCnt": 31
    },
    {
    "stuID": "123",
    "userID": "101493",
    "userName": "王小愣",
    "10010,语文": 27,
    "10011,数学": 1,
    "sumCnt": 28
    }
    ]
    },
    "resCode": "000000",
    "resMsg": "查询成功"
    }

     */
    public function stasClsHwkNotdone($classID  , $orderType="",$orderBySubject="",$ascDesc="",$beginTime="",$endTime="")
    {
        $array = array(
            "classID"=>$classID,
            "orderType"=>$orderType,
            "orderBySubject"=>$orderBySubject,
            "ascDesc"=>$ascDesc,
            "beginDate"=>$beginTime,
            "endDate"=>$endTime
        );
        $soapResult = $this->_soapClient->stasClsHwkNotdone($array);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     *  3.23.38.教师真在批改作业答案
     * 接口地址	http://主机地址:端口号/ schoolService / homeworkManage?wsdl
     * 方法名	teacherChecking
     * @param $teacherID            教师ID
     * @param $homeworkAnswerID     作业答案id
     * @return ServiceJsonResult
     * {
    "data": {
    },
    "resCode": "000000",
    "resMsg": "成功"
    }
     */
    public function teacherChecking($teacherID,$homeworkAnswerID){
        $array =['teacherID'=>$teacherID,'homeworkAnswerID'=>$homeworkAnswerID];
        $soapResult = $this->_soapClient->teacherChecking($array);
        $result = $this->soapResultToJsonResult($soapResult);

            return $result;

    }

    /**
     * 3.23.40.	作业手动告家长
     * @param $students
     * @param $teacherID
     * @return ServiceJsonResult
     */
    public function sendHwkNotdoneMsg($students,$teacherID){
        $array =['teacherID'=>$teacherID,'students'=>$students];
        $soapResult = $this->_soapClient->sendHwkNotdoneMsg($array);
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 自动判断客观题
     * @param $homeworkAnswerID
     * @return \frontend\services\ServiceJsonResult
     */
    public function autoHomeworkCorrectResult($homeworkAnswerID){
        $array =['homeworkAnswerID'=>$homeworkAnswerID];
        $soapResult = $this->_soapClient->autoHomeworkCorrectResult($array);
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

}