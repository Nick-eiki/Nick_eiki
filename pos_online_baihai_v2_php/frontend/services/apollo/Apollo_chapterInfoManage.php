<?php

namespace frontend\services\apollo;
use frontend\components\helper\StringHelper;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;
use stdClass;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-8-14
 * Time: 上午10:50
 */

/**
 * Class Apollo_chapterInfoManageManage
 */
class Apollo_chapterInfoManage extends BaseService
{

    /**
     *
     */
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['apollo_webService'] . "apollo/resource/chapterInfo?wsdl");
    }


    /**
     * /**
     *  章节查询searchKnowledgePoint
     * subjectID    科目
     * departmentID    学籍（小学\初中\高中等）
     * 返回
     * id,pid,name
     *
     * @param   $subjectID  string  科目
     * @param $departmentID  string 学籍（小学\初中\高中等）
     * @param $bookVersionID string 教材版本（人教版\北师大版等）
     * @param $schoolLength string 学制（五四\五三\六三等
     * @param $grade string 年级
     * @param $session	    学期（21301 上学期，21302下学期）可为空
     * @param $bookAtt      教材属性，取接口chapterBaseNodeSearch返回的id
     * @return array
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function chapterSearch($subjectID, $departmentID, $bookVersionID, $schoolLength, $grade, $session=null, $bookAtt=null)
    {
        $arr = array(
            'subject' => $subjectID,
            'schoolLevel' => $departmentID,
            'materialVersion' => $bookVersionID,
            'schoolLength' => $schoolLength,
            'grade' => $grade,
	        'session' => $session,
	        'bookAtt' => $bookAtt
        );
        $soapResult = $this->_soapClient->chapterSearch($arr);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data->cPointList;
        }

        return array();
    }

    /**
     *  查询书转树形结点
     * @param $subjectID
     * @param $departmentID
     *  'bookAtt' => $bookAtt
     * @return array
     */
    public  function  searchChapterPointToTree($subjectID, $departmentID, $bookVersionID, $schoolLength, $grade, $session=null, $bookAtt = null)
    {
        $resultArr = [];

        $arr = $this->chapterSearch($subjectID, $departmentID, $bookVersionID, $schoolLength, $grade, $session, $bookAtt);

        $callback =
            function ($item) {
                    $c=  new  stdClass();
                    $c->id=$item->id;
                    $c->pId=$item->pId;
                    $c->name=$item->name;
                    return $c;
            };
        foreach ($arr as $item) {
            $resultArr[] = $callback($item);
        }
        return $resultArr;
    }


    /**
     * 查询单一个章节
     *  {
     *
     * "id":"1",
     * "pId":"0",
     * "name":"语文"
     *
     *
     * }
     *  searchId
     * @param $id
     * @return mixed
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  searchIdChapter($id)
    {

        $arr = array(
            'id' => $id,
        );

        $soapResult = $this->_soapClient->searchIdChapter($arr);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }

        return null;
    }


    public function  cache_searchById($id)
    {
        $cacheId = 'cache_chapter_id'.$id;
        $result = app()->cache->get($cacheId);
        if ($result === false) {
            $result = $this->searchIdChapter($id);
            if (!empty($result)) {
                app()->cache->set($cacheId, $result, 120);
            }
        }
        return $result;

    }

    /**
     * 根据ID获取名字
     * @param $id
     */
    public function    getNamebyId($id){

        $result= $this->cache_searchById($id);
        return   is_null($result)?'':$result->name;

    }
    /**  查询多个知识点
     * @param $str 参数传多个，号分陨
     * @return array
     */
    public  function  findChapter($str)
    {
        $strarr = StringHelper::splitNoEMPTY($str);
        $resultArr = array();

        foreach ($strarr as $key => $v) {

         $res=  $this->cache_searchById($v);
            if (isset($res))
            {
                $resultArr[] =$res;
            }

        }

        return $resultArr;
    }


    /**
     * 2.6.2.章节添加
     * 注意：subject,schoolLevel,materialVersion,schoolLength,schoolLength,可以为空，用于增加新教材时使用，新添教材时id传入“0”
     * 添加子章节时subject,schoolLevel,materialVersion,schoolLength,schoolLength,为空
     *接口地址    http://主机地址:端口号/service/chapterInfo?wsdl
     *方法名    chapterAdd
     * @param $id               章节id
     * @param $chapterName      新增章节名称
     * @param $subjectID        科目(可以为空)
     * @param $departmentID     学籍（小学\初中\高中等）()
     * @param $bookVersionID    教材版本（人教版\北师大版等）()
     * @param $schoolLength     学制（五四\五三\六三等）()
     * @param $grade            年级()
     * @param $token
     *   "data": {
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * @return ServiceJsonResult
     */
    public function chapterAdd($id, $chapterName, $subjectID, $departmentID, $bookVersionID, $schoolLength, $grade)
    {
        $soapResult = $this->_soapClient->chapterAdd(array('id' => $id, 'chapterName' => $chapterName, 'subjectID' => $subjectID, 'departmentID' => $departmentID, 'bookVersionID' => $bookVersionID,
            'schoolLength' => $schoolLength, 'grade' => $grade));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 2.6.3.章节修改
     * 接口地址    http://主机地址:端口号/service/chapterInfo?wsdl
     * 方法名    chapterModify
     * @param $id               章节id
     * @param $chaptertName     章节名称
     * @param $token
     *   "data": {
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * @return ServiceJsonResult
     */
    public function chapterModify($id, $chaptertName)
    {
        $soapResult = $this->_soapClient->chapterModify(array('id' => $id, 'chaptertName' => $chaptertName));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 2.6.4.章节移动
     * 接口地址    http://主机地址:端口号/service/chapterInfo?wsdl
     * 方法名    chapterMove
     * @param $id   移动节点的章节的id）
     * @param $pid  移动到的章节id
     * @param $token
     * "data": {
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * @return ServiceJsonResult
     */
    public function chapterMove($id, $pid)
    {
        $soapResult = $this->_soapClient->chapterMove(array('id' => $id, 'pid' => $pid));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 2.6.5.章节删除
     * 接口地址    http://主机地址:端口号/service/chapterInfo?wsdl
     * 方法名    chapterDelete
     * @param $id       章节id
     * @param $remark   删除原因
     * @param $token
     *   "data": {
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * @return ServiceJsonResult
     */
    public function chapterDelete($id, $remark)
    {
        $soapResult = $this->_soapClient->chapterDelete(array('id' => $id, 'remark' => $remark));
         return  $this->soapResultToJsonResult($soapResult);
    }

	/**
	 * 2.7.7.	章节根节点查询
	 * 接口地址	http://主机地址:端口号/service/chapterInfo?wsdl
	 * 方法名	chapterBaseNodeSearch
	 * @param $subject      科目
	 * @param $schoolLevel  学籍（小学\初中\高中等）
	 * @param $version      教材版本（人教版\北师大版等）
	 * @param $grade        年级（查询高中时可为空）
	 * @param $session      学期（21301 上学期，21302下学期）可为空
	 * @return array
	 * 查询失败	返回的JSON：
					{   "data":{},"resCode":"应答代码","resMsg":"应答描述"}
					应答代码和应答描述见《响应代码对照表》
		查询成功	返回的JSON示例：
				{
					"data": {
						"cPointList": [
						{"id":"1","pId":"0","name":"一年级（五四制）人教版"},
						{"id":"11","pId":"1","name":"第一章"},
						{"id":"12","pId":"1","name":"第二章"},
						{"id":"121","pId":"12","name":"定义"},
						{"id":"122","pId":"12","name":"性质"},
						],

						},
						"resCode": "000000",
						"resMsg": "成功"
				}

	 */

	public function chapterBaseNodeSearch($subject, $schoolLevel, $version, $grade, $session)
{
    $arr = array(
        'subject' => $subject,
        'schoolLevel' => $schoolLevel,
        'materialVersion' => $version,
        'grade' => $grade,
        'session' => $session
    );
    $soapResult = $this->_soapClient->chapterBaseNodeSearch($arr);
    $result = $this->soapResultToJsonResult($soapResult);
    if($result->resCode == self::successCode){
        return $result->data;
    }
    return array();
}

    public function chapterBaseNodeSearchList($subject, $schoolLevel, $version, $grade, $session)
    {
     $list=  $this->chapterBaseNodeSearch($subject, $schoolLevel, $version, $grade, $session);
        if ($list && isset($list->cPointList)){
            return ArrayHelper::map($list->cPointList, 'id', 'name');
        };
        return  array();
    }

}