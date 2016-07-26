<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 15-7-3
 * Time: 下午1:28
 */

namespace common\services\sms;


use Yii;
use yii\log\Logger;

class SendSmsOneHelper
{

    public function  send_activeCode($moblie, $code)
    {
        $text = "【班海平台】欢迎使用，您的手机验证码是{$code}。本条信息无需回复。";
        $message = $this->send_sms($text, $moblie);
        Yii::getLogger()->log($message, Logger::LEVEL_ERROR,'sms');
        $result = json_decode($message);
        return $result->code == 0;

    }

    function send_sms($text, $mobile)
    {
        $url = "http://yunpian.com/v1/sms/send.json";
        $encoded_text = urlencode("$text");
        $post_string = "apikey=8ab42979c57df7e59093946d715eadf3&text=$encoded_text&mobile=$mobile";
        return $this->sock_post($url, $post_string);
    }


    /**
     * url 为服务的url地址
     * query 为请求串
     */
    function sock_post($url, $query)
    {
        $data = "";
        $info = parse_url($url);
        $fp = fsockopen($info["host"], 80, $errno, $errstr, 30);
        if (!$fp) {
            return $data;
        }
        $head = "POST " . $info['path'] . " HTTP/1.0\r\n";
        $head .= "Host: " . $info['host'] . "\r\n";
        $head .= "Referer: http://" . $info['host'] . $info['path'] . "\r\n";
        $head .= "Content-type: application/x-www-form-urlencoded\r\n";
        $head .= "Content-Length: " . strlen(trim($query)) . "\r\n";
        $head .= "\r\n";
        $head .= trim($query);
        $write = fputs($fp, $head);
        $header = "";
        while ($str = trim(fgets($fp, 4096))) {
            $header .= $str;
        }
        while (!feof($fp)) {
            $data .= fgets($fp, 4096);
        }
        return $data;
    }

}