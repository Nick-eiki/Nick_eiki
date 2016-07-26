<?php
namespace frontend\services\apollo;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-27
 * Time: 下午1:59
 */
class Apollo_QuestionTypeService extends BaseService{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['apollo_webService'] . "apollo/resource/questionType?wsdl");
    }

    /**
     *2.3.1.根据学部和科目查询题型
     * 接口地址	http://主机地址:端口号/ resource/ questionType?wsdl
     * 方法名	queryQuesType
     * @param $schoolLevel      学部（数据字典 代码202）
     * @param $subject          科目
     * "data": {
    "listSize": 2,
    "list": [{
    "typeId": "30018",
    "typeName": "解答题"
    },
    {
    "typeId": "30017",
    "typeName": "填空题"
    }
    ]
    },
    "resCode": "000000",
    "resMsg": "成功"
     * @return array
     */
    public function queryQuesType($schoolLevel,$subject){
        $soapResult = $this->_soapClient->queryQuesType(array("schoolLevel" => $schoolLevel,"subject"=>$subject));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data->list;
        }
        return array();
    }
    /*
     * 根据年级、科目查题型
     */
    public function queryQuesTypeByGrade($grade,$subject){
        $soapResult = $this->_soapClient->queryQuesTypeByGrade(array("gradeId" => $grade,"subject"=>$subject));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data->list;
        }
        return array();
    }
    /*
   * 2.3.2.查询小题题型
   * typeId	大题题型
   */
    public function queryQuesTypeSubs($typeId){
        $soapResult = $this->_soapClient->queryQuesTypeSubs(
            array(
                'typeId' => $typeId,
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data->list;
        }
        return array();
    }
}