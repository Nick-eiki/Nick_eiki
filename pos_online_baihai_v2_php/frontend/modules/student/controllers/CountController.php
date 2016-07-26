<?php
namespace frontend\modules\student\controllers;
use frontend\components\StudentBaseController;
use frontend\models\dicmodels\SubjectModel;
use frontend\services\pos\pos_StudyRecordService;
use frontend\services\pos\pos_SuperManageService;
use yii\data\Pagination;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-3
 * Time: 上午10:43
 */
class CountController extends StudentBaseController
{
    public $layout = "lay_user";
    /*
     * 学生学生记录 （学习时间轴）
     */
    public  function  actionStudyTimeLine(){
       //
        if (app()->request->isAjax){
            $pages = new Pagination();$pages->validatePage=false;
            $pages->pageSize = 10;
            $uid = user()->id;
            $obj = new pos_StudyRecordService();
            $list = $obj->queryStudentStudyRecord($uid,$pages->getPage()+1, $pages->pageSize);
            $pages->totalCount = intval($list->countSize);
            return $this->renderPartial('_studyline',array('list'=>$list,'pages'=>$pages));

        }
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        $uid = user()->id;
        $obj = new pos_StudyRecordService();
        $list = $obj->queryStudentStudyRecord($uid,$pages->getPage()+1, $pages->pageSize);
		$pages->totalCount = intval($list->countSize);
        return $this->render('studytimeline',array('list'=>$list,'pages'=>$pages));
    }
    /**
     *作业统计
     */
    public function actionTaskScale()
    {
        $superServer=new pos_SuperManageService();
        $totalResult=$superServer->stuScoreStastic(user()->id);
//      折线图横轴考试名称的获取
        $examNameArray=array();
        foreach($totalResult->list as $v){
            array_push($examNameArray,$v->examName);
        }
//        折线图纵轴分数的获取
        $totalScoreArray=array();
        foreach($totalResult->list as $v){
            array_push($totalScoreArray,$v->totalScore);
        }
        $subjectArray= SubjectModel::model()->getData();
        if(app()->request->getQueryParam("subjectID")!=""){

        }
        return $this->render("taskScale",array("examNameJson"=>json_encode($examNameArray),
                    "totalScoreJson"=>json_encode($totalScoreArray),
                     "subjectArray"=>$subjectArray,
        ));
    }

    /**
     *单科分数折线图片段
     */
    public function actionSubjectScore(){
        $superServer=new pos_SuperManageService();
        $totalResult=$superServer->stuScoreStastic(user()->id);
//      折线图横轴考试名称的获取
        $examNameArray=array();
        foreach($totalResult->list as $v){
            array_push($examNameArray,$v->examName);
        }
        $subjectID=app()->request->getQueryParam("subjectID");
        $subjectResult=$superServer->stuScoreStastic(user()->id,$subjectID);
        $subjectScoreArray=array();
        foreach($subjectResult->list as $v){
            array_push($subjectScoreArray,isset($v->subScore[0])?$v->subScore[0]->personalScore:0);
        }
        return $this->renderPartial("subject_score",array(
            "examNameJson"=>json_encode($examNameArray),
            "subjectScoreJson"=>isset($subjectScoreArray)?json_encode($subjectScoreArray):json_encode(array()),
        ));
    }

    /**
     *测验的成绩变动
     */
    public function actionTestScale(){
        $superServer=new pos_SuperManageService();
        $scoreResult=$superServer->stuTestScore(user()->id);
//        折线图横轴测验名称的获取
        $testNameArray=array();
        foreach($scoreResult->list as $v){
            array_push($testNameArray,$v->testName);
        }
//        折线图纵轴测验分数的获取
        $testScoreArray=array();
        foreach($scoreResult->list as $v){
            array_push($testScoreArray,$v->testScore?:"");
        }
        return $this->render("testScale",array("testNameJson"=>json_encode($testNameArray),
            "testScoreJson"=>json_encode($testScoreArray)
        ));
    }

    /**
     *作业完成比例和题目正确比
     */
    public function actionTaskCompleteScale(){
        $superServer=new pos_SuperManageService();
//        作业完成比例
        $taskResult=$superServer->stuHomeworkStasticCmp(user()->id);
        if ($taskResult==null){
            $taskDataArray=array(
                array("value"=> 0,"name"=>"完成"),
                array("value"=>1,"name"=>"未完成")
            );
        }else {
            $taskDataArray=array(
                array("value"=> $taskResult->completeCnt,"name"=>"完成"),
                array("value"=>$taskResult->allCnt-$taskResult->completeCnt,"name"=>"未完成")
            );
        }


        return $this->render("taskCompleteScale",array("taskDataJson"=>json_encode($taskDataArray),

        ));
    }
}