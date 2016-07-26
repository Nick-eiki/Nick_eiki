<?php
namespace console\controllers;

use common\helper\DateTimeHelper;
use common\models\pos\SeClassMembers;
use common\models\pos\SeHomeworkAnswerInfo;
use common\models\pos\SeHomeworkClassReport;
use common\models\pos\SeHomeworkRel;
use Yii;
use yii\console\Controller;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/7
 * Time: 10:48
 */
class ClassReportController extends Controller
{

    /**
     *生成班级作业报表数据
     */
    public function actionCreateReport()
    {

        $nowDate = Date('Y-m', time());

        $thisDate = $nowDate . '-01';

        $lastDate = date('Y-m-d', strtotime("$thisDate-1 month"));

        $thisDate = date('Y-m-d 23:59:59', strtotime("$thisDate-1 day"));

        $lastTime = strtotime($lastDate) * 1000;

        $thisTime = strtotime($thisDate) * 1000;

        $relHomeworkResult = SeHomeworkRel::find()->where(['>', 'se_homework_rel.createTime', $lastTime])->andWhere(['<', 'se_homework_rel.createTime', $thisTime])->innerJoinWith('homeWorkTeacher')->orderBy('se_homework_rel.classID,se_homework_teacher.subjectId')->all();

        $dataArray = $this->ManageData($relHomeworkResult);

        $this->insterDate($lastDate, $dataArray);

    }

    /**
     *生成班级作业报表历史数据
     */
    public function actionCreateHisReport()
    {
        $dataArray = ['2015-01', '2015-02', '2015-03', '2015-04', '2015-05', '2015-06', '2015-07', '2015-08', '2015-09', '2015-10', '2015-11', '2015-12', '2016-01', '2016-02', '2016-03', '2016-04'];
        foreach ($dataArray as $v) {
            $dateArray = explode('-', $v);
            $year = $dateArray[0];
            $month = $dateArray[1];
            $lastDate = date('Y-m', strtotime($year . '-' . ($month - 1)));
            $lastTime = strtotime($lastDate) * 1000;
            $thisTime = strtotime($v) * 1000;
            $relHomeworkResult = SeHomeworkRel::find()->where(['>', 'se_homework_rel.createTime', $lastTime])->andWhere(['<', 'se_homework_rel.createTime', $thisTime])->innerJoinWith('homeWorkTeacher')->orderBy('se_homework_rel.classID,se_homework_teacher.subjectId')->all();
            $dataArray = $this->ManageData($relHomeworkResult);
            $this->insterDate($lastDate, $dataArray);
        }
    }

    /**
     * 根据se_homework_rel和se_homework_teacher 查出的结果变成需要的数据处理结果
     * @param $relHomeworkResult
     * @param $reportArray
     * @return array
     */
    public function ManageData($relHomeworkResult)
    {
        $reportArray = [];
        $classID = 0;
        foreach ($relHomeworkResult as $k => $v) {
            if ($v->classID != $classID) {
                if (isset($classArray)) {
                    array_push($reportArray, $classArray);
                }
                $classArray = [];
                $classArray[] = $v;
                $classID = $v->classID;
            } else {
                $classArray[] = $v;
            }
            if ($k == count($relHomeworkResult) - 1) {
                array_push($reportArray, $classArray);
            }
        }
        $subjectId = 0;
        $dataArray = [];
        foreach ($reportArray as $v) {

            $relArray = [];
            $memberTotalArray = [];
            foreach ($v as $key => $value) {
                $classID = $value->classID;
                if ($subjectId != $value['homeWorkTeacher']->subjectId) {
                    if (!empty($relArray)) {
                        array_push($dataArray, ['generateTime' => date('Y-m-d H:i:s', DateTimeHelper::timestampDiv1000($v[$key - 1]->createTime)), 'classID' => $v[$key - 1]->classID, 'subjectID' => $v[$key - 1]['homeWorkTeacher']->subjectId, 'relID' => $relArray, 'memberTotal' => $memberTotalArray]);
                    }
                    $relArray = [];
                    $memberTotalArray = [];
                    $subjectId = $value['homeWorkTeacher']->subjectId;
                }
                array_push($relArray, $value->id);
                array_push($memberTotalArray, $value->memberTotal);
                if ($key == count($v) - 1) {
                    array_push($dataArray, ['generateTime' => date('Y-m-d H:i:s', DateTimeHelper::timestampDiv1000($value->createTime)), 'classID' => $classID, 'subjectID' => $value['homeWorkTeacher']->subjectId, 'relID' => $relArray, 'memberTotal' => $memberTotalArray]);
                }
            }
        }

        return $dataArray;
    }

    /**
     * 数据库插入数据
     * @param $lastDate
     * @param $dataArray
     */
    public function insterDate($lastDate, $dataArray)
    {


        $thisMonth = date("Y-m-d 23:59:59", strtotime("$lastDate +1 month -1 day"));

        $transaction = Yii::$app->db_school->beginTransaction();
        try {
            $isExisted = SeHomeworkClassReport::find()->where(['between', 'generateTime', $lastDate, $thisMonth])->exists();
            if (!$isExisted) {
                foreach ($dataArray as $v) {

                    $answerInfoQuery = SeHomeworkAnswerInfo::find()->where(['relId' => $v['relID']]);
                    $finishNum = $answerInfoQuery->count();
                    $excellentNum = SeHomeworkAnswerInfo::find()->where(['relId' => $v['relID']])->andWhere(['isCheck' => 1, 'correctLevel' => '4'])->count();
                    $goodNum = SeHomeworkAnswerInfo::find()->where(['relId' => $v['relID']])->andWhere(['isCheck' => 1, 'correctLevel' => '3'])->count();
                    $middleNum = SeHomeworkAnswerInfo::find()->where(['relId' => $v['relID']])->andWhere(['isCheck' => 1, 'correctLevel' => '2'])->count();
                    $badNum = SeHomeworkAnswerInfo::find()->where(['relId' => $v['relID']])->andWhere(['isCheck' => 1, 'correctLevel' => '1'])->count();
                    $totalNum = 0;
                    foreach ($v['memberTotal'] as $value) {
                        $totalNum += $value;
                    }
                    $classReportModel = new SeHomeworkClassReport();
                    $classReportModel->badNum = $badNum;
                    $classReportModel->excellentNum = $excellentNum;
                    $classReportModel->middleNum = $middleNum;
                    $classReportModel->goodNum = $goodNum;
                    $classReportModel->subjectId = $v['subjectID'];
                    $classReportModel->classId = $v['classID'];
                    $classReportModel->totalNum = $totalNum;
                    $classReportModel->finishNum = $finishNum;
                    $classReportModel->createTime = DateTimeHelper::timestampX1000();
                    $classReportModel->generateTime = $v['generateTime'];
                    $classReportModel->save();
                }

            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }


    /**
     *更新se_homework_rel 表的memberTotal字段
     */
    public function actionUpdateHisMember()
    {

        $relResult = SeHomeworkRel::find()->batch(100);
        foreach ($relResult as $v) {
            foreach ($v as $value) {
                $classMemNum = SeClassMembers::getClassNumByClassId($value->classID, SeClassMembers::STUDENT);
                $value->memberTotal = $classMemNum;
                $value->save();
            }

        }
    }

}