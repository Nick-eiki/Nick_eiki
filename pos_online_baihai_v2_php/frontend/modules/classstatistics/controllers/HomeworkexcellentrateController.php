<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/2/22
 * Time: 16:47
 */

namespace frontend\modules\classstatistics\controllers;

use common\models\pos\SeHomeworkClassReport;
use frontend\components\ClassesBaseController;
use frontend\models\dicmodels\SubjectModel;
use Yii;

class HomeworkexcellentrateController extends ClassesBaseController
{
    public $layout = '@app/views/layouts/lay_new_classstatistic_v2';

    public function actionIndex($classId)
    {
        $this->getClassModel($classId);

        $classCreateYear = 2014;
        $nowYear = date("Y",time());
        $years = [];
        for($i=$classCreateYear ; $i<=$nowYear ;$i++){
            $years[] = $i;
        }

        $months = [1,2,3,4,5,6,7,8,9,10,11,12];

        if($_POST){

            $year = app()->request->post('year');
            $month = app()->request->post('month');

            $firstDay=date('Y-m-01 00:00:01', strtotime($year."-".$month."-01"));
            $lastDay = date('Y-m-d 23:59:59', strtotime("$firstDay +1 month -1 day"));

            $classHomeworkReport = SeHomeworkClassReport::find()->where(['classId'=>$classId])
                ->andWhere(['between', 'generateTime', $firstDay, $lastDay])
                ->all();

        }else{

            $currentYear = date("Y",time());
            $currentMonth = date("m", strtotime("-1 month"));
            $nowFirstDay=date('Y-m-01 00:00:01', strtotime($currentYear."-".$currentMonth."-01"));
            $nowLastDay = date('Y-m-d 23:59:59', strtotime("$nowFirstDay +1 month -1 day"));

            $classHomeworkReport = SeHomeworkClassReport::find()->where(['classId'=>$classId])
                ->andWhere(['between', 'generateTime', $nowFirstDay, $nowLastDay])
                ->all();

        }

        $data = [];
        $subjectArr = [];
        $excellentArr = [];
        $goodArr = [];
        $middleArr = [];
        $badArr = [];
        foreach($classHomeworkReport as $report){
            $subjectArr[] = SubjectModel::model()->getSubjectName($report->subjectId);
            $excellentArr[] = $report->excellentNum;
            $goodArr[] = $report->goodNum;
            $middleArr[] = $report->middleNum;
            $badArr[] = $report->badNum;
        }
        array_push($data ,$excellentArr,$goodArr,$middleArr,$badArr );

        if(app()->request->isAjax){
            return $this->renderPartial('_index_info',['subjectArr'=>$subjectArr ,'data'=>$data]);
        }


        return $this->render('index',['classId'=>$classId ,'years'=>$years,'months'=>$months , 'subjectArr'=>$subjectArr ,'data'=>$data ]);

    }






}