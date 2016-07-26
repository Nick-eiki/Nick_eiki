<?php

namespace schoolmanage\modules\exam\controllers;

use common\helper\DateTimeHelper;
use common\models\JsonMessage;
use common\models\pos\SeClass;
use common\models\pos\SeClassMembers;
use common\models\pos\SeExamClass;
use common\models\pos\SeExamClassSubject;
use common\models\pos\SeExamPersonalScore;
use common\models\pos\SeExamSchool;
use common\models\pos\SeExamScoreInput;
use common\models\pos\SeExamSubject;
use common\models\pos\SeSchoolInfo;
use common\models\pos\SeUserinfo;
use common\models\sanhai\SeDateDictionary;
use common\models\sanhai\SeSchoolGrade;
use frontend\components\WebDataCache;
use frontend\models\dicmodels\SchoolLevelModel;
use PhpOffice\PhpWord\Exception\Exception;
use schoolmanage\components\helper\GradeHelper;
use schoolmanage\components\SchoolManageBaseAuthController;
use Yii;
use yii\data\Pagination;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for SeUserinfo model.
 */
class DefaultController extends SchoolManageBaseAuthController
{

    public $layout = "lay_exam_index";
    public $enableCsrfValidation = false;

    /**
     *
     */
    public function actionIndex()
    {
        $schoolID = $this->schoolId;
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $year = app()->request->get('examYear', null); //获取 年份
        $examType = app()->request->get('examType', null); //获取考试类型
        $isSolved = app()->request->get('isSolved', null); //获取录入状态
        $gradeId = app()->request->get('gradeId',null);//获取年级id

        $schoolData = $this->schoolModel;
        if($schoolData==null){
            return $this->notFound();
        }
        $department = $schoolData->department; //学部id
        $lengthOfSchooling = $schoolData->lengthOfSchooling; //学制id
       // $schoolLevelId = app()->request->get("schoolLevel",substr($department, 0, 5));//获取学部 当学部为空时 给默认学校中的第一个学部
        $schoolLevelId = app()->request->get("schoolLevel", null);//获取学部 当学部为空时 给默认学校中的第一个学部
        $departmentId = substr($department, 0, 5);//获取学校里的第一个学段
        if(empty($schoolLevelId)){
            return $this->redirect(url("/exam/default/index",["schoolLevel"=>$departmentId,"gradeId"=>""]));
        }
        $gradeData=GradeHelper::getGradeByDepartmentAndLengthOfSchooling($schoolLevelId,$lengthOfSchooling);
        $departmentArray = explode(',', $department);
        $schoolLevelList = SchoolLevelModel::model()->getListInData($departmentArray);//根据学校id 获取该校的学部


        $gradeModel = GradeHelper::getGradeByDepartmentAndLengthOfSchooling($schoolLevelId,$lengthOfSchooling,1); //获取 学部和学制 相对的年级列表
        //根据年级 获取 年份列表
        $gradeArr = explode(",",$gradeId);
        $yearArr = GradeHelper::getYearList($gradeArr, $schoolID);

        //查询考试列表
        $examSchoolQuery = SeExamSchool::find()->where(['schoolId'=>$schoolID,"departmentId"=>$schoolLevelId]);

        //年级筛选
        if(!empty($gradeId)){
            $examSchoolQuery->andWhere(['gradeId'=>$gradeId]);
        }

        //年份筛选
        if(!empty($year)){
            $examSchoolQuery->andWhere(['schoolYear'=>$year]);
        }
        //考试筛选
        if(!empty($examType)){
            $examSchoolQuery->andWhere(['examType'=>$examType]);
        }
        //状态筛选
        if($isSolved!=null){
            $examSchoolQuery->andWhere(['inputStatus'=>$isSolved]);
        }
        $pages->totalCount = $examSchoolQuery->count();
        $examSchoolModel = $examSchoolQuery->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();

        if(app()->request->isAjax){
            return $this->renderPartial("_index_exam_right_list",["examSchoolModel"=>$examSchoolModel, 'department'=>$schoolLevelId, 'pages' => $pages]);
        }
        return $this->render('index', [
            'gradeData' => $gradeData,
            'schoolData' => $schoolData,
            "examSchoolModel" => $examSchoolModel,
            'gradeModel' => $gradeModel,
            'schoolID' => $schoolID,
            'gradeId' => $gradeId,
            'lengthOfSchooling' => $lengthOfSchooling,
            'yearArr' => $yearArr,
            'schoolLevelList'=>$schoolLevelList,
            'pages' => $pages,
            'department'=>$schoolLevelId
        ]);
    }

