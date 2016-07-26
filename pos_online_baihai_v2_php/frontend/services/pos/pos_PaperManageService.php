<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * 试卷管理
 * Created by PhpStorm.
 * User: yangjie
 */
class pos_PaperManageService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/paperManage?wsdl");
    }


    /**
     *  绢识试卷
     * 方法名    createTest
     * 请求参数    name    试卷名称
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
     * mainTitle    主标题
     * subTitle    副标题
     * scope    考试范围
     * examTime    考试时间
     * studentInput    考生输入
     * attention    注意事项
     * paperSection    分卷题目{"sections":[{"sectionName":"第一卷","sectionNote":"一卷","questionTypes":[{"typeName":"选择题","typeNote":"选择题","questions":[{"questionId":"110"}]}]}]}
     *
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
     * @param $mainTitle
     * @param $subTitle
     * @param $scope
     * @param $examTime
     * @param $studentInput
     * @param $attention
     * @param $paperSection
     * @return array
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function createPaper($name,
                                $provience,
                                $city,
                                $country,
                                $gradeId,
                                $subjectId,
                                $version,
                                $knowledgeId,
                                $author,
                                $paperDescribe,
                                $creator,
                                $paperType,
                                $mainTitle,
                                $subTitle,
                                $scope,
                                $examTime,
                                $studentInput,
                                $attention,
                                $paperSection)
    {
        $soapResult = $this->_soapClient->createTest(
            array(
                'name' => $name,
                'provience' => $provience,
                'city' => $city,
                'country' => $country,
                'gradeId' => $gradeId,
                'subjectId' => $subjectId,
                'version' => $version,
                'knowledgeId' => $knowledgeId,
                'author' => $author,
                'paperDescribe' => $paperDescribe,
                'creator' => $creator,
                'paperType' => $paperType,
                'mainTitle' => $mainTitle,
                'subTitle' => $subTitle,
                'scope' => $scope,
                'examTime' => $examTime,
                'studentInput' => $studentInput,
                'attention' => $attention,
                'paperSection' => $paperSection
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 上传试卷
     * @param $name
     * @param $provience
     * @param $city
     * @param $country
     * @param $gradeId
     * @param $subjectId
     * @param $version
     * @param $knowledgeId
     * @param $paperDescribe
     * @param $creator
     * @param $gutter
     * @param $secret
     * @param $imageUrls
     * @return array
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function uploadPaper($name,
                                $provience,
                                $city,
                                $country,
                                $gradeId,
                                $subjectId,
                                $version,
                                $knowledgeId,
                                $paperDescribe,
                                $creator,
                                $imageUrls,
                                $gutter = 0,
                                $secret = 0
    )
    {
        $soapResult = $this->_soapClient->uploadPaper(
            array(
                'name' => $name,
                'provience' => $provience,
                'city' => $city,
                'country' => $country,
                'gradeId' => $gradeId,
                'subjectId' => $subjectId,
                'version' => $version,
                'knowledgeId' => $knowledgeId,
                'paperDescribe' => $paperDescribe,
                'creator' => $creator,
                'imageUrls' => $imageUrls,
                'gutter' => $gutter,
                'secret' => $secret

            ));
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 3.22.2.    修改上传试卷
     * @param $paperId
     * @param $name
     * @param $provience
     * @param $city
     * @param $country
     * @param $gradeId
     * @param $subjectId
     * @param $version
     * @param $knowledgeId
     * @param $paperDescribe
     * @param $creator
     * @param $imageUrls
     * @return ServiceJsonResult
     */
    public function updateUploadPaper(
        $paperId,
        $name,
        $provience,
        $city,
        $country,
        $gradeId,
        $subjectId,
        $version,
        $knowledgeId,
        $paperDescribe,
        $creator,
        $imageUrls

    )
    {
        $soapResult = $this->_soapClient->updateUploadPaper(
            array(
                'paperId' => $paperId,
                'name' => $name,
                'provience' => $provience,
                'city' => $city,
                'country' => $country,
                'gradeId' => $gradeId,
                'subjectId' => $subjectId,
                'version' => $version,
                'knowledgeId' => $knowledgeId,
                'paperDescribe' => $paperDescribe,
                'creator' => $creator,
                'imageUrls' => $imageUrls
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 查询试卷
     * @param string $creator
     * @param string $getType
     * @param string $paperId
     * @param string $name
     * @param string $provience
     * @param string $city
     * @param string $country
     * @param string $gradeId
     * @param string $subjectId
     * @param string $version
     * @param string $knowledgeId
     * @param string $author
     * @param string $paperDescribe
     * @param string $paperType
     * @param string $mainTitle
     * @param string $subTitle
     * @param string $scope
     * @param string $examTime
     * @param string $studentInput
     * @param string $attention
     * @param string $orderType 排序   1发布时间倒序    2发布时间升序
     * @return array
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function queryPaper($creator = '', $currPage = 1, $pageSize = 10, $getType = '', $paperId = '', $name = '', $provience = '', $city = '', $country = '', $gradeId = '', $subjectId = '', $version = '', $knowledgeId = '', $author = '', $paperDescribe = '', $paperType = '', $mainTitle = '', $subTitle = '', $scope = '', $examTime = '', $studentInput = '', $attention = '', $orderType = '')
    {
        $soapResult = $this->_soapClient->queryPaper(array("getType" => $getType, "creator" => $creator, 'paperId' => $paperId, 'name' => $name, 'provience' => $provience, 'city' => $city, 'country' => $country, 'gradeId' => $gradeId, 'subjectId' => $subjectId, 'version' => $version, 'knowledgeId' => $knowledgeId, 'author' => $author, 'paperDescribe' => $paperDescribe, 'paperType' => $paperType, 'mainTitle' => $mainTitle, 'subTitle' => $subTitle, 'scope' => $scope, 'examTime' => $examTime, 'studentInput' => $studentInput, 'attention' => $attention, 'currPage' => $currPage, 'pageSize' => $pageSize, 'orderType' => $orderType));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();

    }

    /**
     * 获取一条组织试卷
     * @param $paperId
     * @return null
     */
    public function queryMakerPaperById($paperId)
    {
        $result = $this->queryPaper('', 1, 500, '', $paperId);

        if (empty($result)) {

            return null;
        }

        if (isset($result->list) && isset($result->list[0])) {
            return $result->list[0];
        }
        return null;


    }

    /**
     * 新试卷搜索映射 queryPaper 接口
     * @param $creator
     * @param $currPage
     * @param $pageSize
     * @param $gradeId
     * @param $subjectId
     * @param $version
     * @return array
     */
    public function searchPapeer($creator, $currPage, $pageSize, $getType, $gradeId, $subjectId, $version, $orderType)
    {
        return $this->queryPaper($creator, $currPage, $pageSize, $getType, $paperId = '', $name = '', $provience = '', $city = '', $country = '', $gradeId, $subjectId, $version, $knowledgeId = '', $author = '', $paperDescribe = '', $paperType = '', $mainTitle = '', $subTitle = '', $scope = '', $examTime = '', $studentInput = '', $attention = '', $orderType);
    }

    /**
     *  删除试卷
     * @param $paperId
     * @return ServiceJsonResult
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function deletePaper($paperId)
    {
        $soapResult = $this->_soapClient->deletePaper(array('paperId' => $paperId));
        $result = $this->soapResultToJsonResult($soapResult);

        return $result;
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
    public function createPaperHeader($userId)
    {
        $soapResult = $this->_soapClient->createPaperHeader(
            array(
                'creator' => $userId
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
    public function  updatePaperHead($paperId, $name, $provience, $city, $country,
                                     $gradeId, $subjectId, $version, $knowledgeId,
                                     $author, $paperDescribe,
                                     $creator, $paperType
    )
    {
        $param = ['paperId' => $paperId,
            'name' => $name,
            'provience' => $provience,
            'city' => $city,
            'country' => $country,
            'gradeId' => $gradeId,
            'subjectId' => $subjectId,
            'version' => $version,
            'knowledgeId' => $knowledgeId,
            'author' => $author,
            'paperDescribe' => $paperDescribe,
            'creator' => $creator,
            'paperType' => $paperType,
        ];

        $soapResult = $this->_soapClient->updatePaperHead($param);
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
    public function  updatePaperContent($paperId, $pageMainJsonObj)
    {
        $param = ['paperId' => $paperId,
            'pageMain' => json_encode($pageMainJsonObj),
        ];

        $soapResult = $this->_soapClient->updatePaperContent($param);
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
    public function  queryTempPaper($paperId)
    {
        $param = ['paperId' => $paperId];

        $soapResult = $this->_soapClient->queryTempPaper($param);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }

        return null;
    }

    /**
     *  请求试卷 类型
     * @param $paperId 试卷ID
     * @param $creator 创建人
     * @return  array
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  queryQuestions($paperId, $creator)
    {
        $param = ['paperId' => $paperId,
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
     * 3.21.9.试卷题目打分
     * @param $paperId 试卷ID
     * @param $creator 录入人
     * @param $scoreJson 题目分数 json [{"id":"","score":""}]
     * @return ServiceJsonResult
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  scoreAllQuestion($paperId, $creator, $scoreJson)
    {
        $param = ['paperId' => $paperId,
            'creator' => $creator,
            'scoreJson' => $scoreJson];

        $soapResult = $this->_soapClient->scoreAllQuestion($param);
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;


    }

    /**
     * 3.22.13.    试卷题目打分，不修改状态
     * @param $paperId
     * @param $creator
     * @param $scoreJson
     * @return ServiceJsonResult
     */
    public function  scoreAllQuestionNosta($paperId, $creator, $scoreJson)
    {
        $param = ['paperId' => $paperId,
            'creator' => $creator,
            'scoreJson' => $scoreJson];

        $soapResult = $this->_soapClient->scoreAllQuestionNosta($param);
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
    public function  updateQuestionAll($paperId, $creator, $questionsJson)
    {
        $param = ['paperId' => $paperId,
            'creator' => $creator,
            'questionsJson' => $questionsJson];

        $soapResult = $this->_soapClient->updateQuestionAll($param);
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;


    }

    /**
     * 3.22.18.    查询试卷是否使用
     * @param $paperId
     * @return ServiceJsonResult
     * {
     * "data": {
     * "used": 0
     * //0否， 1是
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * }
     */
    public function  queryPaperUsedByOtherExamSub($paperId, $examSubID)
    {
        $param = ['paperId' => $paperId,
            'examSubID' => $examSubID
        ];

        $soapResult = $this->_soapClient->queryPaperUsedByOtherExamSub($param);
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 3.22.2.    查询试卷详情
     * {
     * "data": {
     * "id": 101186,//试卷id
     * "getType": "0",//试卷组织类型（0上传，1组卷）
     * "name": "4月18的单元测验数学试卷"//试卷名称
     * },
     * "resCode": "000000",
     * "resMsg": "查询成功"
     * }
     * @param $paperId
     * @param $testAnswerID
     * @param $examSubID
     * @return array
     */
    public function queryPaperById($paperId, $testAnswerID, $examSubID)
    {
        $soapResult = $this->_soapClient->queryPaperById(
            array(
                "paperId" => $paperId,
                "testAnswerID" => $testAnswerID,
                "examSubID" => $examSubID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }


}