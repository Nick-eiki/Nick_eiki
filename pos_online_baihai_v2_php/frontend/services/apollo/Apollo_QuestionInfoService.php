<?php

namespace frontend\services\apollo;
use ArrayObject;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;
use stdClass;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-8-6
 * Time: 下午3:47
 */

/**
 * Class BaseInformationService
 */
class Apollo_QuestionInfoService extends BaseService
{

    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['apollo_webService'] . "apollo/resource/questionInfo?wsdl");
    }

    /**
     * 题目录入
     * provience    省
     * city    市
     * country    区县
     * gradeid    适用年级
     * 关联年级信息表的年级ID
     * subjectid    科目
     * 关联科目信息表的科目ID
     * versionid    版本
     * 管理版本信息表的版本ID
     * kid    知识点
     * 关联知识点表的知识点ID。
     * 多选，知识点之间使用逗号分隔
     * typeId    题型
     * 1    单选题
     * 2    填空题
     * 3    计算题
     * 4    解答题
     * 5    判断题
     * provenance    出处
     * 21001    高考
     * 21002    中考
     * 21003    月考
     * 21004    普通考试
     * 21005    升级
     * 21006    上学期期末考试
     * 21007    下学期期末考试
     * year    年份
     * school    名校
     * 20801    北京四中
     * 20802    人大附中
     * 20804    黄冈中学
     * 20805    衡水中学
     * 20806    其他
     * complexity    难易程度,复杂度
     * 21101    简单
     * 21102    复杂
     * 21103    非常复杂
     * capacity    能力提升
     * 21201    提升阅读理解能力
     * 21202    提升计算能力
     * 21203    提升逻辑能力
     * tags    自定义标签。标签之间使用逗号分隔
     * name    题目名称
     * content    题目内容
     * textContent    题目内容文本
     * answerOptionJson    选择题备选答案
     * [
     * {"id":"A","content":"备选项1","right":"1"}
     * ]
     * answerContent    答案
     * analytical    解析
     * questionPrice    题目价格
     * childQuesJson    小题：
     * [                 {"content":"","answerContent":"","answerOptionJson":"","analytical":""}
     * ]
     * operater    录入人
     * @param $provience
     * @param $city
     * @param $country
     * @param $gradeid
     * @param $subjectid
     * @param $versionid
     * @param $kid
     * @param $typeId
     * @param $provenance
     * @param $year
     * @param $school
     * @param $complexity
     * @param $capacity
     * @param $tags
     * @param $name
     * @param $content
     * @param $textContent
     * @param $answerOptionJson
     * @param $answerContent
     * @param $analytical
     * @param $questionPrice
     * @param $childQuesJson
     * @param $operater
     */
    function  questionAdd(
        $provience,
        $city,
        $country,
        $gradeid,
        $subjectid,
        $versionid,
        $kid,
        $typeId,
        $provenance,
        $year,
        $school,
        $complexity,
        $capacity,
        $tags,
        $name,
        $content,
        $textContent,
        $answerOptionJson,
        $answerContent,
        $analytical,
        $questionPrice,
        $childQuesJson,
        $operater)
    {
        $param = [
            'provience' => $provience,
            'city ' => $city,
            'country' => $country,
            'gradeid' => $gradeid,
            'subjectid' => $subjectid,
            'versionid' => $versionid,
            'kid' => $kid,
            'typeId' => $typeId,
            'provenance' => $provenance,
            'year' => $year,
            'school' => $school,
            'complexity' => $complexity,
            'capacity' => $capacity,
            'tags' => $tags,
            'name' => $name,
            'content' => $content,
            'textContent' => $textContent,
            'answerOptionJson' => $answerOptionJson,
            'answerContent' => $answerContent,
            'analytical' => $analytical,
            'questionPrice' => $questionPrice,
            'childQuesJson' => $childQuesJson,
            'operater' => $operater


        ];
        $soapResult = $this->_soapClient->questionInfo($param);
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json, true);
        return $result;

    }


    /**
     * 查询三库试卷
     *
     * @param $questSearchModel Apollo_QuestSearchModel
     * @return stdClass
     * @throws \Camcima\Exception\InvalidParameterException
     */
    function  questionSearch(
        $questSearchModel)
    {
        $param = [
            'provience' => $questSearchModel->provience,
            'city ' => $questSearchModel->city,
            'country' => $questSearchModel->country,
            'gradeid' => $questSearchModel->gradeid,
            'subjectid' => $questSearchModel->subjectid,
            'versionid' => $questSearchModel->versionid,
            'kid' => $questSearchModel->kid,
            'typeId' => $questSearchModel->typeId,
            'provenance' => $questSearchModel->provenance,
            'year' => $questSearchModel->year,
            'school' => $questSearchModel->school,
            'complexity' => $questSearchModel->complexity,
            'capacity' => $questSearchModel->capacity,
            'tags' => $questSearchModel->tags,
            'name' => $questSearchModel->name,
            'content' => $questSearchModel->content,
            'textContent' => $questSearchModel->textContent,
            'answerOptionJson' => $questSearchModel->answerOptionJson,
            'answerContent' => $questSearchModel->answerContent,
            'analytical' => $questSearchModel->analytical,
            'questionPrice' => $questSearchModel->questionPrice,
            'childQuesJson' => $questSearchModel->childQuesJson,
            'showTypeId' =>$questSearchModel->showTypeId,
            'operater' => $questSearchModel->operater,
	        'userID' => $questSearchModel->userID,
            'pageSize' => $questSearchModel->pageSize,
            'currPage' => $questSearchModel->currPage,

        ];
        $soapResult = $this->_soapClient->questionSearch($param);
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        $std = new stdClass();
        $std->currPage = 0;
        $std->totalPages = 0;
        $std->countSize = 0;
        $std->pageSize = 0;
        $std->list = array();

        $result = $this->mapperJsonResult($json);

        if ($result->resCode == self::successCode) {
            $std->currPage = $result->data->currPage;
            $std->totalPages = $result->data->totalPages;
            $std->countSize = $result->data->countSize;
            $std->pageSize = $result->data->pageSize;
            $std->list = $result->data->list;
        }
        return $std;

    }

    /**
     * 查询三库试卷(增加了题目类型查询showTypeId)
     *
     * @param $questSearchModel Apollo_QuestSearchModel
     * @return stdClass
     * @throws \Camcima\Exception\InvalidParameterException
     */
    function  questionSearchByShowtype(
        $questSearchModel)
    {
        $param = [
            'provience' => $questSearchModel->provience,
            'city ' => $questSearchModel->city,
            'country' => $questSearchModel->country,
            'schoolLevel' => $questSearchModel->schoolLevel,
            'gradeid' => $questSearchModel->gradeid,
            'subjectid' => $questSearchModel->subjectid,
            'versionid' => $questSearchModel->versionid,
            'kid' => $questSearchModel->kid,
            'typeId' => $questSearchModel->typeId,
            'provenance' => $questSearchModel->provenance,
            'year' => $questSearchModel->year,
            'school' => $questSearchModel->school,
            'complexity' => $questSearchModel->complexity,
            'capacity' => $questSearchModel->capacity,
            'tags' => $questSearchModel->tags,
            'name' => $questSearchModel->name,
            'content' => $questSearchModel->content,
            'textContent' => $questSearchModel->textContent,
            'answerOptionJson' => $questSearchModel->answerOptionJson,
            'answerContent' => $questSearchModel->answerContent,
            'analytical' => $questSearchModel->analytical,
            'questionPrice' => $questSearchModel->questionPrice,
            'childQuesJson' => $questSearchModel->childQuesJson,
            'showTypeId' =>$questSearchModel->showTypeId,
            'operater' => $questSearchModel->operater,
            'pageSize' => $questSearchModel->pageSize,
            'currPage' => $questSearchModel->currPage,

        ];
        $soapResult = $this->_soapClient->questionSearchByShowtype($param);
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        $std = new stdClass();
        $std->currPage = 0;
        $std->totalPages = 0;
        $std->countSize = 0;
        $std->pageSize = 0;
        $std->list = array();

        $result = $this->mapperJsonResult($json);

        if ($result->resCode == self::successCode) {
            $std->currPage = $result->data->currPage;
            $std->totalPages = $result->data->totalPages;
            $std->countSize = $result->data->countSize;
            $std->pageSize = $result->data->pageSize;
            $std->list = $result->data->list;
        }
        return $std;

    }

    /**
     * 根据题目id查询题目
     */
    function  questionSearchById(
        $id)
    {
        $param = [
            'id' => $id
               ];
        $soapResult = $this->_soapClient->questionSearch($param);
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        $std = new stdClass();
        $std->currPage = 0;
        $std->totalPages = 0;
        $std->countSize = 0;
        $std->pageSize = 0;
        $std->userID = '';
        $std->list = array();

        $result = $this->mapperJsonResult($json);

        if ($result->resCode == self::successCode) {
            $std->currPage = $result->data->currPage;
            $std->totalPages = $result->data->totalPages;
            $std->countSize = $result->data->countSize;
            $std->pageSize = $result->data->pageSize;
            $std->list = $result->data->list;
        }
        return $std;

    }

    /**
     * 根据自定义标签查询试题
     * @param $tags     自定义标签
     * @return stdClass
     */
    public function questionSearchByTags($tags,$currPage,$pageSize){
        $param = [
            'tags' => $tags,
            'currPage'=>$currPage,
            'pageSize'=>$pageSize
        ];
        $soapResult = $this->_soapClient->questionSearch($param);
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        $std = new stdClass();
        $std->currPage = 0;
        $std->totalPages = 0;
        $std->countSize = 0;
        $std->pageSize = 0;
        $std->list = array();

        $result = $this->mapperJsonResult($json);

        if ($result->resCode == self::successCode) {
            $std->currPage = $result->data->currPage;
            $std->totalPages = $result->data->totalPages;
            $std->countSize = $result->data->countSize;
            $std->pageSize = $result->data->pageSize;
            $std->list = $result->data->list;
        }
        return $std;
    }

    /**
     * 2.2.6.录入图片题目
     * @param provience    省
     * @param city         市
     * @param country      区县
     * @param gradeid      适用年级
     * @param subjectid    科目
     * @param versionid    版本
     * @param complexity    难易程度,复杂度  21101	简单 21102	复杂 21103	非常复杂
     * @param name          题目名称
     * @param content       题目内容（多个url用逗号隔开）
     * @param answerContent 答案（多个url用逗号隔开）
     * @param analytical    解析（多个url用逗号隔开）
     * @param operater      录入人
     * 返回的JSON：
        {"data":{},"resCode":"000001","resMsg":"录入失败"}
            返回的JSON：
            {"data":{
            "questionId":""
            },
            "resCode":"000001",
            "resMsg":"录入成功"
        }
     */
    public  function  questionPicAdd(
        $model,
        $name,
        $analytical,
        $operater)
    {
        $param = [
            'provience' => $model->provience,
            'city' => $model->city,
            'country' => $model->country,
            'gradeid' => $model->gradeid,
            'subjectid' => $model->subjectid,
            'versionid' => $model->versionid,
            'complexity' => $model->complexity,
            'content' => $model->content,
            'answerContent' => $model->answerContent,
            'name' => $name,
            'analytical' => $analytical,
            'operater' => $operater
        ];
        $soapResult = $this->_soapClient->questionPicAdd($param);
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json, true);
        return $result;

    }

    /**
     * 2.2.12.	修改图片题目
     * @param id           题目id
     * @param provience    省
     * @param city         市
     * @param country      区县
     * @param gradeid      适用年级
     * @param subjectid    科目
     * @param versionid    版本
     * @param complexity    难易程度,复杂度  21101	简单 21102	复杂 21103	非常复杂
     * @param name          题目名称
     * @param content       题目内容（多个url用逗号隔开）
     * @param answerContent 答案（多个url用逗号隔开）
     * @param analytical    解析（多个url用逗号隔开）
     * @param operater      录入人
     * 返回的JSON：
    {"data":{},"resCode":"000001","resMsg":"录入失败"}
    返回的JSON：
    {"data":{
    "questionId":""
    },
    "resCode":"000001",
    "resMsg":"录入成功"
    }
     */
    public function  questionPicUpdate(
        $model,
        $id,
        $name,
        $analytical,
        $operater)
    {
        $param = [
            'provience' => $model->provience,
            'city' => $model->city,
            'country' => $model->country,
            'gradeid' => $model->gradeid,
            'subjectid' => $model->subjectid,
            'versionid' => $model->versionid,
            'complexity' => $model->complexity,
            'content' => $model->content,
            'answerContent' => $model->answerContent,
            'id'=>$id,
            'name' => $name,
            'analytical' => $analytical,
            'operater' => $operater
        ];
        $soapResult = $this->_soapClient->questionPicUpdate($param);
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json, true);
        return $result;

    }

    /**
     * 2.2.13.	删除题目
     * questionDelete
     * @param $id           题目id
     * @param $operater     录入人(判断是否有权限删除)
     * @return {
    "resCode":"000000",
    "resMsg":"成功",
    "data":{
    "collectID":"",//收藏id
    }
    }
    {
    "resCode":"000001",
    "resMsg":"失败",
    "data":{
    }
    }
     */
    public function questionDelete($id = '', $operater = '')
    {
        $soapResult = $this->_soapClient->questionDelete(array( 'id' => $id, 'operater' => $operater));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();

    }


}