<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 15-6-25
 * Time: 下午12:05
 */

namespace common\helper;


class StringHelper
{


    static function isEmpty($value)
    {
        return $value === '' || $value === [] || $value === null || is_string($value) && trim($value) === '';
    }
//图片路径的替换
    public static  function  replacePath($content){
        if(preg_match("/<img.*>/",$content))
        {$img=preg_replace("/src=\"\//","src=\"http://www.banhai.com/",$content);return $img;}else{
            return $content;
        }
    }
}