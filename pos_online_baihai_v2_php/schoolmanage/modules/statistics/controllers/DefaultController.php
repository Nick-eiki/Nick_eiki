<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/2/22
 * Time: 16:47
 */

namespace schoolmanage\modules\statistics\controllers;

use common\helper\ClassContrast;
use common\helper\ExcelHelper;
use common\helper\MathHelper;
use common\models\pos\SeClass;
use common\models\pos\SeExamClass;
use common\models\pos\SeExamPersonalScore;
use common\models\pos\SeExamReportBaseInfo;
use common\models\pos\SeExamSchool;
use common\models\pos\SeExamSubject;
use common\models\sanhai\SeDateDictionary;
use schoolmanage\components\helper\GradeHelper;
use schoolmanage\components\SchoolManageBaseAuthController;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class DefaultController extends SchoolManageBaseAuthController
{
    public $layout = "lay_statistics_index";
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $schoolId = $this->schoolId;
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;

        $year = app()->request->get('examYear', null); //获取 年份
        $examType = app()->request->get('examType', null); //获取考试类型
        $gradeId = app()->request->get('gradeId', null);//获取年级id

        $schoolData = $this->schoolModel;
        if ($schoolData == null) {
            return $this->notFound();
        }

        $department = $schoolData->department;
        $lengthOfSchooling = $schoolData->lengthOfSchooling;
        // $schoolLevelId = app()->request->get("schoolLevel",substr($department, 0, 5));//获取学部 当学部为空时 给默认学校中的第一个学部
        $schoolLevelId = app()->request->get("schoolLevel", null);//获取学部 当学部为空时 给默认学校中的第一个学部
        $departmentId = substr($department, 0, 5);//获取学校里的_index_exam_right_list第一个学段
        if (empty($schoolLevelId)) {
            return $this->redirect(url("/statistics/default/index", ["schoolLevel" => $departmentId, "gradeId" => ""]));
        }

        $gradeData = GradeHelper::getGradeByDepartmentAndLengthOfSchooling($schoolLevelId, $lengthOfSchooling);

        $gradeModel = GradeHelper::getGradeByDepartmentAndLengthOfSchooling($schoolLevelId, $lengthOfSchooling, 1); //获取 学部和学制 相对的年级列表
        //根据年级 获取 年份列表
        $gradeArr = explode(",", $gradeId);
        $yearArr = GradeHelper::getYearList($gradeArr, $schoolId);

        //查询考试列表
        $examSchoolQuery = SeExamSchool::find()->where(['schoolId' => $schoolId, "departmentId" => $schoolLevelId, "reportStatus" => 2]);
        //年级筛选
        if (!empty($gradeId)) {
            $examSchoolQuery->andWhere(['gradeId' => $gradeId]);
        }

        //年份筛选
        if (!empty($year)) {
            $examSchoolQuery->andWhere(['schoolYear' => $year]);
        }
        //考试筛选
        if (!empty($examType)) {
            $examSchoolQuery->andWhere(['examType' => $examType]);
        }
        $pages->totalCount = $examSchoolQuery->count();
        $examSchoolModel = $examSchoolQuery->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();

        if (app()->request->isAjax) {
            return $this->renderPartial("_index_exam_right_list", ["examSchoolModel" => $examSchoolModel, 'schoolData' => $schoolData, 'lengthOfSchooling' => $lengthOfSchooling, 'schoolID' => $schoolId, 'gradeId' => $gradeId, 'pages' => $pages]);
        }
        return $this->render('index', [
            'gradeData' => $gradeData,
            'schoolData' => $schoolData,
            "examSchoolModel" => $examSchoolModel,
            'gradeModel' => $gradeModel,
            'schoolID' => $schoolId,
            'gradeId' => $gradeId,
            'lengthOfSchooling' => $lengthOfSchooling,
            'department' => $department,
            'yearArr' => $yearArr,
            'pages' => $pages,
            'departmentId' => $schoolLevelId
        ]);
    }

    /**
     * @return string
     * 统计概览
     */
    public function actionOverview()
    {

        $schoolExamId = app()->request->get('examId');
        $classId = app()->request->get('classId', '');
        $subjectId = app()->request->get('subjectId', '');

        $this->getSeExamSchoolModel($schoolExamId);

        //查询班级
        $examClass = SeExamClass::find()->where(['schoolExamId' => $schoolExamId])->all();
        //查询当前考试下科目
        $seExamSubject = SeExamSubject::find()->where(['schoolExamId' => $schoolExamId])->all();


        if (!empty($classId) && !empty($subjectId)) { //单年级，单科目
            $data = SeExamReportBaseInfo::getSingleClassSingleSubjectInfo($schoolExamId, $classId, $subjectId);
            $seExamReprotBaseInfoList = $data['seExamReprotBaseInfoList'];
            $rankListDesc = $data['rankListDesc'];
            $rankListAsc = $data['rankListAsc'];

        } elseif (empty($classId) && !empty($subjectId)) { //全部年级单科目
            $seExamReprotBaseInfoList = SeExamReportBaseInfo::getAllClassSingleSubjectInfo($schoolExamId, $subjectId);
            $rankListDesc = [];
            $rankListAsc = [];
        } elseif (!empty($classId) && empty($subjectId)) { //单年级全部科目
            $seExamReprotBaseInfoList = SeExamReportBaseInfo::getSingleClassAllSubjectInfo($schoolExamId, $classId);
        } else {  //全部年级全部科目

            $seExamReprotBaseInfoList = SeExamReportBaseInfo::getAllClassAllSubjectInfo($schoolExamId);

        }

        //查询考试标题
        $examSchoolData = SeExamSchool::find()->where(['schoolExamId' => $schoolExamId])->one();
        if (!empty($examSchoolData)) {
            $examName = $examSchoolData->examName;
        }

        //班级总分分数段占比
        $scoreSectionCount = $this->classTotalScore($schoolExamId, $classId, $subjectId);
        $section = [];
        $count = [];
        $allCount = 0;
        foreach ($scoreSectionCount as $v) {
            $allCount += $v['total'];
        }

        foreach ($scoreSectionCount as $k => $v) {
            if($k == 0){
                $section[] = '[' . $v['min'] . ',' . $v['max'] . ']';
            }else{
                $section[] = '(' . $v['min'] . ',' . $v['max'] . ']';
            }
            $count[] = sprintf("%.2f", MathHelper::division($v['total'], $allCount) * 100);
        }

        if (app()->request->isAjax) {
            if (!empty($subjectId)) {
                return $this->renderPartial('_score_overview_info', [
                    'seExamReprotBaseInfoList' => $seExamReprotBaseInfoList,
                    'subjectId' => $subjectId,
                    'classId' => $classId,
                    'examId' => $schoolExamId,
                    'section' => $section,
                    'count' => $count,
                    'rankListDesc' => $rankListDesc,
                    'rankListAsc' => $rankListAsc
                ]);
            } else {
                return $this->renderPartial('_score_overview_info', [
                    'seExamReprotBaseInfoList' => $seExamReprotBaseInfoList,
                    'subjectId' => $subjectId,
                    'classId' => $classId,
                    'examId' => $schoolExamId,
                    'section' => $section,
                    'count' => $count,
                ]);
            }

        }

        return $this->render('score_overview', [
            'seExamReprotBaseInfoList' => $seExamReprotBaseInfoList,
            'examClass' => $examClass,
            'seExamSubject' => $seExamSubject,
            'subjectId' => $subjectId,
            'classId' => $classId,
            'examId' => $schoolExamId,
            'section' => $section,
            'count' => $count,
            'examName' => $examName
        ]);
    }

    /**
     * 班级总分分数段占比
     * $examId:学校考试id
     * $classId:班级id
     * $subjectId:科目id
     */

    public function classTotalScore($examId, $classId, $subjectId)
    {


        $examClassModel = SeExamClass::find()->where(['schoolExamId' => $examId]);
        if (!empty($classId)) {
            $examClassModel->andWhere(['classId' => $classId]);
        }
        $examClassData = $examClassModel->all();
        $data = [];
        foreach ($examClassData as $v) {
            $personalScore = SeExamPersonalScore::find()->where(['classExamId' => $v->classExamId])->all();
            foreach ($personalScore as $v1) {
                if (!empty($subjectId)) {

                    $data[] = ExcelHelper::getSubjectScore($subjectId, $v1);

                } else {
                    $data[] = $v1->totalScore;
                }

            }
        }
        if (!empty($subjectId)) {
            $examSubject = SeExamSubject::find()->where(['schoolExamId' => $examId, 'subjectId' => $subjectId])->one();
            if (!empty($examSubject)) {
                $totalScore = $examSubject->fullScore;
            }
            $scoreSection = 10;

            $scoreSectionCount = $this->scoreSectionCount($data, $scoreSection, $totalScore);
        } else {
            $examSubject = SeExamSubject::find()->select(['fullScore'])->where(['schoolExamId' => $examId])->all();
            $totalScore = 0;
            if (!empty($examSubject)) {
                foreach ($examSubject as $v) {
                    $totalScore += $v->fullScore;
                }
            }
            $scoreSection = 50;
            $scoreSectionCount = $this->scoreSectionCount($data, $scoreSection, $totalScore);
        }

        //data : ['[0,50]','[50,100]','[100,150]','[150,200]','[200,250]','[250,300]']


        return $scoreSectionCount;
    }


    /**
     * @return string
     *班级对比
     */
    public function actionClassesContrast()
    {

        $schoolExamId = app()->request->get('examId');
        $subjectId = app()->request->get('subjectId');
        $num = app()->request->get('num');

        $this->getSeExamSchoolModel($schoolExamId);

        $examSchool = SeExamSchool::find()->where(['schoolExamId' => $schoolExamId])->one();
        $class = SeExamClass::find()->where(['schoolExamId' => $schoolExamId])->select('classExamId,schoolExamId,classId')->all();
        //显示科目
        $subject = SeExamSubject::find()->where(['schoolExamId' => $schoolExamId])->select('subjectId')->column();
        $classModel=new ClassContrast();
        $subList = $classModel->showSubject($subject);

        if (empty($subjectId)) {
            //总成绩
            $dataList = $classModel->classSumScore($class, $schoolExamId);
            //优良率
            $contrastList = $classModel->classGoodNum($class, $schoolExamId);
            //及格率
            $passList = $classModel->classPassList($class, $schoolExamId);
            //低分率
            $lowList = $classModel->classLowList($class, $schoolExamId);
            //高分人数对比
            $topList = $classModel->classTopList($class, $schoolExamId);
            //不及格人数对比
            $lowScoreList = $classModel->classNoPassList($class, $schoolExamId);
            //学生构成分析
            $studentAnalyze = $classModel->classAnalyze($schoolExamId);
        } else {
            //单科成绩
            $dataList = $classModel->subjectScoreSum($subjectId, $schoolExamId);
            //优良率
            $contrastList = $classModel->subjectExcellent($schoolExamId, $subjectId);
            //及格率
            $passList = $classModel->subjectNoPass($schoolExamId, $subjectId);
            //低分率
            $lowList = $classModel->subjectLow($schoolExamId, $subjectId);
            //高分人数对比
            $topList = $classModel->subjectTopList($schoolExamId, $subjectId);
            //单科不及格人数对比
            $lowScoreList = $classModel->subjectLowList($schoolExamId, $subjectId);
        }
        if (app()->request->isAjax) {
            if ($num == 1) {
                return $this->renderPartial('_average_class', ['dataList' => $dataList]);
            } elseif ($num == 2) {
                return $this->renderPartial('_three_contrast_class', ['contrastList' => $contrastList,'passList' => $passList,'lowList' => $lowList]);
            } elseif ($num == 3) {
                return $this->renderPartial('_top_low_class', ['topList' => $topList,'lowScoreList' => $lowScoreList]);
            }

        }
        return $this->render('classes_contrast', [
            'examSchool' => $examSchool,
            'examId' => $schoolExamId,
            'subList' => $subList,
            'dataList' => $dataList,
            'contrastList' => $contrastList,
            'passList' => $passList,
            'lowList' => $lowList,
            'topList' => $topList,
            'lowScoreList' => $lowScoreList,
            'studentAnalyze' => $studentAnalyze]);
    }


    /**
     * @param $schoolLevelId
     * @return array|\yii\web\Response
     * @throws \yii\web\HttpException
     * 左侧--班级学段选择
     */
    public function selectClass($schoolLevelId)
    {
        $data = [];

        $schoolData = $this->schoolModel;
        if ($schoolData == null) {
            return $this->notFound();
        }

        $department = $schoolData->department;

        $lengthOfSchooling = $schoolData->lengthOfSchooling;

        $departmentId = substr($department, 0, 5);//获取学校里的_index_exam_right_list第一个学段
        if (empty($schoolLevelId)) {
            return $this->redirect(url("/statistics/default/index", ["schoolLevel" => $departmentId, "gradeId" => ""]));
        }
        $gradeModel = GradeHelper::getGradeByDepartmentAndLengthOfSchooling($schoolLevelId, $lengthOfSchooling, 1); //获取 学部和学制 相对的年级列表

        $data['department'] = $department;
        $data['gradeModel'] = $gradeModel;
        return $data;
    }



    /**
     * @param $data ,$scoreSection,$totalScore
     * @return array
     * 每个分数段的人数
     */
    public function scoreSectionCount($data, $scoreSection, $totalScore)
    {
        $num = ceil($totalScore / $scoreSection);
        $scoreSectionData = [];
        for ($i = 0; $i < $num; $i++) {
            $min = $scoreSection * $i;
            $max = $scoreSection * ($i + 1);
            $total = 0;
            if ($i == 0) {
                foreach ($data as $v) {
                    if ($v >= $min && $v <= $max) {
                        $total += 1;
                    }
                }
            } else {
                foreach ($data as $v) {
                    if ($i == $num - 1) {
                        $max = $totalScore;
                    }
                    if ($v > $min && $v <= $max) {
                        $total += 1;
                    }
                }
            }

            $scoreSectionData[] = ['min' => $min, 'max' => $max, 'total' => $total];
        }

        return $scoreSectionData;
    }

}