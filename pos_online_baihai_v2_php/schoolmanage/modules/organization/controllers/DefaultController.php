<?php

namespace schoolmanage\modules\organization\controllers;

use common\models\JsonMessage;
use common\models\pos\SeClass;
use common\services\ClassChangeService;
use frontend\models\dicmodels\ClassListModel;
use schoolmanage\components\helper\GradeHelper;
use schoolmanage\components\SchoolManageBaseAuthController;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class DefaultController extends SchoolManageBaseAuthController
{
    public $layout = "lay_organization_index";
    public $enableCsrfValidation = false;

    /**
     * 小学学部
     * @return string
     */
    public function actionIndex()
    {
        $schoolID = $this->schoolId;

        $gradeId = app()->request->get("gradeId", '');
        $classId = app()->request->get("classId", '');
        $status = app()->request->get("status", 1);

        $schoolData = $this->schoolModel;
        $departmentIds = $schoolData->department; //学部id
        $lengthOfSchooling = $schoolData->lengthOfSchooling; //学制

        //默认为第一个学部
        $defaultDepartmentId = substr($departmentIds, 0, 5);

        $departmentId = app()->request->get("departmentId", $defaultDepartmentId);

        //查询默认学部的年级列表
        $gradeData = GradeHelper::getGradeByDepartmentAndLengthOfSchooling($departmentId,$lengthOfSchooling);
        $gradeDataList = ArrayHelper::map($gradeData, 'gradeId', 'gradeName');
//        var_dump($gradeDataList);die;

        //查询班级列表数据
        $classListData = ClassListModel::getClassList($schoolID, $gradeId, $classId,$departmentId,$status);
//        var_dump($classListData);

        $classCount = count($classListData);

        if(app()->request->isAjax){
            return $this->renderPartial('_class_list',[
                'departmentIds'=>$departmentIds,
                "schoolId" => $schoolID,
                'departmentId'=>$departmentId,
                'classCount'=>$classCount,
                'classListData'=>$classListData
            ]);
        }

        return $this->render('index',[
            'departmentIds'=>$departmentIds,
            'gradeDataList'=>$gradeDataList,
            "schoolId" => $schoolID,
            'departmentId'=>$departmentId,
            'classCount'=>$classCount,
            'classListData'=>$classListData
        ]);
    }

    /**
     * 保存创建班级
     */

    public function actionCreateClass(){
        $jsonResult = new JsonMessage();
        $schoolID = $this->schoolId;
        $departmentId = app()->request->get("departmentId");
        $gradeId = app()->request->get("gradeId");
        $classNumber = app()->request->get("classNumber");
        $joinYear = app()->request->get("joinYear");
        $className = app()->request->get("className");
        $creatorID = user()->id;

        $classChangeModel=new ClassChangeService();
        $result = $classChangeModel->CreateClass($schoolID,$gradeId,$departmentId,$classNumber,$joinYear,$className,$creatorID);

        return $this->renderJSON($result);
    }

    /**
     * 根据年级$id获取学校的班级
     * @param $id
     * @param bool|true $prompt
     * @param null $department
     */

    public function actionGetClassData($id, $prompt = true, $department = null)
    {
        echo $id;
        $schoolID = $this->schoolId;
        if ($prompt) {
            echo Html::tag("option", "班级", ["value" => ""]);
        }

        if (empty($id)) {
            return;
        }
        $data = ClassListModel::model($schoolID, $id, $department)->getListData();
        if (empty($data)) {
            echo Html::tag('option', Html::encode("无班级"), array('value' => ""));
        }
        foreach ($data as $key => $item) {
            echo Html::tag('option', Html::encode($item), array('value' => $key));
        }
    }

    /**
     * ajax获取学部和入学年份
     * @return string
     */
    public function actionGetDepartmentYear(){
        $departmentId = app()->request->get("departmentId");
        $jsonResult = new JsonMessage();
        //加入时间
        $years = getClassYears();
        $joinYear = [];
        foreach($years as $k=>$v){
            array_push($joinYear,$v);
        }

        //学部
        $schoolData = $this->schoolModel;
        $departmentIds = $schoolData->department; //学部id
        $departmentIds = explode(',',$departmentIds);


        $departmentArr = [];
        foreach($departmentIds as $k=>$v){
            $departmentArr[$k]['id'] = $v;
        }

        //年级
        $gradeArr = $this->actionGetGrade($departmentId);

        $jsonResult->success = true;
        $jsonResult->joinYear = $joinYear;
        $jsonResult->departmentArr = $departmentArr;
        $jsonResult->gradeArr = $gradeArr;


        return $this->renderJSON($jsonResult);

    }

    /**
     * ajax点击封班获取年级和班级
     */
    public function actionCloseClass(){

        $jsonResult = new JsonMessage();
        $departmentId = app()->request->get('departmentId');

        $gradeArr = $this->actionGetGrade($departmentId);
        $classListArr = $this->getClassListArr('', $departmentId);

        $jsonResult->classListArr = $classListArr;
        $jsonResult->gradeArr = $gradeArr;

        return $this->renderJSON($jsonResult);
    }

    /**
     * 完成封班
     * @return string
     */
    public function actionFinishCloseClass(){

        $schoolId = app()->request->post('schoolId');
        $classIds = app()->request->post('classIds');
        $this->isClassInSchool($classIds);
        $classIds = implode(',',$classIds);
        $classChangeModel=new ClassChangeService();
        $result = $classChangeModel->CloseClass($schoolId,$classIds);
        return $this->renderJSON($result);

    }

    /**
     * 升级
     * @return string
     */
    public function actionUpgrade(){
        $schoolId = $this->schoolId;
        $departmentId = app()->request->post('departmentId');
        $classChangeModel=new ClassChangeService();
        $result = $classChangeModel->SchoolUpgrade($schoolId,$departmentId);
        return $this->renderJSON($result);
    }

    /**
     * 封班:更换年级
     */
    public function actionGetClass(){
        $jsonResult = new JsonMessage();
        $gradeId = app()->request->get('gradeId');
        $departmentId = app()->request->get('departmentId');

        $classListArr = $this->getClassListArr($gradeId, $departmentId);

        $jsonResult->classListArr = $classListArr;

        return $this->renderJSON($jsonResult);
    }

    /**
     * 获取班级数据
     * @param $gradeId
     * @param $departmentId
     * @return array
     */
    public function getClassListArr($gradeId, $departmentId){
        $schoolID = $this->schoolId;
        $classList = ClassListModel::model($schoolID, $gradeId, $departmentId)->getList();
        $classListArr=[];
        foreach($classList as $k=>$v){
            $classListArr[$v['classID']] = $v['className'];
        }
        return $classListArr;
    }


    /**
     * 创建班级:根据学部获取年级
     */
    public function actionGetGrade($departmentId){

        $schoolData = $this->schoolModel;
        $lengthOfSchooling = $schoolData->lengthOfSchooling; //学制
        //查询默认学部的年级列表
        $gradeData = GradeHelper::getGradeByDepartmentAndLengthOfSchooling($departmentId,$lengthOfSchooling);
        $gradeDataList = ArrayHelper::map($gradeData, 'gradeId', 'gradeName');
        return $this->renderJSON($gradeDataList);
    }
}
