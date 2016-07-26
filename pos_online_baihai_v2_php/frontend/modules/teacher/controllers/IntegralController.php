<?php
namespace frontend\modules\teacher\controllers;
use frontend\components\TeacherBaseController;

/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2015/8/19
 * Time: 15:14
 */
class IntegralController extends TeacherBaseController{
    public $layout="lay_user";
    public function actions(){
        return [
            'income-details'=>['class'=>'frontend\controllers\integral\IncomeDetailsAction'],
            'my-ranking'=>[ 'class'=>'frontend\controllers\integral\MyRankingAction' ],
            'integral-exchange'=>[ 'class'=>'frontend\controllers\integral\IntegralExchangeAction'],

        ];
    }
}
?>