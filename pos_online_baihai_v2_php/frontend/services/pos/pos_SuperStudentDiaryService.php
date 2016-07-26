<?php

namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2015/6/25
 * Time: 13:54
 */
class pos_SuperStudentDiaryService extends  BaseService{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/superStudentDiary?wsdl");
    }

    /**
     * 3.46.1.	录入养成记
     * @param $creatorID
     * @param $title
     * @param $subjectID
     * @param $type
     * @param $content
     * @param $summary
     * @return ServiceJsonResult
     */
    public function createDiary($creatorID,$title,$subjectID,$type,$content,$summary){
        $soapResult = $this->_soapClient->createDiary(
            array(
               "creatorID"=>$creatorID,
                "title"=>$title,
                "subjectID"=>$subjectID,
                "type"=>$type,
                "content"=>$content,
                "summary"=>$summary
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 3.46.2.	查询列表
     * @param $creatorID
     * @param $currPage
     * @param $pageSize
     * @param string $title
     * @param string $subjectID
     * @param string $type
     * @param string $summary
     * @return array
     */
    public function queryList($creatorID,$currPage,$pageSize,$title="",$subjectID="",$type="",$summary=""){
        $soapResult = $this->_soapClient->queryList(
            array(
               "creatorID"=>$creatorID,
                "currPage"=>$currPage,
                "pageSize"=>$pageSize,
                "title"=>$title,
                "subjectID"=>$subjectID,
                "type"=>$type,
                "summary"=>$summary
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }


}