    /**
     * 获取创建考试弹窗
     * @return string
     */
    public function actionGetExamBox(){
        $schoolID = $this->schoolId;
        $department=app()->request->getBodyParam('department');
        $schoolData = $this->schoolModel;
        $lengthOfSchooling=$schoolData->lengthOfSchooling;
        $gradeData=GradeHelper::getGradeByDepartmentAndLengthOfSchooling($department,$lengthOfSchooling);
        return $this->renderPartial('get_exam_box',[
                'schoolID'=>$schoolID,
                'department'=>$department,
                'gradeData'=>$gradeData,
            'schoolData'=>$schoolData
            ]);
    }

    /**
     *获取学年
     * @return string
     */
    public function actionGetSchoolYearList()
    {
        $schoolID = app()->request->getBodyParam('schoolID');
        $gradeArray = app()->request->getBodyParam('gradeArray');
        $yearArray = GradeHelper::getYearList($gradeArray, $schoolID);

        return $this->renderPartial('school_year_list', ['yearArray' => $yearArray]);
    }

    /**
     *获取考试名称
     */
    public function actionProduceExamName()
    {
        $schoolID = app()->request->getBodyParam('schoolID');
        $lengthOfSchooling = SeSchoolInfo::find()->where(['schoolID' => $schoolID])->one()->lengthOfSchooling;
        $gradeArray = app()->request->getBodyParam('gradeArray');
        $schoolYear = app()->request->getBodyParam('schoolYear');
        $semester = app()->request->getBodyParam('semester');
        $wenli = app()->request->getBodyParam('wenli');
        $monthArray = app()->request->getBodyParam('monthArray');
        $examNameArray = [];
        foreach ($gradeArray as $v) {
            $yearOutGradeName = GradeHelper::getYearOutGrade($v, $schoolYear);
            $comingYear = GradeHelper::getComingYearByGrade($v, $lengthOfSchooling);
            foreach ($monthArray as $item) {
                $examName = $schoolYear . '学年' . $yearOutGradeName . '（' . $comingYear . '级）' . WebDataCache::getDictionaryName($semester) . $item . '月月考';
                if ($wenli == 1) {
                    array_push($examNameArray, $examName);
                } else {
                    array_push($examNameArray, $examName . '（文科）');
                    array_push($examNameArray, $examName . '（理科）');
                }
            }
        }
        foreach ($examNameArray as $v) {
            echo '<li>' . $v . '</li>';
        }
    }

