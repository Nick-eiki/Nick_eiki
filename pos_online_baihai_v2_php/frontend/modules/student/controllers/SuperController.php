<?php
namespace frontend\modules\student\controllers;
use common\models\JsonMessage;
use frontend\components\StudentBaseController;
use frontend\services\apollo\Apollo_BaseInfoService;
use frontend\services\pos\pos_SuperStudentDiaryService;
use Yii;
use yii\data\Pagination;

/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2015/6/24
 * Time: 11:02
 */

class SuperController extends StudentBaseController {

    public $layout = "lay_user";

    /**
     *学霸养成记
     */
    public function actionCurveWrecker(){
        $server=new pos_SuperStudentDiaryService();
        $pages=new Pagination();
        $pages->pageSize=10;
        $superResult=$server->queryList(user()->id,$pages->getPage(),$pages->pageSize);
        $pages->totalCount=$superResult->countSize;
        $dataService=new Apollo_BaseInfoService();
        $dataResult=$dataService->getGrowupType();
        $typeArray=array();
        foreach($dataResult as $v){
          $typeArray[$v->key]=$v->value;
        }
        return $this->render("curveWrecker",array("typeArray"=>$typeArray,"superResult"=>$superResult,"pages"=>$pages));
    }

    /**
     * 获取记录列表分页
     * @throws CException
     */
    public function actionGetRecordList(){
        $currPage=app()->request->getQueryParam("page");
        $lastTime=app()->request->getQueryParam("lastTime");
        $server=new pos_SuperStudentDiaryService();
        $pages=new Pagination();
        $pages->pageSize=10;
        $superResult=$server->queryList(user()->id,$currPage,$pages->pageSize);
        $pages->totalCount=$superResult->countSize;
        return $this->renderPartial("_record_list",array("superResult"=>$superResult,"pages"=>$pages,"lastTime"=>$lastTime));
    }

    /**
     *AJAX记录学习成长
     */
    public function actionSetGrowupRecord(){
        $title=Yii::$app->request->post("title");
        $summary=Yii::$app->request->post("summary");
        $type=Yii::$app->request->post("type");
        $content=Yii::$app->request->post("content");
        $subjectID=Yii::$app->request->post("subjectID");
        $jsonResult=new JsonMessage();
        $server=new pos_SuperStudentDiaryService();
        $result=$server->createDiary(user()->id,$title,$subjectID,$type,$content,$summary);
        $jsonResult->message=$result->resMsg;
        return $this->renderJSON($jsonResult);
    }
}