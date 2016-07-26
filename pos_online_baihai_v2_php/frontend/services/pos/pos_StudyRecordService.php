<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: liquan
 * Date: 2014/12/4
 * Time: 15:21
 */
class pos_StudyRecordService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/studentStudyRecord?wsdl");
    }

    /*
     * .查询学习记录
     * studentID	学生ID
     * currPage	当前页码
     * pageSize	每页条数
     * {"data":{
             "currPage":"当前页码",
              "totalPages":"总页数",
              "countSize":"总记录数",
              "pageSize":"每页数据的条数",
                      “recordList”:[//记录列表
                              {
                           “firstTime”:”年月份”
                           “detailList” [
                               {
                               “recordID”:记录id
                                “recordType”:记录操作类型
                                “resourceType”:资源类型
                                 “secondResourceType”:资源子类型
                                 “resourceID”:资源id
                                  “resourceName””资源名称”
                                    “appendInfo”:”附加信息”
                                   “createTime”:记录时间
                                         }
                                          ]

                    //注意查看说明信息
                               }
                             ]


            },
            "resCode":"000000",
            "resMsg":"成功"}

     */
    public function queryStudentStudyRecord($studentID = '', $currPage = '', $pageSize = '')
    {
        $soapResult = $this->_soapClient->queryStudentStudyRecord(array('studentID' => $studentID, 'currPage' => $currPage, 'pageSize' => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }
}
