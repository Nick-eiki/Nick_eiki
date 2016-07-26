<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-20
 * Time: 下午1:37
 */
class pos_FavoriteFolderService extends BaseService{
    function __construct(){
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService']."schoolService/favoriteFolder?wsdl");
    }

    /**
     * 3.23.1.添加收藏夹
     * 接口地址	http://主机地址:端口号/ /schoolService/favoriteFolder?wsdl
     * 方法名	addFavoriteFolder
     * @param $favoriteId   收藏内容id（不为空）
     * @param $favoriteType 收藏类型(收藏类型(1教案，2讲义，3视频,4 资料，5 ppt，6 素材))
     * @param $userId       收藏夹创建人id
     * @param $token        用于安全控制，暂时为空
     * "resCode":"000000",
    "resMsg":"成功",
    "data":{
    }
     * @return ServiceJsonResult
     */
    public function addFavoriteFolder($favoriteId,$favoriteType,$userId){
        $soapResult = $this->_soapClient->addFavoriteFolder(array("favoriteId" => $favoriteId,'favoriteType'=>$favoriteType,'userId'=>$userId));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.23.2.删除收藏
     * 接口地址	http://主机地址:端口号/ /schoolService/favoriteFolder?wsdl
     * 方法名	delFavoriteFolder
     * @param $collectID       收藏内容id
     *"resCode":"000000",
    "resMsg":"成功",
    "data":{
    }
     * @return ServiceJsonResult
     */
    public function delFavoriteFolder($collectID){
        $soapResult = $this->_soapClient->delFavoriteFolder(array("collectID" =>$collectID));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.23.3.查询收藏夹
     * 接口地址	http://主机地址:端口号/ /schoolService/favoriteFolder?wsdl
     *方法名	queryFavoriteFolder
     * @param $userID       收藏夹创建人id
     * @param $favoriteType 收藏类型(0：讲义1：视频2：网址3：其他)
     * @param $token        用于安全控制，暂时为空
     *
     * "resCode":"000000",
    "resMsg":"查询成功",
    "data":{
    "currPage":"当前页码",
    "totalPages":"总页数",
    "countSize":"总记录数",
    "pageSize":"每页数据的条数",
    "list":[//列表
    {"collectID":"",//收藏id
    "favoriteId":"",//收藏内容id
    "headLine":"",//标题
    "brief":"",//简介
    "favoriteType":"",//收藏类型(0：讲义
    1：视频2：网址3：其他)
    " creatorID ":"",//收藏夹创建人id
    " createTime ":""//创建时间
    },
    ...
    ]
    }
     * @return null
     */
    public function queryFavoriteFolder($userID,$favoriteType,$currPage,$pageSize){
        $soapResult = $this->_soapClient->queryFavoriteFolder(array("userID" => $userID,'favoriteType'=>$favoriteType,'currPage'=>$currPage,'pageSize'=>$pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 3.23.4.其他用户查询收藏夹
     * 接口地址	http://主机地址:端口号/ /schoolService/favoriteFolder?wsdl
     * 方法名	otherQueryFavoriteFolder
     * @param $ownerUserID      收藏夹创建人id
     * @param $favoriteType     收藏类型(1教案，2讲义，3视频,4 资料，5 ppt，6 素材)
     * @param $otherUserID      其他查看用户 根据查看用户，查询该资料是否收藏  isCollected 0未收藏 1收藏
     * @param $currPage         当前显示页码，可以为空,默认值1
     * @param $pageSize         每页显示的条数，可以为空，默认值10
     * "resCode":"000000",
    "resMsg":"查询成功",
    "data":{
    "currPage":"当前页码",
    "totalPages":"总页数",
    "countSize":"总记录数",
    "pageSize":"每页数据的条数",
    "list":[//列表
    {"collectID":"",//收藏id
    "favoriteId":"",//收藏内容id
    "headLine":"",//标题
    "brief":"",//简介
    "url":"",//链接
    "favoriteType":"",//收藏类型(0：讲义
    1：视频2：网址3：其他)
    " creatorID ":"",//收藏夹创建人id
    " createTime ":""//创建时间
    “isCollected“:"" 0未收藏 1收藏
    “otherCollectID “:"" 查看用户收藏id
    },
    ...
    ]
    }
     *
     * @return array
     */
    public function otherQueryFavoriteFolder($ownerUserID,$favoriteType,$otherUserID,$currPage,$pageSize){
        $soapResult = $this->_soapClient->otherQueryFavoriteFolder(array("ownerUserID" => $ownerUserID,'favoriteType'=>$favoriteType,'otherUserID'=>$otherUserID,'currPage'=>$currPage,'pageSize'=>$pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 3.23.5.查询资料是否收藏
     * 接口地址	http://主机地址:端口号/ /schoolService/favoriteFolder?wsdl
     * 方法名	queryHasFavorite
     * @param $favoriteId   收藏内容id（不为空）
     * @param $favoriteType 收藏类型(收藏类型(1教案，2讲义，3视频,4 资料，5 ppt，6 素材))
     * @param $userID       收藏人
     * "resCode":"000000",
    "resMsg":"查询成功",
    "data":{
    “isCollected“:"" 0未收藏 1收藏
    “collectID“:"" 收藏id
    }
     * @return ServiceJsonResult
     */
    public function queryHasFavorite($favoriteId,$favoriteType,$userID){
        $soapResult = $this->_soapClient->queryHasFavorite(array("favoriteId"=>$favoriteId,"favoriteType" =>$favoriteType,"userID"=>$userID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 3.23.2.按内容删除收藏
     * 接口地址	http://主机地址:端口号/ schoolService/favoriteFolder?wsdl
     * 方法名	delFavoriteFolderByDtl
     * @param $favoriteId   收藏内容id（不为空）
     * @param $favoriteType 收藏类型(收藏类型(1教案，2讲义，3视频,4 资料，5 ppt，6 素材,7 直播课程))
     * @param $userId       收藏夹创建人id
     * {
    "resCode":"000000",
    "resMsg":"成功",
    "data":{
    }
    }
     * @return ServiceJsonResult
     */
    public function delFavoriteFolderByDtl($favoriteId,$favoriteType,$userId){
        $soapResult = $this->_soapClient->delFavoriteFolderByDtl(array("favoriteId" =>$favoriteId,"favoriteType"=>$favoriteType,"userId"=>$userId));
         return  $this->soapResultToJsonResult($soapResult);
    }
}