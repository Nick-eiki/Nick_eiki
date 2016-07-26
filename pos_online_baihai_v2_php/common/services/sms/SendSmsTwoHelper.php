<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 15-7-3
 * Time: 下午1:28
 */

namespace common\services\sms;

use Yii;

class SendSmsTwoHelper
{

    public function sendTemplateSMS($to,$code,$tempId)
    {

        // 说明：主账号，登陆云通讯网站后，可在"控制台-应用"中看到开发者主账号ACCOUNT SID。
        $accountSid= '8a48b55152f73add01532bd26f7f5bec';

        //说明：主账号Token，登陆云通讯网站后，可在控制台-应用中看到开发者主账号AUTH TOKEN。
        $accountToken= '12517f09e5f34e6e803e7abf6c5724bc';

        //说明：应用Id，如果是在沙盒环境开发，请配置"控制台-应用-测试DEMO"中的APPID。如切换到生产环境，请使用自己创建应用的APPID。
        $appId='aaf98f8953b303c10153c02750a1128a';

        //说明：请求地址。
        //沙盒环境配置成sandboxapp.cloopen.com，
        //生产环境配置成app.cloopen.com。
        $serverIP='app.cloopen.com';

        //说明：请求端口 ，无论生产环境还是沙盒环境都为8883.
        $serverPort='8883';

        $softVersion='2013-12-26';

        // 初始化REST SDK
        //global $accountSid, $accountToken, $appId, $serverIP, $serverPort, $softVersion;
        $rest = new CCPRestSmsSDK($serverIP, $serverPort, $softVersion);
        $rest->setAccount($accountSid, $accountToken);
        $rest->setAppId($appId);


        // 发送模板短信
        //$datas = "欢迎使用【班海平台】，您的手机验证码是{$code}。本条信息无需回复。<br />";
        $datas = ['班海' , $code];
        $result = $rest->sendTemplateSMS($to, $datas, $tempId);

        if ($result == NULL) {
            echo "result error!";
        }
        if ($result->statusCode != 0) {
            return false;       //模板短信发送失败
        } else {
          return true;          //模板短信发送成功
        }
    }

}