    /**
     *创建考试
     */
    public function actionCreateExam()
    {
        $jsonResult=new JsonMessage();
        $schoolID = app()->request->getBodyParam('schoolID');
        $lengthOfSchooling = SeSchoolInfo::find()->where(['schoolID' => $schoolID])->one()->lengthOfSchooling;
        $gradeArray = app()->request->getBodyParam('gradeArray');
        $schoolYear = app()->request->getBodyParam('schoolYear');
        $semester = app()->request->getBodyParam('semester');
        $wenli = app()->request->getBodyParam('wenli');
        $department=app()->request->getBodyParam('department');
        $monthArray = app()->request->getBodyParam('monthArray');
        foreach ($gradeArray as $v) {
            $yearOutGradeName = GradeHelper::getYearOutGrade($v, $schoolYear);
            $comingYear = GradeHelper::getComingYearByGrade($v, $lengthOfSchooling);
            foreach ($monthArray as $item) {
                $transaction = Yii::$app->db_school->beginTransaction();
                try {
                    $examName = $schoolYear . '学年' . $yearOutGradeName . '（' . $comingYear . '级）' . WebDataCache::getDictionaryName($semester) . $item . '月月考';
                    if ($wenli == 1) {
                        $isExisted = SeExamSchool::find()->where(['schoolId' => $schoolID, 'examName' => $examName])->exists();
                        if (!$isExisted) {
                            $examModel = new SeExamSchool();
                            $examModel->examType = '21903';
                            $examModel->departmentId=$department;
                            $examModel->createTime = DateTimeHelper::timestampX1000();
                            $examModel->schoolId = $schoolID;
                            $examModel->semester = $semester;
                            $examModel->schoolYear = $schoolYear;
                            $examModel->examMonth = $item;
                            $examModel->createrId = user()->id;
                            $examModel->gradeId = $v;
                            $examModel->subjectType = '22401';
                            $examModel->examName = $examName;
                            $examModel->save();
                        }
                    } else {
                        for ($i = 0; $i < 2; $i++) {

                            if ($i == 0) {
                                $finalExamName = $examName . '（文科）';
                                $subjectType = '22403';

                            } else {
                                $finalExamName = $examName . '（理科）';
                                $subjectType = '22402';
                            }
                            $isExisted = SeExamSchool::find()->where(['schoolId' => $schoolID, 'examName' => $finalExamName])->exists();
                            if (!$isExisted) {
                                $examModel = new SeExamSchool();
                                $examModel->examType = '21903';
                                $examModel->departmentId=$department;
                                $examModel->createTime = DateTimeHelper::timestampX1000();
                                $examModel->schoolId = $schoolID;
                                $examModel->semester = $semester;
                                $examModel->schoolYear = $schoolYear;
                                $examModel->examMonth = $item;
                                $examModel->createrId = user()->id;
                                $examModel->gradeId = $v;
                                $examModel->subjectType = $subjectType;
                                $examModel->examName = $finalExamName;
                                $examModel->save();
                            }

                        }

                    }
                    $transaction->commit();
                    $jsonResult->success=true;
                }catch (Exception $e) {
                    $transaction->rollBack();

                }
            }
        }
        return $this->renderJSON($jsonResult);
    }

