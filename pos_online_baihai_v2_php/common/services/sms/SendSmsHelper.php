<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 15-7-3
 * Time: 下午1:28
 */

namespace common\services\sms;


use Yii;

class SendSmsHelper
{

    /**
     * @param $type   判断选用那种短信发送方式，One：云片；Two：云通讯   (注意大小写)
     * @param $code     生成的对应的验证码
     * @return mixed
     */
    public function sentFormat($type, $code, $phone)
    {

//        $class = 'SendSms'.$type.'Helper()';
//        $model = new $class;
        switch ($type) {
            case "One":
                $model = new SendSmsOneHelper();
                return $model->send_activeCode($phone, $code);
            case "Two":
                $model = new SendSmsTwoHelper();
                return $model->sendTemplateSMS($phone, $code, 86257);
        }
    }
}
