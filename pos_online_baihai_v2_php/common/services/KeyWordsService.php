<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 2015/8/6
 * Time: 11:32
 */

namespace common\services;


use Httpful\Mime;
use Httpful\Request;
use Yii;
use yii\base\Exception;

class KeyWordsService {

    private $Uri = null;

    function __construct(){
       $this->Uri = Yii::$app->params['keyWords']."/v1/key-words";
    }

    /**
     * @param $string
     * 判断字符串是否有敏感字符
     */
    protected   function judgeText($string){
       $result= Request::post($this->Uri)
            ->body(http_build_query(["text"=>$string]))
             ->sendsType(Mime::FORM)
            ->send();
        return    $result->body;
    }

    /**
     * @param $string
     * 替换字符串内的敏感字符
     */
    protected  function ReplaceText($string){

        try{
            $result= Request::post($this->Uri)
                ->body(http_build_query(["text"=>$string,"method"=>"replace"]))
                ->sendsType(Mime::FORM)
                ->send();
            return    $result->body;
        }catch (Exception $e){
            return $string;
        }


    }


    public  static  function  ReplaceKeyWord($string){
      $self=  new Self();
      return  $self->ReplaceText($string);


    }



}
?>