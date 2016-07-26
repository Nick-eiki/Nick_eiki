<?php
/**
 * Created by wangchunlei
 * User: mahongru
 * Date: 2015/9/11
 * Time: 10:15
 */
namespace frontend\controllers\integral;

use common\services\JfManageService;
use yii\base\Action;
use yii\web\HttpException;

class IntegralExchangeAction extends Action
{
    /**
     * @return string
     * @throws HttpException
     */
    public function run()
    {
        //累计积分
        $user = loginUser();

        if(!$user){
            throw new HttpException(404, '用户不存在');
        }
        $jfManageHelperModel=new JfManageService();
        $goods = $jfManageHelperModel->Goods($user->type);

        return $this->controller->render("@app/views/publicView/integral/integralExchange", ['user' => $user,'goods' => $goods]);
    }
}

?>