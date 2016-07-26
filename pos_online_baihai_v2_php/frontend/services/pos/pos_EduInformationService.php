<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/15
 * Time: 10:11
 */
class pos_EduInformationService extends BaseService
{
    /**
     *
     */
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/EduInformation?wsdl");
    }

    /**
     * 添加资讯信息
     * wgl 14-11-15
     * @
     * @param string $informationTitle	    资讯标题
     * @param string $informationType	    资讯类型
     * @param string $informationContent	    资讯内容
     * @param string $informationKeyWord	    资讯关键字
     * @param string $userID	                创建人id

     * token	安全保护措施
                    失败	返回的JSONB：参考响应代码
                    成功	返回的JSON示例：
                    "data":{
                    "InformationID":"10113"},
                    "resCode":"000000",
                    "resMsg":"添加成功！"
                    }
     */
    public function addEduInformation($informationTitle, $informationType, $informationContent, $informationKeyWord, $userID)
    {
        $soapResult = $this->_soapClient->addEduInformation(
            array(
                'informationTitle' => $informationTitle,
                'informationType' => $informationType,
                'informationContent' => $informationContent,
                'informationKeyWord' => $informationKeyWord,
                'userID' => $userID
            ));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 分页查询资讯信息(列表)
     * wgl 14-11-15 15:15:20
     *
     * @param string  informationID	资讯ID
     * @param string informationTitle	资讯标题
     * @param string informationType	资讯类型
     * @param string informationContent	资讯内容
     * @param string informationKeyWord	资讯关键字
     * @param string userID	创建人id
     * @param string btime	查询开始时间 （查询时间范围类发布的资讯）
     * @param string etime	查询结束时间
     * @param string currPage	当前页码
     * @param string pageSize	每页条数（从需求上看，该参数应设置为1）
     *
     * 	token	安全保护措施
    应答	    失败	返回的JSONB：参考响应代码
                成功	返回的JSON示例：
                        {
                        "data":{
                        "pageSize":"10",
                        "countSize":"1",
                        "list":
                        [{"informationID":1,
                        "informationTitle":"修改，____",
                        "informationType":"50103",
                        "informationContent":"修改内容",
                        "informationKeyWord":"",
                        "userID":1015,
                        "informationTypeName":"小升初",
                        "creatorName":"黄海"}],
                        "currPage":"1",
                        "listSize":1,
                        "totalPages":"1"},
                        "resCode":"000000",
                        "resMsg":"查询成功"
                        }
                        "resMsg":"成功"
                        }
     */
    public function queryEducInformation($informationID, $informationTitle, $informationType,$informationContent, $informationKeyWord, $userID, $btime, $etime, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->queryEducInformation(
            array(
                'informationID' => $informationID,
                'informationTitle' => $informationTitle,
                'informationType' => $informationType,
                'informationContent' => $informationContent,
                'informationKeyWord' => $informationKeyWord,
                'userID' => $userID,
                'btime' => $btime,
                'etime' => $etime,
                'currPage' => $currPage,
                'pageSize' => $pageSize
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 资讯详情
     * wgl 14-11-17
     *
     * @param string  informationID	资讯ID
     *
     * token	安全保护措施
                失败	返回的JSONB：参考响应代码
                成功	返回的JSON示例：
                {
                "data":{
                "informationListSize":1,
                "informationList":
                [{"userID":"50102",
                "informationContent":"修改内容",
                "informationTitle":"修改，____",
                "publishTime":"1411697048264",
                "informationType":"50103",
                "informationID":"1",
                "informationKeyWord":""}]},
                "resCode":"000000",
                "resMsg":"成功"
                }
     */
    public function queryEducInformationByid($informationID){
        $soapResult = $this->_soapClient->queryEducInformationByid(array("informationID"=>$informationID));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 修改资讯信息
     * wgl 14-11-17
     *
     * @param string  informationID	资讯ID
     * @param string informationTitle	资讯标题
     * @param string informationType	资讯类型
     * @param string informationContent	资讯内容
     * @param string informationKeyWord	资讯关键字
     * @param string userID	创建人id
                token	安全保护措施
                失败	返回的JSONB：参考响应代码
                成功	返回的JSON示例：
                        {
                        "data":{},
                        "resCode":"000000",
                        "resMsg":"修改成功！"
                        }
     */

    public function modifyEducInformation($informationID, $informationTitle, $informationType,$informationContent, $informationKeyWord, $userID)
    {
        $soapResult = $this->_soapClient->modifyEducInformation(
            array(
                'informationID' => $informationID,
                'informationTitle' => $informationTitle,
                'informationType' => $informationType,
                'informationContent' => $informationContent,
                'informationKeyWord' => $informationKeyWord,
                'userID' => $userID,
            ));
         return  $this->soapResultToJsonResult($soapResult);
    }
    /**
     * 添加评论
     * wgl 14-11-19
     * @param string commentContent    评论内容
     * @param string informationID	    资讯ID
     * @param string commentUserID	    评论发布人ID
     *token	    安全保护措施
                失败	返回的JSONB：参考响应代码
                成功	返回的JSON示例：
                {"data":{
                "commentID":"1017"},评论id
                "resCode":"000000",
                "resMsg":"添加成功！"}
     */
    public function commentAdd($commentContent,$informationID,$commentUserID,$commentType,$informationName)
    {
        $soapResult = $this->_soapClient->commentAdd(
            array(
                'commentContent' => $commentContent,
                'informationID' => $informationID,
                'commentUserID' => $commentUserID,
                'commentType' => $commentType,
                'informationName' => $informationName
            ));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.33.8.根据评论对象id分页查询评论信息以及该评论下的所有回复信息（2层结构方式展示）
     * 14-11-20 wgl
     *
     * @param string $informationID	    资讯ID
     * @param string $type  评论类型
     * @param string $currPage	开始页
     * @param string $pageSize	每页显示数量
     *
     * token	安全保护措施
       失败	  返回的JSONB：参考响应代码
       成功   返回的JSON示例：
                {"data":
                {"pageSize":"10",
                "countSize":"2","
                list":[{
                "commentID":1017," 评论id
                commentContent":"评论内容","
                commentTime":"1415935568801"," 评论发布时间
                commentUserID":1015,"  评论发布人id
                commentUserName":"评论发布人名称
                ","commenTitletName":"eeee" 回复资讯名称
                },],"currPage":"1","listSize":2,"totalPages":"1"},"resCode":"000000","resMsg":"查询成功"}
     *
     */

    public function searchCommentInformation($informationID,$type,$currPage,$pageSize){
        $soapResult = $this->_soapClient->searchCommentInformation(
            array(

                'informationID' => $informationID,
                'type' => $type,
                'currPage' => $currPage,
                'pageSize' => $pageSize,
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 3.33.9.根据评论人id查询评论信息，以及该评论下的所有回复（2层结构方式展示）
     * 接口地址	http://主机地址:端口号/ schoolService / EduInformation?wsdll
     * 方法名	searchCommentInformationByUse
     * 请求参数
     * @param   commentUserID	评论人ID
     * @param   currPage	开始页
     * @param   pageSize	每页显示数量
     *          token	安全保护措施
     * 应答	     失败	返回的JSONB：参考响应代码
    成功	     返回的JSON示例：
    返回的JSON示例：
    {"data":{"pageSize":"10",
    "countSize":"3",
    "list":
    [{"commentID":101126,"commentContent":"对课程的评论",
    "informationName":null,
    "commentType":50402,
    "commentTime":"2014-12-02 11:09:00",
    "commentUserID":1015,
    "commentUserName":"黄海",
    "subReplays":[{"replayID":10180,"replayContent":"对课程评论的回复",
    "replayTime":"2014-12-02 11:12:22",
    "isReport":"0",
    "replayUserID":100245,"replayUserName":"司机","replayTargetUserID":null,"replayTargetUserName":null},
    {"replayID":10181,"replayContent":"对10180进行回复","replayTime":"2014-12-02 11:20:37","isReport":"0","replayUserID":100283,"replayUserName":"瓦工","replayTargetUserID":100245,"replayTargetUserName":"司机"}]},
    {"commentID":1017,"commentContent":"评论内容","informationName":null,"commentType":50401,"commentTime":"2014-11-14 11:26:08","commentUserID":1015,"commentUserName":"黄海","subReplays":[{"replayID":10123,
    "replayContent":"回复内容",
    "replayTime":"2014-11-14 14:10:57",
    "isReport":"0",
    "replayUserID":10011,
    "replayUserName":"慕容冲",
    "replayTargetUserID":null,
    "replayTargetUserName":null},
    {"replayID":10124,
    "replayContent":"回复内容",
    "replayTime":"2014-11-14
    14:28:31",
    "isReport":"0","replayUserID":10019,"replayUserName":"温老师",
    "replayTargetUserID":10011,
    "replayTargetUserName":"慕容冲"}]},
    {"commentID":1016,"commentContent":"评论内容3","informationName":null,"commentType":50401,"commentTime":"2014-11-13 16:45:38","commentUserID":1015,"commentUserName":"黄海","subReplays":
    []}],"currPage":"1","listSize":3,"totalPages":"1"},"resCode":"000000","resMsg":"查询成功"}
     */
    public function searchCommentInformationByUse($commentUserID, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->searchCommentInformationByUse(
            array(
                'commentUserID' => $commentUserID,
                'currPage' => $currPage,
                'pageSize' => $pageSize
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 对评论进行回复
     * 14-11-20 wgl
     *
     * @param string commentID	评论ID
     * @param string replayContent	评论回复内容
     * @param string replayUserID	评论回复人ID
     * @param string replayTargetUserID	回复对象 用户ID
     * @param string replayType	回复类型  50401资讯回复  50402课程回复
     *
     * token	安全保护措施
        失败	    返回的JSONB：参考响应代码
        成功    返回的JSON示例：
                {"data":{
                "replayID":"10123"}, 回复id
                "resCode":"000000",
                "resMsg":"添加成功！"}
     */
    public function replyAdd($commentID, $replayContent, $replayUserID, $replayTargetUserID, $replayType){
        $soapResult = $this->_soapClient->replyAdd(
            array(
                'commentID' => $commentID,
                'replayContent' => $replayContent,
                'replayUserID' => $replayUserID,
                'replayTargetUserID' => $replayTargetUserID,
                'replayType' => $replayType
            ));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 通过评论id查询该评论下的所有回复信息
     * 14-11-20
     *
     * 因页面问题功能暂时
     *
     * @param string commentid  评论id
     *
     * token	安全保护措施
        失败	     返回的JSONB：参考响应代码
        成功	     返回的JSON示例：
                {"data":
                {"replayListSize":2,"replayList":[
                {"replaytUserName":"温老师",   回复人名称
                "replayContent":"回复内容",
                "replayID":"10124",            回复ID
                "preplayid":"10123",           回复对象回复id
                "commentID":"1017",            所属评论id
                "replayTime":"1415946511773",  回复时间
                "replayUserID":"10019"},
                ]},"resCode":"000000","resMsg":"成功"}
     *
     */

    public function queryReplayByCommentid($commentid){
        $soapResult = $this->_soapClient->queryReplayByCommentid(
            array(
                'commentid' => $commentid,
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 删除评论
     *
     * @param string commentid  评论id
     *
     * token	安全保护措施
        失败	     返回的JSONB：参考响应代码
        成功	     返回的JSON示例：
                    {"data":{},
                    "resCode":"000000",
                    "resMsg":"删除成功！"
                    }
     */

    public function commentDelete($commentID){
        $soapResult = $this->_soapClient->commentDelete(
            array(
                'commentID' => $commentID,
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();

    }

    /**
     * 举报评论
     * 14-11-21 wgl
     *
     * @param string replayID	回复ID
     *
     * token	安全保护措施
        失败	     返回的JSONB：参考响应代码
        成功	     返回的JSON示例：
                {"data":{},
                "resCode":"000000",
                "resMsg":"评论举报成功！"
                }
     */

    public function commentReport($commentID)
    {
        $soapResult = $this->_soapClient->commentReport(
            array(
                'commentID' => $commentID,
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if($result->resCode == self::successCode)
        {
            return $result->data;
        }
        return array();
    }

    /**
     * 删除回复
     *
     * @param replayID 回复ID
     *
     *失败	返回的JSONB：参考响应代码
        成功  	返回的JSON示例：
                {"data":{},
                "resCode":"000000",
                "resMsg":"删除成功！"
                }
     */
    public function replyDelete($replayID){
        $soapResult = $this->_soapClient->replyDelete(
            array(
                'replayID' => $replayID,
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 举报回复
     * @param replayID 回复ID
     *
     * 失败	 返回的JSONB：参考响应代码
        成功  	返回的JSON示例：
             {"data":{},
                "resCode":"000000",
                "resMsg":"删除成功！"
             }
     */
    public function replayReport($replayID)
    {
        $soapResult = $this->_soapClient->replayReport(
            array(
                'replayID' => $replayID,
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if($result->resCode == self::successCode)
        {
            return $result->data;
        }
        return array();
    }

    /**
     * 对回复进行回复
     *
     * @PARAM   preplayID	被回复的回复ID
     * @PARAM   replayContent	回复内容
     * @PARAM   commentID	评论ID
     * @PARAM   replayUserID	回复人ID
     * @PARAM   replayTargetUserID	回复目标人ID
     * @PARAM   replayType	回复类型  50401资讯回复  50402课程回复
     *
     * token	安全保护措施
        失败	     返回的JSONB：参考响应代码
        成功	     返回的JSON示例：
                {"data":{
                "replayID":"10124"}, 回复id
                "resCode":"000000",
                "resMsg":"添加成功！"}
     */

    public function preplyAdd($preplayID,$replayContent,$commentID,$replayUserID,$replayTargetUserID,$replayType)
    {
        $soapResult = $this->_soapClient->preplyAdd(
            array(
                'preplayID' => $preplayID,
                'replayContent' => $replayContent,
                'commentID' => $commentID,
                'replayUserID' => $replayUserID,
                'replayTargetUserID' => $replayTargetUserID,
                'replayType' => $replayType
            ));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.32.8.根据咨询id查询上一条记录详情
     * 方法名	queryPreviousPageByid
     * 请求参数
     *          @PARAM $informationID    资讯ID
     *          @PARAM $userID
     *                          token	安全保护措施
     * 应答	                     失败   返回的JSONB：参考响应代码
     *                           成功	返回的JSON示例：
                                        {
                                         "data":{
                                        "informationListSize":1,
                                        "informationList":
                                        [{"userID":"50102",
                                        "informationContent":"资讯内容",
                                        "informationTitle":"资讯标题_",
                                        "publishTime":"1411697048264", 发布时间
                                        "informationType":"50103",   资讯类型
                                        "informationID":"1",   资讯id
                                        "informationKeyWord":""}]},  关键字
                                        "resCode":"000000",
                                        "resMsg":"成功"
                                        }
     */
    public function queryPreviousPageByid($informationID,$userid){
        $soapResult = $this->_soapClient->queryPreviousPageByid(array("informationID"=>$informationID,'userid'=>$userid));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.32.7.根据咨询id查询下一条记录详情
     * 方法名	queryNextPageByid
     * 请求参数
     *          @PARAM $informationID    资讯ID
     *          @PARAM $userid
     *                          token	安全保护措施
     * 应答	                     失败   返回的JSONB：参考响应代码
     *                           成功	返回的JSON示例：
                                        {
                                        "data":{
                                        "informationListSize":1,
                                        "informationList":
                                        [{"userID":"50102",
                                        "informationContent":"资讯内容",
                                        "informationTitle":"资讯标题_",
                                        "publishTime":"1411697048264", 发布时间
                                        "informationType":"50103",   资讯类型
                                        "informationID":"1",   资讯id
                                        "informationKeyWord":""}]},  关键字
                                        "resCode":"000000",
                                        "resMsg":"成功"
                                        }
     */
    public function queryNextPageByid($informationID,$userid){
        $soapResult = $this->_soapClient->queryNextPageByid(array("informationID"=>$informationID,'userid'=>$userid));
         return  $this->soapResultToJsonResult($soapResult);
    }


}