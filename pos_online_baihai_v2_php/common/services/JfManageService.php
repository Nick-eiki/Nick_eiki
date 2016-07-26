<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/6
 * Time: 17:40
 */

namespace common\services;


use Httpful\Mime;
use Httpful\Request;
use Yii;

class JfManageService {

    private $uri = null;

    function __construct(){
        $this->uri = Yii::$app->params['keyWords'];
    }

    /**
     * @param $string
     * 积分管理
     */
    public  function myAccount($ruleCode,$userID){
        $result= Request::post($this->uri."/v1/current-accounts")
            ->body(http_build_query(["rule_code"=>$ruleCode,"userID"=>$userID]))
            ->sendsType(Mime::FORM)
            ->send();
        return    $result->body;
    }

    //积分获取
    public  function Points($userID,$page=1,$pageSize=50){
             $result= Request::get( $this->uri."/v1/jf-currents?".http_build_query(["userID"=>$userID,'page'=>$page,'per-page'=>$pageSize,'sort'=>'-id_current']))
             ->expectsType(Mime::JSON)
            ->send();
        return $result->body;
    }
    /**
     * @param $string
     * 签到
     */
    public  function Sign($userID){
        $result= Request::post($this->uri."/v1/signs")
            ->body(http_build_query(["userID"=>$userID]))
            ->contentType('')
            ->sendsType(Mime::FORM)
            ->send();
        return    $result->body;
    }
    /**
     * @param $string
     * 查看签到
     */
    public  function checkSign($userID){
        $result= Request::get($this->uri."/v1/signs?".http_build_query(["userID"=>$userID]))
            ->send();
        return    $result->body;
    }

    /**
     * @param $string
     * 积分等级
     */
    public  function JfGrade($userID){
        $result= Request::get($this->uri."/v1/jf-grades?".http_build_query(["userID"=>$userID]))
            ->contentType('')
            ->sendsType(Mime::FORM)
            ->send();
        return    $result->body;
    }

    /**
     * @param $string
     * 积分兑换
     */
    public  function JfExchange($userID , $goodsId){
        $result= Request::post($this->uri."/v1/jf-exchanges")
            ->body(http_build_query(["userID"=>$userID,"goodsId"=>$goodsId]))
            ->contentType('')
            ->sendsType(Mime::FORM)
            ->send();
        return    $result->body;
    }


    //用户总积分和可用积分
    public  function UserScore($userID){
        $result= Request::get( $this->uri."/v1/user-accounts?".http_build_query(["userID"=>$userID]))
            ->expectsType(Mime::JSON)
            ->send();
        return $result->body;
    }


    //用户获取当日积分
    public  function UserDayScore($userID){
        $result= Request::get( $this->uri."/v1/user-currents?".http_build_query(["userID"=>$userID]))
            ->expectsType(Mime::JSON)
            ->send();
        return $result->body;
    }



    //商品列表
    public  function Goods($type){
        $result= Request::get( $this->uri."/v1/jf-goods?".http_build_query(["type"=>$type]))
            ->expectsType(Mime::JSON)
            ->send();
        return $result->body;
    }


} 