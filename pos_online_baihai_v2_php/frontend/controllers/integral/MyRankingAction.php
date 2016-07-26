<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/8/19
 * Time: 16:35
 */
namespace frontend\controllers\integral;
use common\services\JfManageService;
use yii\base\Action;
use yii\web\HttpException;

class MyRankingAction extends Action{
    public function run(){
        $userid = user()->id;
        $user= loginUser();
        if(!$user){
            throw new HttpException(404, 'The requested page does not exist.');
        }
        $jfManageHelperModel=new JfManageService();
        $grad = $jfManageHelperModel->JfGrade($userid);

        return $this->controller->render('@app/views/publicView/integral/myRanking',['grad'=>$grad,'user'=>$user]);
    }

}
?>