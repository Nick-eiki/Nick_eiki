<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-9-11
 * Time: 下午2:25
 */
class pos_PointLineInfoService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/PointLineInfo?wsdl");
    }

    /**
     * 高 2014 9 11
     * 4.4.1.分数线搜索
     * 接口地址    http://主机地址:端口号/ schoolService / PointLineInfo?wsdl
     *  方法名       pointLineSearch
     * @param $schoolID         学校id
     * @param $year             年份（可为空）
     * @param $departmentID   学部编码（可为空）
     * @return ServiceJsonResult
     * 返回的JSON示例：
     * {
     * "data": {
     * pointLinelist：[//分数线列表
     * “year”:””,
     * “departmentName”:””;
     * “admissionLine”:””;
     * }
     * ]
     * "resCode": "000000",
     * "resMsg": "查询成功"
     * }
     */
    public function search($schoolID, $year, $departmentID)
    {
        $soapResult = $this->_soapClient->search(array("schoolID" => $schoolID, 'year' => $year, 'departmentID' => $departmentID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();


    }

    /**
     * 当前年招生简章
     * @param $schoolID
     * @return ServiceJsonResult
     */
    public function search_curr($schoolID)
    {

        $ys = date("Y", time());
        $month= date("m", time());
        if($month<9){
            $ys--;
        }
        return $this->search($schoolID,$ys,'20203');


    }

    /**
     * 高 2014.9.11
     * 4.4.2.分数线添加
     * 接口地址    http://主机地址:端口号/ schoolService / PointLineInfo?wsdl
     *  方法名    pointLineAdd
     * @param $departmentID   string     学部名称
     * @param $year       string           年份
     * @param $admissionLine  string       录取分数线
     * @param $seclectSchoolLine  string    择校分数线
     * @param $creatorID integer
     * @param $residentialLine    string    住宿分数线
     * @param $token            string    安全保护措施
     *
     * 回的JSON示例：
     * {
     * "data": {
     * “pointLineID”:””,
     * }
     * "resCode": "000000",
     * "resMsg": "查询成功"
     * }
     * @return ServiceJsonResult
     */
    public function add($departmentID, $year, $admissionLine, $seclectSchoolLine, $creatorID, $residentialLine, $schoolID, $token)
    {
        $soapResult = $this->_soapClient->add(array("departmentID" => $departmentID, 'year' => $year, 'admissionLine' => $admissionLine, 'seclectSchoolLine' => $seclectSchoolLine, 'creatorID' => $creatorID, 'residentialLine' => $residentialLine, 'schoolID' => $schoolID, 'token' => $token));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.4.3.编辑分数线信息
     * 接口地址	http://主机地址:端口号/ schoolService / PointLineInfo?wsdll
     * 方法名	update
     * @param $pointLineID      分数线id
     * @param $departmentID   学部名称
     * @param $year             年份
     * @param $admissionLine    录取分数线
     * @param $seclectSchoolLine  择校分数线
     * @param $residentialLine  住宿分数线
     * @param $token            安全保护措施
     *
     * "data": {
    “pointLineID”:””

    }
    "resCode": "000000",
    "resMsg": "修改成功
     * @return ServiceJsonResult
     */
    public function update($pointLineID,$departmentID,$year,$admissionLine,$seclectSchoolLine,$residentialLine,$token){
        $arr = [
            'pointLineID'=>$pointLineID,
            'departmentID'=>$departmentID,
            'year'=>$year,
            'admissionLine'=>$admissionLine,
            'seclectSchoolLine'=>$seclectSchoolLine,
            'residentialLine'=>$residentialLine,
            'token'=>$token
        ];
        $soapResult = $this->_soapClient->update($arr);
         return  $this->soapResultToJsonResult($soapResult);
    }


    /**
     * 3.15.6.最近一年的分数线搜索
     * 接口地址	http://主机地址:端口号/ schoolService / PointLineInfo?wsdl
     * 方法名	searchLately
     * @param $schoolID     学校id
     * @param $departmentID 学部编码（可为空）从基础数据获取
     * "data": {
    "admissionLine": "560",//录取分数线
    "createTime": "2014-09-22 11:05:38",//信息创建时间
    "creatorID":"",
    "departmentID": "20201",//学部编码
    "departmentName": "小学部",//学部名称
    "isDelete": "0",
    "pointLineID": "10116",//分数线ID
    "residentialLine": "233",//住宿分数线
    "schoolID": "1014",//学校ID
    "seclectSchoolLine": "580",//择校分数线
    "year": "2014"//年份
    },
    "resCode": "000000",
    "resMsg": "成功"
     * @return null
     */
    public function searchLately($schoolID,$departmentID){
        $soapResult = $this->_soapClient->searchLately(array("schoolID" => $schoolID, 'departmentID' => $departmentID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return null;
    }

}