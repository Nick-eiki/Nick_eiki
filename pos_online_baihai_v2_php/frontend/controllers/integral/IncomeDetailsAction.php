<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/8/19
 * Time: 15:31
 */
namespace frontend\controllers\integral;
use common\services\JfManageService;
use yii\base\Action;
use yii\data\Pagination;
use yii\web\HttpException;

class IncomeDetailsAction extends Action {
    /**
     * @return string
     * @throws HttpException
     */
    public function run(){
        //累计积分
        $userid = user()->id;
        $user= loginUser();

        if(!$user){
            throw new HttpException(404, 'The requested page does not exist.');
        }
        $jfManageHelperModel=new JfManageService();
        //总积分和可用积分
        $userScore=$jfManageHelperModel->UserScore($userid);
        $points=$userScore->points;
        $totalPonits=$userScore->totalPoints;
        // 积分明细
        $pages = new Pagination();
        $pages->validatePage=false;
        $pages->pageSize = 50;
        $restResult=$jfManageHelperModel->Points($userid,$pages->getPage() + 1,$pages->pageSize);
        $model=$restResult->items;
        $pages->totalCount=$restResult->_meta->totalCount;
      if (app()->request->isAjax) {
            return $this->controller->renderPartial("@app/views/publicView/integral/_income_list",
                ['pages'=>$pages,
                 'points'=>$points,
                 'totalPonits'=>$totalPonits,
                 'model'=>$model,
                ]);

        }
        return $this->controller->render("@app/views/publicView/integral/incomeDetails",
            ['pages'=>$pages,
             'points'=>$points,
             'totalPonits'=>$totalPonits,
             'model'=>$model,
             'user'=>$user,
            ]);
    }
}
?>