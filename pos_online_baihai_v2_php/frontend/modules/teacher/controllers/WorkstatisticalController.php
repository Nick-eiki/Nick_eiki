<?php
namespace frontend\modules\teacher\controllers;

use common\helper\DateTimeHelper;
use common\models\pos\SeClassMembers;
use common\models\pos\SeHomeworkAnswerInfo;
use common\models\pos\SeHomeworkAnswerQuestionAll;
use common\models\pos\SeHomeworkAnswerQuestionMain;
use common\models\pos\SeHomeworkRel;
use common\models\pos\SeHomeworkTeacher;
use common\models\sanhai\SeDateDictionary;
use common\models\sanhai\ShTestquestion;
use frontend\components\TeacherBaseController;

class WorkstatisticalController extends TeacherBaseController
{
    public $layout = "lay_user";

    public function actionWorkStatistical()
    {
        $relId = app()->request->get("relId");
        $result = SeHomeworkRel::find()->where(['id' => $relId])->one();
        //查询作业
        $query = SeHomeworkAnswerInfo::find()->where(['relId' => $relId]);
        //截止时间
        $deadlineTime = strtotime(date('Y-m-d 23:59:59', DateTimeHelper::timestampDiv1000($result->deadlineTime))) * 1000;
        //超时提交
        $overtimeInfo = $query->andWhere(['>', 'uploadTime', $deadlineTime])->all();
        $overtimeId = array();
        foreach ($overtimeInfo as $v) {
            $overtimeId[] = $v->studentID;
        }
        //作业信息
        $homeWorkTeacher = $result->getHomeWorkTeacher()->one();

        //查询已答学生
        $answerStuList = $result->getHomeworkAnswerInfo()->all();
        $answerdId = array();
        foreach ($answerStuList as $v) {
            $answerdId[] = $v->studentID;
        }
        //查询班级学生
        $studentList = SeClassMembers::find()->where(['classID' => $result->classID, 'identity' => '20403'])->all();
        $allId = array();
        foreach ($studentList as $v1) {
            $allId[] = $v1->userID;
        }
        //未答的学生
        $noAnswerdId = array();
        foreach ($allId as $v) {
            if (!in_array($v, $answerdId)) {
                $noAnswerdId[] = $v;
            }
        }
        //查询优良中差

        $result = SeHomeworkAnswerInfo::getDb()->createCommand('SELECT correctLevel,count(*) as levelCount from se_homeworkAnswerInfo WHERE relId = :relId  AND isCheck = 1 GROUP BY correctLevel', [':relId' => $relId])->queryAll();
        $level = [];
        foreach ($result as $key => $v) {
            $level[$v['correctLevel']] = $v['levelCount'];
        }
        if (app()->request->isAjax) {
            return $this->renderPartial("work_statistical_all", ['relId' => $relId, 'overtimeId' => $overtimeId, 'homeWorkTeacher' => $homeWorkTeacher, 'noAnswerdId' => $noAnswerdId, 'deadlineTime' => $deadlineTime, 'level' => $level]);
        }
        return $this->render("work_statistical", ['relId' => $relId, 'overtimeId' => $overtimeId, 'homeWorkTeacher' => $homeWorkTeacher, 'noAnswerdId' => $noAnswerdId, 'deadlineTime' => $deadlineTime, 'level' => $level]);
    }

