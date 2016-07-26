<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-22
 * Time: 下午6:26
 */
class pos_SchlHomMsgService extends BaseService{

    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/schlHomMsg?wsdl");
    }

    /**
     * 高：2014.10.22
     * 3.24.1.添加家校短息
     * 接口地址	http://主机地址:端口号/ /schoolService/ schlHomMsg?wsdl
     * 方法名	createSchlHomMsg
     * @param $title            短信标题
     * @param $receiverJson     接收者{"receivers":[{"type":"class","id":"c1","scope":"all"//all:全部，part: 部分},{"type":"student","id":"s1","scope":""}]}
     * @param $receiverType     收件人身份(数据字典 1学生 2家长)
     * @param $sendWay          发送方式(数据字段 1短息 2站内信)
     * @param $rankingChg       本班整体名次及其变化
     * @param $rankJson         分数区间{"ranks":[{"low":"60","high":"70","peoples":"10"},{"low":"70","high":"80","peoples":"20"}]}
     * @param $weakPoint        知识盲点
     * @param $addContent       补充内容
     * @param $creator          创建人
     * @param $token            用于安全控制，暂时为空
     *
     * {
    "resCode":"000000",
    "resMsg":"成功",
    "data":{
    }
    }
     * @return ServiceJsonResult
     */
    public function createSchlHomMsg($title,$classId,$scope,$examId,$receiverJson,$receiverType,$sendWay,$rankingChg,$rankJson,$weakPoint,$addContent,$creator,$reference,$subjectId,$kids,$urls,$token){
        $soapResult = $this->_soapClient->createSchlHomMsg(array('title' => $title,'classId'=>$classId,'scope'=>$scope,'examId'=>$examId,'receiverJson' => $receiverJson, 'receiverType' => $receiverType,
            'sendWay' => $sendWay, 'rankingChg' => $rankingChg, 'rankJson' => $rankJson, 'weakPoint' => $weakPoint, 'addContent' => $addContent,'creator' => $creator,'reference'=>$reference,
            'subjectId'=>$subjectId,'kids'=>$kids,'urls'=>$urls,'token' => $token));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 高：2014.10.22
     * 3.24.4.查询家校短信
     * 接口地址	http://主机地址:端口号/ /schoolService/ schlHomMsg?wsdl
     * 方法名	querySchlHomMsg
     * @param $id       短信id
     * @param $creator  创建人
     * @param $token    用于安全控制，暂时为空
     *
     * "resCode":"000000",
    "resMsg":"查询成功",
    "data":{
    "currPage":"当前页码",
    "totalPages":"总页数",
    "countSize":"总记录数",
    "pageSize":"每页数据的条数",
    "list":[//列表
    {"id":"",//短信id
    "title":"",//短信标题
    "receiverType":"",//收件人身份(数据字典 1学生 2家长)
    "sendWay":"",//发送方式(数据字段 1短息 2站内信)
    "rankingChg":"",//本班整体名次及其变化(0没有,1变化)
    "weakPoint":"",//知识盲点
    "addContent":"",//补充内容
    "creator":"",//创建人
    "creatTime":"",//创建时间
    "receivers":"",//家校联系短信收件人
    [
    {"receivers":[{"type":"class",//类型class班级，student学生
    "id":"c1",//班级或学生id
    "scope":"all"//范围all全部,part部分
    },{"type":"student","id":"s1","scope":""}]}
    ]
    "ranks":""//家校联系短信分数段
    [
    {"ranks":[{"low":"60","high":"70","peoples":"10"},{"low":"70","high":"80","peoples":"20"}]}
    ]
    },
    ...
    ]
     * @return array
     */
    public function querySchlHomMsg($id,$creator,$isSend,$currPage,$pageSize,$token){
        $soapResult = $this->_soapClient->querySchlHomMsg(array("id" => $id, 'creator' => $creator,'isSend'=>$isSend,'currPage'=>$currPage,'pageSize'=>$pageSize, 'token' => $token));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 查询一条家校联系详情数据
     * @param $id
     * @return mixed
     */
    public function seachHomMsg($id){
        $result = $this->querySchlHomMsg($id,'','','','','');
        if(!empty($result)){
            return $result->list[0];
        }
        return null;
    }

    /**
     * 高：2014.10.28
     * 3.24.2.修改家校短息
     * 接口地址	http://主机地址:端口号/ /schoolService/ schlHomMsg?wsdl
     * 方法名	updateSchlHomMsg
     * @param $id              短信id
     * @param $title            短信标题
     * @param $receiverJson
     * @param $receiverType     收件人身份(数据字典 1学生 2家长)
     * @param $sendWay          发送方式(数据字段 1短息 2站内信)
     * @param $rankingChg       本班整体名次及其变化
     * @param $rankJson         分数区间{"ranks":[{"low":"60","high":"70","peoples":"10"},{"low":"70","high":"80","peoples":"20"}]}
     * @param $weakPoint        知识盲点
     * @param $addContent       补充内容
     * @param $creator          创建人
     * @param $token
     * {
    "resCode":"000000",
    "resMsg":"成功",
    "data":{
    }
    }
     * @return ServiceJsonResult
     */
    public function updateSchlHomMsg($id,$title,$examId,$classId,$scope,$receiverJson,$receiverType,$sendWay,$rankingChg,$rankJson,$weakPoint,$addContent,$reference,$subjectId,$kids,$urls,$token){
        $soapResult = $this->_soapClient->updateSchlHomMsg(array('id'=>$id,'title' => $title,'receiverJson' => $receiverJson,'examId'=>$examId,'classId' => $classId,'scope' => $scope, 'receiverType' => $receiverType,
            'sendWay' => $sendWay, 'rankingChg' => $rankingChg, 'rankJson' => $rankJson, 'weakPoint' => $weakPoint, 'addContent' => $addContent,'reference'=>$reference,'subjectId'=>$subjectId,'kids'=>$kids,'urls'=>$urls, 'token' => $token));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 高：2014.10。28
     * 3.24.3.删除家校短信
     * 接口地址	http://主机地址:端口号/ /schoolService/ schlHomMsg?wsdl
     * 方法名	delSchlHomMsg
     * @param $id       短信id
     * {
    "resCode":"000000",
    "resMsg":"成功",
    "data":{
    }
    }
     * @return ServiceJsonResult
     */
    public function delSchlHomMsg($id){
        $soapResult = $this->_soapClient->delSchlHomMsg(array('id'=>$id));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 高：2014.10.28
     * 发送信息
     * @param $id 信息id
     * @param $token
     * @return ServiceJsonResult
     */
    public function sendSchlHomMsg($id){
        $soapResult = $this->_soapClient->sendSchlHomMsg(array('id'=>$id));
        $result=  $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return null;
    }



    /**
     * 3.25.6. 获取考试最高最低分
     * 接口地址	http://主机地址:端口号/ /schoolService/ schlHomMsg?wsdl
     * 方法名	getMaxMinScore
     * @param $examID   考试id
     * @return ServiceJsonResult
     * {"data":
    {"maxmin":{
    "min":"0"
    ,"max":"134"}},
    "resCode":"000000","resMsg":"成功"}
     */
    public function getMaxMinScore($examID){
        $soapResult = $this->_soapClient->getMaxMinScore(array('examID'=>$examID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data->maxmin;
        }
        return null;
    }

	/**
	 * 3.25.7.	作业未完成统计-消息发送给查询
	 * 接口地址	http://主机地址:端口号/ /schoolService/ schlHomMsg?wsdl
	 * 方法名	queryMsgHwkNotDone
	 * @param $classID	        班级id
	 * @param $orderType        排序方式 1按科目排序 2按总个数统计
	 * @param $orderBySubject   排序科目id
	 * @param $ascDesc          1 升序  2降序
	 * @param $beginDate	    时间段起 2015-05-01
	 * @param $endDate	        时间段截止 2015-06-01
	 * @return array
	 *
	 * 应答	失败	  返回的JSONB：参考响应代码
	 * 成功	{
				"data": {
				"listHeadSize": 2,
				"listHead": [//科目列表
				{
				"subjectId": "10010",//科目id
				"subjectName": "语文"//科目
				},
				{
				"subjectId": "10011",
				"subjectName": "数学"
				}
				]
				"listSize": 7,
				"list": [
				{
				"id": 2,//消息id
				"creatTime": "2015-05-03",//时间
				"stuID": "2323",//学号
				"userID": "101457",//用户id
				"userName": "小愣子",//姓名
				"senderID": "系统",//发送人id
				"senderName": "系统",//发送人姓名
				"10010,语文": 35,
				"10011,数学": 1,
				"sumCnt": 36//未完成总数
				},
				{
				"id": 1,//消息id
				"creatTime": "2015-05-03",//时间
				"stuID": "",
				"userID": "101399",
				"userName": null,
				"senderID": "系统",//发送人id
				"senderName": "系统",//发送人姓名
				"10010,语文": 35,
				"10011,数学": 1,
				"sumCnt": 36
				}
				]
				},
				"resCode": "000000",
				"resMsg": "查询成功"
				}
	 */

	public function queryMsgHwkNotDone($classID, $orderType, $orderBySubject, $ascDesc, $beginDate, $endDate){
		$soapResult = $this->_soapClient->queryMsgHwkNotDone(
			array(
				'classID' => $classID,
				'orderType' => $orderType,
				'orderBySubject' => $orderBySubject,
				'ascDesc' => $ascDesc,
				'beginDate' => $beginDate,
				'endDate' => $endDate
			)
		);
		$result = $this->soapResultToJsonResult($soapResult);
		if($result->resCode == self::successCode){
			return $result->data;
		}
		return array();
	}
}