    /*
     *设置科目和分数线
     */
    public function actionSetScore(){
        $this->layout = 'lay_exam_score';
        $schoolId=$this->schoolId;
        $schoolExamId=app()->request->get('examId');
        $this->getSeExamSchoolModel($schoolExamId);

        $SeExamSchool=SeExamSchool::find()->where(['schoolExamId'=>$schoolExamId,'schoolId'=>$schoolId])->one();
        //学段
        $Department=SeSchoolGrade::find()->where(['gradeId'=>$SeExamSchool->gradeId])->select('schoolDepartment')->one();
        //班级和科目
        $class=SeClass::find()->where(['schoolID'=>$schoolId,'gradeID'=>$SeExamSchool->gradeId,'isDelete'=>0,'status'=>0])->select('classID,className')->all();
        if(!empty($class)){
            $subject=SeDateDictionary::find()->where(['like','reserve1',$Department->schoolDepartment])->select('secondCode,secondCodeValue')->all();
        }else{$subject=null;}
//        if(!empty($SeExamSchool) && !empty($class)){
//            preg_match_all('/(?:\（)(\d*级)(?:\）)/i',$SeExamSchool->examName,$str_ary);
//            $name=$str_ary[1][0];
//        }else{$name=null;}
            //考试科目和班级班级
            $subjectModel=SeExamSubject::find()->where(['schoolExamId'=>$schoolExamId])->all();
            $classModel=SeExamClass::find()->where(['schoolExamId'=>$schoolExamId])->select('classId')->all();
            $score=$this->sumScore($subjectModel);
            return $this->render('score',['class'=>$class,'score'=>$score,'subject'=>$subject,'SeExamSchool'=>$SeExamSchool,'schoolExamId'=>$schoolExamId,'classModel'=>$classModel,'subjectModel'=>$subjectModel]);

    }
    /*
     * 接受数据
     */
    public function actionReceive(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $schoolExamId=Yii::$app->request->post('school');
        $checked=Yii::$app->request->post('checkbox');
        $man=Yii::$app->request->post('man');
        $transaction=Yii::$app->db_school->beginTransaction();
        try{
            $this->subjectList($schoolExamId,$man);
            $this->classList($schoolExamId,$checked);
            $this->subjectTeacher($schoolExamId,$man,$checked);
            $transaction->commit();
            return true;
        }catch (Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    //考试科目
    public function subjectList($schoolExamId,$man){
        if(!empty($man)){
            //删除科目
            $array=array_values($man);
            SeExamSubject::deleteAll(['and','schoolExamId= :school',['not in', 'subjectId', $array]],[':school' => $schoolExamId]);
            //添加或更新科目
            foreach($man as $val){
                $subjectModel=SeExamSubject::find()->where(['schoolExamId'=>$schoolExamId,'subjectId'=>$val])->one();
                if(empty($subjectModel)){
                    $subject=new SeExamSubject();
                    $subject->schoolExamId=$schoolExamId;
                    $subject->subjectId=$val;
                    $subject->fullScore=Yii::$app->request->post($val.'_full');
                    $subject->borderlineOne=Yii::$app->request->post($val.'_cutLine');
                    $subject->createTime=DateTimeHelper::timestampX1000();
                    $subject->save();
                }else{
                    $subjectModel->fullScore=Yii::$app->request->post($val.'_full');
                    $subjectModel->borderlineOne=Yii::$app->request->post($val.'_cutLine');
                    $subjectModel->updateTime=DateTimeHelper::timestampX1000();
                    $subjectModel->update();
                }
            }
        }
    }
    //考试班级
    public function classList($schoolExamId,$checked){
        if(!empty($checked)){
            //删除班级和成绩
            $array=array_values($checked);
            $delClass=SeExamClass::find()->where(['and',"schoolExamId=:schoolExamId",['not in','classId',$array]],[":schoolExamId"=>$schoolExamId])->select('classExamId')->column();
            $examClass=SeExamClass::deleteAll(['and',"schoolExamId=:schoolExamId",['not in','classId',$array]],[":schoolExamId"=>$schoolExamId]);
            if($examClass>0){
                $classArray=array_values($delClass);
                SeExamPersonalScore::deleteAll(['in','classExamId',$classArray]);
                SeExamScoreInput::deleteAll(['in','classExamId',$classArray]);
            }
            //添加或更新班级
            foreach($checked as $val){
                $classModel=SeExamClass::find()->where(['schoolExamId'=>$schoolExamId,'classId'=>$val])->one();
                if(empty($classModel)){
                    $class=new SeExamClass();
                    $class->schoolExamId=$schoolExamId;
                    $class->classId=$val;
                    $class->inputStatus=0;
                    $class->createTime=DateTimeHelper::timestampX1000();
                    $class->save();
                }else{
                    $classModel->updateTime=DateTimeHelper::timestampX1000();
                    $classModel->update();
                }
            }
            $inputStatus=SeExamClass::find()->where(['schoolExamId'=>$schoolExamId])->select('inputStatus')->column();
            $statusSum=SeExamClass::find()->where(['schoolExamId'=>$schoolExamId,'inputStatus'=>2])->select('inputStatus')->count();
           if(in_array(1,$inputStatus)){
               $this->upSchool($status=1,$schoolExamId);
           }elseif(floatval(count($inputStatus))==floatval($statusSum)){
               $this->upSchool($status=2,$schoolExamId);
           }else{
               $this->upSchool($status=0,$schoolExamId);
           }
        }
    }



    /*
     * 设置科目和分数线回显总分
     */
    public function sumScore($subjectModel){
        $sum=0;
        $scoreLine=0;
        if(!empty($subjectModel)){
            foreach($subjectModel as $val){
                $sum+=$val->fullScore;
                $scoreLine+=$val->borderlineOne;
            }
        }
        $list=['sum'=>$sum,'scoreLine'=>$scoreLine];
        return $list;
    }


    /*
     * 更新考试状态
     */
    public function upSchool($status,$schoolExamId){
        return  SeExamSchool::updateAll(['inputStatus'=>$status],'schoolExamId=:schoolExamId',[':schoolExamId'=>$schoolExamId]);
    }




    /*
     * 考试班级，科目老师
     */
    public function subjectTeacher($schoolExamId,$man,$checked){
        if(!empty($schoolExamId) && !empty($man) && !empty($checked)){
            $schoolId=$this->schoolId;
            //学校考试
            $ExamSchool= SeExamSchool::find()->where(['schoolExamId'=>$schoolExamId,'schoolId'=>$schoolId])->one();
            //学部
            $Department= SeSchoolGrade::find()->where(['gradeId'=>$ExamSchool->gradeId])->select('schoolDepartment')->one();
            //班级和科目
            foreach($checked as $val){
               $class[]= SeExamClass::find()->where(['schoolExamId'=>$schoolExamId,'classId'=>$val])->select('classExamId,classId')->one();
            }
            foreach($man as $v){
                $subject[]=  SeExamSubject::find()->where(['schoolExamId'=>$schoolExamId,'subjectId'=>$v])->select('examSubId,subjectId')->one();
            }
           //删除考试班级科目老师
            foreach($subject as $val){
                $subjectId[]=$val->examSubId;
            }
            foreach($class as $val){
                $classId[]=$val->classExamId;
            }
            $examSubId=array_values($subjectId);
            $classExamId=array_values($classId);
            SeExamClassSubject::deleteAll(['and',"schoolExamId=:schoolExamId",['or',['not in','examSubId',$examSubId],['not in','classExamId',$classExamId]]],[":schoolExamId"=>$schoolExamId]);
            //添加或更新考试班级科目老师
            if(!empty($class) && !empty($subject)){
                foreach($class as $val){
                    foreach($subject as $v){
                       $subjectTeacher= SeExamClassSubject::find()->where(['examSubId'=>$v->examSubId,'classExamId'=>$val->classExamId])->one();
                       if(empty($subjectTeacher)){
                           $this->subTeacher($v,$val,$schoolExamId);

                       } else{
                           $subjectTeacher->updateTime=DateTimeHelper::timestampX1000();
                           $subjectTeacher->update();
                       }
                    }
                }
            }

        }
    }
    /*
     * 学校老师或校长ID
     */
    public function user($val,$subjectID){
        $classResult=SeClassMembers::find()->where(['classID'=>$val->classId])->select('userID')->column();
         return SeUserinfo::find()->where(['userID'=>$classResult,'isDelete'=>0,'subjectID'=>$subjectID])->select('userID')->one();
    }
    /*
     * 考试班级科目老师
     */
    public  function subTeacher($v,$val,$schoolExamId){
        $teacherID=$this->user($val,$v->subjectId);

        $teacher=new SeExamClassSubject();
        $teacher->examSubId=$v->examSubId;
        $teacher->classExamId=$val->classExamId;
        $teacher->schoolExamId=$schoolExamId;
        $teacher->createTime=DateTimeHelper::timestampX1000();
        if($teacherID){
            $teacher->teacherId=$teacherID->userID;
        }else{

                $teacher->teacherId=0;
        }
        $teacher->save();
    }


}

