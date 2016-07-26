<?php
namespace frontend\services\pos;
use ArrayObject;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-9-9
 * Time: 下午12:58
 */
class pos_QueFavoriteFolder extends BaseService{
    function __construct(){
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService']."schoolService/queFavoriteFolder?wsdl");
    }

    /**
     * 3.47.1.	添加收藏夹
     * addQueFavoriteFolder
     * @param $questionID
     * @param $userID
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
    public function addQueFavoriteFolder($questionID = '', $userID = '')
    {
        $soapResult = $this->_soapClient->addQueFavoriteFolder(array( 'questionID' => $questionID, 'userID' => $userID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();

    }

    /**
     * 3.47.2.	按内容删除收藏
     * delQueFavoriteFolderByDtl
     * @param $questionID
     * @param $userID
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
    public function delQueFavoriteFolderByDtl($questionID = '', $userID = '')
    {
        $soapResult = $this->_soapClient->delQueFavoriteFolderByDtl(array( 'questionID' => $questionID, 'userID' => $userID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();

    }


    //收藏题目列表
    public function queryQueFavoriteFolder($userID,$name='',$provience='',$city='',$country='', $schoolLevel='', $gradeid='',$subjectid='',$versionid='',$kid='',$typeId='',$provenance='',$year='',$school='',$complexity='',$capacity='',$tags='',$content='',$analytical='',$questionPrice='', $chapterId = '',$showTypeId = '',$currPage,$pageSize){
        $soapResult = $this->_soapClient->queryQueFavoriteFolder(
            array(
                "userID" => $userID,
                "name" => $name,
                "provience" => $provience,
                "city" => $city,
                "country" => $country,
                "schoolLevel" =>$schoolLevel,
                "gradeid" => $gradeid,
                "subjectid" => $subjectid,
                "versionid" => $versionid,
                "kid" => $kid,
                "typeId" => $typeId,
                "provenance" => $provenance,
                "year" => $year,
                "school" => $school,
                "complexity" => $complexity,
                "capacity" => $capacity,
                "tags"=> $tags,
                "content" => $content,
                "analytical" => $analytical,
                "questionPrice" => $questionPrice,
                'chapterId'=>$chapterId,
                'showTypeId'=>$showTypeId,
                "currPage" => $currPage,
                "pageSize" => $pageSize
            ));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        $result = $this->mapperJsonResult($json);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }


}