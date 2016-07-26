<?php

namespace schoolmanage\modules\shortboard\controllers;

use common\models\databusiness\SdUserWeaknessKid;
use frontend\components\helper\DepartAndSubHelper;
use frontend\components\WebDataKey;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\dicmodels\SchoolLevelModel;
use schoolmanage\components\helper\GradeHelper;
use schoolmanage\components\SchoolManageBaseAuthController;
use Yii;
use yii\db\Query;

class DefaultController extends SchoolManageBaseAuthController
{
    public $layout = "lay_statistics_index";
    public $enableCsrfValidation = false;

    public function actionIndex()
    {

        $schoolData = $this->schoolModel;
        if ($schoolData == null) {
            return $this->notFound();
        }

        $department = $schoolData->department;  //学部id(20201,20202,20203)
        $lengthOfSchooling = $schoolData->lengthOfSchooling; //学制id
        $schoolId = $schoolData->schoolID;      //学校id

        $defaultDepartmentId = substr($department, 0, 5);//默认为学校里的_index_exam_right_list第一个学段

        $schoolLevelId = app()->request->get("schoolLevel", null);//获取学部 当学部为空时 给默认学校中的第一个学部

        //判断是否有学部id，没有默认为小学
        if(empty($schoolLevelId)){
           return $this->isEmptySchoolLevelId($defaultDepartmentId,$lengthOfSchooling);
        }


        //获取 学部和学制 相对的年级列表
        $gradeModel = GradeHelper::getGradeByDepartmentAndLengthOfSchooling($schoolLevelId,$lengthOfSchooling,1);

        $gradeId = app()->request->get('gradeId', null);//获取年级id

        //获取当前学部下的所有科目
        $subjectNumber = $this->getSubjectNumber($schoolLevelId);

        //科目
        $subjectId = app()->request->get('subjectId',empty($subjectNumber)?[]:key($subjectNumber[0]));
        $defaultmonth = date('Y-m',strtotime('-1 month'));
        $month = app()->request->get('month',$defaultmonth);

        $BeginDate = $month . '-01';
        $endDate = date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));

        $monthShortBoard = $this->getKnowledgePoint($schoolId,$gradeId,$subjectId,$BeginDate,$endDate);

        if(app()->request->isAjax) {
            return $this->renderPartial("short_board",['monthShortBoard'=>$monthShortBoard]);
        }

        return $this->render('index',[
            'gradeId'=>$gradeId,
            'schoolLevelId'=>$schoolLevelId,
            'gradeModel' => $gradeModel,
            'department'=>$department,
            'subjectNumber'=>$subjectNumber,
            'monthShortBoard'=>$monthShortBoard,
            'lengthOfSchooling'=>$lengthOfSchooling
        ]);
    }

    /**
     * 周短板
     * @param $classId
     * @return string
     */
    public function actionWeekShort()
    {
        $schoolData = $this->schoolModel;
        $department = $schoolData->department;
        $schoolId = $schoolData->schoolID;
        $lengthOfSchooling = $schoolData->lengthOfSchooling; //学制id

        $gradeId = app()->request->get('gradeId', null);//获取年级id
        $schoolLevelId = app()->request->get("schoolLevel", null);//获取学部 当学部为空时 给默认学校中的第一个学部

        $defaultDepartmentId = substr($department, 0, 5);//获取学校里的_index_exam_right_list第一个学段

        //判断是否有学部id，没有默认为小学
        if(empty($schoolLevelId)){
            return $this->isEmptySchoolLevelId($defaultDepartmentId,$lengthOfSchooling);
        }

        $gradeModel = GradeHelper::getGradeByDepartmentAndLengthOfSchooling($schoolLevelId,$lengthOfSchooling,1); //获取 学部和学制 相对的年级列表


        //获取当前学部下的所有科目
        $subjectNumber = $this->getSubjectNumber($schoolLevelId);

        $subjectId = app()->request->get('subjectId',empty($subjectNumber)?[]:key($subjectNumber[0]));

        $n = time() - 86400 * date('N', time());
        $week_start = date('Y-m-d', $n - 86400 * 6 );
        $week_end = date('Y-m-d', $n);

        $weekstart = app()->request->get('weekstart', $week_start);
        $weekend = app()->request->get('weekend', $week_end);
        $defaultTime = $weekstart .','.$weekend;

        $monthShortBoard = $this->getKnowledgePoint($schoolId,$gradeId,$subjectId,$weekstart,$weekend);

        if(app()->request->isAjax) {
            return $this->renderPartial("short_board",['monthShortBoard'=>$monthShortBoard]);
        }
        return $this->render('weekshort', [
            'weekstart'=>$weekstart,
            'weekend'=>$weekend,
            'defaultTime'=>$defaultTime,
            'gradeId'=>$gradeId,
            'schoolLevelId'=>$schoolLevelId,
            'gradeModel' => $gradeModel,
            'department'=>$department,
            'subjectNumber'=>$subjectNumber,
            'monthShortBoard'=>$monthShortBoard,
            'lengthOfSchooling'=>$lengthOfSchooling
        ]);
    }


    /**
     * 如果学部为空，默认为显示小学
     * @param $defaultDepartmentId
     * @param $lengthOfSchooling
     * @return \yii\web\Response
     */
    public function isEmptySchoolLevelId($defaultDepartmentId,$lengthOfSchooling){

        $defaultGradeModel = GradeHelper::getGradeByDepartmentAndLengthOfSchooling($defaultDepartmentId,$lengthOfSchooling,1);
        $defaultGradeId = $defaultGradeModel[0]->gradeId;
        return $this->redirect(url("/shortboard/default/index", ["schoolLevel" => $defaultDepartmentId, "gradeId" => $defaultGradeId]));

    }


    /**
     * 获取当前学部下的所有学科
     * @param $schoolLevelId
     * @return array
     */
    public function getSubjectNumber($schoolLevelId){
        $subjectNumber=[];
        $subjects=DepartAndSubHelper::getTopicSubArray();
        foreach($subjects as $k=>$v){
            if($schoolLevelId == $k){
                $subjectNumber[] = $v;
            }
        }
        return $subjectNumber;
    }


    /**
     * 短板知识点
     * @param $classId
     * @param $subjectId
     * @param $BeginTime
     * @param $endTime
     * @param int $limit
     * @return array
     */
    public  function  getKnowledgePoint($schoolId,$gradeId,$subjectId,$BeginTime,$endTime,$limit=15){

        $cache = Yii:: $app->cache;
        $key = WebDataKey::SCHOOL_SHORTBOARD_CACHE_KEY . $schoolId.$gradeId.$subjectId.$BeginTime.$endTime;
        $shortBoard = $cache->get($key);
        if ($shortBoard === false) {
            $shortBoard = [];
            $db = SdUserWeaknessKid::getDb();
            $userWeaknessModel = new Query();
            $userWeaknessModel->select(["a.kid","count(a.kid) num"])
                ->from("sd_user_weakness_kid a")
                ->join("INNER JOIN", "sd_user_weakness b", "a.weakId=b.weakId")
                ->where("b.correctResult<3")
                ->andWhere("a.schoolId=:schoolId ",[':schoolId'=>$schoolId])
                ->andWhere("a.subjectId=:subjectId ",[':subjectId'=>$subjectId]);

            $userWeaknessModel->andWhere("a.gradeId=:gradeId ",[':gradeId'=>$gradeId]);
            $userWeaknessModel->andWhere(['between','a.createTime',$BeginTime,$endTime]);

            $monthWeaknessData = $userWeaknessModel->groupBy('kid')->orderBy('num desc')->limit($limit)->createCommand($db)->queryAll();

            foreach($monthWeaknessData as $k =>$v){
                $shortBoard[$k]['num'] = $v['num'];
                $shortBoard[$k]['name'] = KnowledgePointModel::getNamebyId($v['kid']);
                $shortBoard[$k]['kid'] = $v['kid'];
            }

            if ($shortBoard != null) {
                $cache ->set($key, $shortBoard, 86400);
            }
        }
        return $shortBoard;
    }

}