    //题目分析
    public function actionWorkStatisticalTopic($relId)
    {

        $homeworkTeacherOne = SeHomeworkTeacher::findBySql('select * from se_homework_teacher as t where  t.id  in (select homeworkId from  se_homework_rel where id=:relId)', [":relId" => $relId])->one();
        // $homeworkTeacherTwo = SeHomeworkTeacher::find()->joinWith('homeworkRel')->where('{{homeworkRel}}.[[id]]=:relId',[":relId"=>$relId])->one();

        //是否有学生完成该作业
        $isFinishHomework = SeHomeworkAnswerInfo::find()->where(['relId' => $relId])->one();

        /**
         * 主观题、客观题 正确率
         */
        //生成答题卡（X轴）
        $homeworkAnswerAll = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId])->select('questionID')->distinct()->orderBy('questionID')->all();

        $objective = [];
        $subjective = [];
        foreach ($homeworkAnswerAll as $allQuestionId) {

            $questionInfo = ShTestquestion::find()->where(['id' => $allQuestionId->questionID])->one();
            if (!empty($questionInfo->showType)) {
                $showType = $questionInfo->showType;
            } else {
                $showType = $questionInfo->getQuestionShowType();
            }

            $arrQuestionId = $homeworkTeacherOne->getQuestionNo($allQuestionId->questionID);
            $arrQuestionTrueId = $allQuestionId->questionID;
            if ($showType == 1 || $showType == 2) {
                $objective[$arrQuestionId] = $arrQuestionTrueId;
            } else {
                $subjective[$arrQuestionId] = $arrQuestionTrueId;
            }

        }
        ksort($objective);
        ksort($subjective);
        $objectiveArr = []; //转换后的题目id (客观题)
        $objectiveTrueArr = [];    //真实的题目id (客观题)
        $subjectiveArr = []; //转换后的题目id (主观题)
        $subjectiveTrueArr = [];    //真实的题目id (主观题)

        foreach ($objective as $key => $val) {
            array_push($objectiveArr, $key);
            array_push($objectiveTrueArr, $val);
        }

        foreach ($subjective as $i => $item) {
            array_push($subjectiveArr, $i);
            array_push($subjectiveTrueArr, $item);
        }

        //对应的正确率(Y轴)
        $objectiveAnswer = [];      //客观题正确率
        $subjectiveAnswer = [];     //主观题正确率

        foreach ($objective as $objectiveId) {
            $homeworkAnswerCount = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId, 'questionID' => $objectiveId])->count();
            $homeworkAnswerCorrectCount = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId, 'questionID' => $objectiveId, 'correctResult' => 3])->count();
            $rate = sprintf("%.2f", ($homeworkAnswerCorrectCount / $homeworkAnswerCount) * 100);
            array_push($objectiveAnswer, $rate);
        }

        foreach ($subjective as $subjectiveId) {
            $homeworkAnswerCount = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId, 'questionID' => $subjectiveId])->count();
            $homeworkAnswerCorrectCount = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId, 'questionID' => $subjectiveId, 'correctResult' => 3])->count();
            $rate = sprintf("%.2f", ($homeworkAnswerCorrectCount / $homeworkAnswerCount) * 100);
            array_push($subjectiveAnswer, $rate);
        }

        /**
         * 题目难度正确率
         */
        $homeworkQuestions = SeHomeworkAnswerQuestionMain::getDb()->createCommand('SELECT  t.complexity,m.correctResult,count(tID) as countTid from schoolservice.se_homeworkAnswerQuestionMain as m INNER JOIN teachresource.sh_testquestion as t on m.questionID=t.id ' .
            ' where  m.relId = :relId   group by t.complexity,m.correctResult', [':relId' => $relId])->queryAll();

        //难易程度（X轴）
        $totleCount = 0;

        $arr = [];
        foreach ($homeworkQuestions as $complexity => $complexityCount) {
            $totleCount += $complexityCount['countTid'];

            if (!array_key_exists($complexityCount['complexity'], $arr)) {
                if (empty($complexityCount['complexity'])) {
                    continue;
                }
                $complexity = $complexityCount['complexity'];
                $sum = from($homeworkQuestions)->sum(function ($v, $key) use ($complexity) {
                    if ($v['complexity'] == $complexity) {
                        return $v['countTid'];
                    } else {
                        return 0;
                    }
                });

                $persum = from($homeworkQuestions)->sum(function ($v, $key) use ($complexity) {
                    if ($v['complexity'] == $complexity && $v['correctResult'] == 3) {
                        return $v['countTid'];
                    } else {
                        return 0;
                    }
                });

                $complexityName = SeDateDictionary::find()->where(['secondCode' => $complexity])->one()->secondCodeValue;
                $arr[$complexityCount['complexity']] = ['name' => $complexityName, 'per' => $sum > 0 ? ($persum / $sum * 100) : 0];
            }
        }


        $complexityNameArr = from($arr)->select(function ($v) {
                return $v['name'];
            })->toList();
        $complexityRateArr = from($arr)->select(function ($v) {
                return $v['per'];
            })->toList();

        return $this->renderPartial("work_statistical_topic",
            ['relId' => $relId,
                'objectiveArr' => $objectiveArr,
                'objectiveTrueArr' => $objectiveTrueArr,
                'subjectiveArr' => $subjectiveArr,
                'subjectiveTrueArr' => $subjectiveTrueArr,
                'objectiveAnswer' => $objectiveAnswer,
                'subjectiveAnswer' => $subjectiveAnswer,
                'complexityNameArr' => $complexityNameArr,
                'complexityRateArr' => $complexityRateArr,
                'isFinishHomework' => $isFinishHomework
            ]);
    }

//显示题目详情
    public
    function actionQuestionInfo()
    {
        $id = app()->request->getParam('questionId', '');
        $relId = app()->request->getParam('relId', '');
        $questionResult = ShTestquestion::find()->where(['id' => $id])->one();
        //求回答选项的比例
        $options = SeHomeworkAnswerQuestionAll::getDb()->createCommand('select answerOption,count(aid) as optionCount  from  se_homeworkAnswerQuestionAll  where questionID=:questionID and   relId=:relId  group by answerOption', ['questionID' => $id, ':relId' => $relId])->queryAll();

        if (!empty($questionResult->showType)) {
            $showType = $questionResult->showType;
        } else {
            $showType = $questionResult->getQuestionShowType();
        }

        $allOptions = [];
        $optionCountSum = 0;
        foreach ($options as $item) {
            $allOptions[$item['answerOption']] = $item['optionCount'];
            $optionCountSum += $item['optionCount'];
        }
        //求当前人回答的选项
        $answerOption = app()->request->getParam('answerOption', '');
        $student = app()->request->getParam('student', '');
        return $this->renderPartial('question_content', ['questionResult' => $questionResult, 'relId' => $relId, 'allOptions' => $allOptions, 'optionCountSum' => $optionCountSum, 'showType' => $showType, 'answerOption' => $answerOption, 'student' => $student]);
    }

    public
    function actionWorkStatisticalStudent($relId)
    {
        //根据relId查询当前作业所有提交了答案的学生
        $homeworkAnswerID = SeHomeworkAnswerInfo::find()->select('studentID')->where(['relId' => $relId, 'isCheck' => 1])->all();
        if (!empty($homeworkAnswerID)) {
            $orderStudent = [];
            foreach ($homeworkAnswerID as $v) {
                $rightCount = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId, 'studentID' => $v->studentID, 'correctResult' => 3])->count();
                $allCount = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId, 'studentID' => $v->studentID])->count();
                if ($allCount == '0') {
                    $orderStudent[$v->studentID] = 0;
                } else {
                    $orderStudent[$v->studentID] = $rightCount / $allCount;
                }


                //查询学生的作业
                $questionArray = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId, 'studentID' => $v->studentID])->select('questionID')->asArray()->all();
            }
            //根据正确率排序
            arsort($orderStudent);
            //根据relId查询homeworkId
            $homeworkID = SeHomeworkRel::find()->where(['id' => $relId])->one()->homeworkId;
            //根据homeworkID查询题目*/
            $homeworkResult = SeHomeworkTeacher::find()->where(['id' => $homeworkID])->one();

            return $this->renderPartial("work_statistical_student", ['relId' => $relId, 'orderStudent' => $orderStudent, 'questionArray' => $questionArray, 'homeworkResult' => $homeworkResult]);
        } else {
            return "<div style='font-size:18px;margin-top:20px;'>暂无数据！</div>";
        }

    }

}