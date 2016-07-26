<?php
namespace frontend\modules\teacher\controllers;
use common\models\JsonMessage;
use frontend\components\helper\ImagePathHelper;
use frontend\components\TeacherBaseController;
use frontend\services\BaseService;
use frontend\services\pos\pos_TeachingPlanInfoService;
use yii\data\Pagination;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-14
 * Time: 上午11:07
 */
class TeachingplanController extends TeacherBaseController

{
    public $layout = 'lay_user';


    /**
     *教学计划列表
     */
    public function actionIndex(){
        $userId = user()->id;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        $grade = app()->request->getParam('grade', '');
        $pages->params = ['grade' => $grade];
        $model = new pos_TeachingPlanInfoService();
        $teachingPlan = $model->teachingPlanSearch(1,$grade,$userId ,'', 1, $pages->getPage() + 1, $pages->pageSize, '');
        $pages->totalCount = intval($teachingPlan->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_list_plan', array(
                'teachingList' => $teachingPlan->teachingPlanList,
                'pages' => $pages
            ));
            return;
        }

        return $this->render('index',array('teachingList' =>$teachingPlan->teachingPlanList,'pages' => $pages));
    }

    /**
     *教学计划添加
     */
    public function actionAddPlan(){
        $creatorID = user()->id;
        $type = 1;
        $planName = app()->request->getParam('planName','');
        $gradeID = app()->request->getParam('gradeID','');
        $brief = app()->request->getParam('brief','');
        $url =  app()->request->getParam('url','');
        $newUrl = ImagePathHelper::replace_pic($url);
        $teachingGroupID = '';
        $teachingPlan = new pos_TeachingPlanInfoService();
        $addTeaching = $teachingPlan->teachingPlanAdd($type, $planName, $gradeID, $brief, $creatorID, $newUrl, $teachingGroupID, '');
        $jsonResult = new JsonMessage();
        if ($addTeaching->resCode === BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message="添加失败！";
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     *根据教学计划id查出详细数据
     */
    public function actionGetTeachingPlan(){
        $id = intval($_POST['id']);
        $teachingPlan = new pos_TeachingPlanInfoService();
        $teachingSearch = $teachingPlan->teachingPlanDetaileSearch($id, '');
        if ($teachingSearch->resCode == BaseService::successCode) {
            return $this->renderPartial('_teachingPlan_details', array('teachingSearch' => $teachingSearch->data, 'id' => $id));
        }
    }

    /**
     *修改教学计划
     */
    public function actionEditPlan(){
        $jsonResult = new JsonMessage();
        $id = intval($_POST['id']);
        $planName = $_POST['planName'];
        $gradeID = $_POST['gradeID'];
        $url = $_POST['url'];
        $newUrl = ImagePathHelper::replace_pic($url);
        $brief = $_POST['brief'];
        $editModel = new pos_TeachingPlanInfoService();
        $editPlan = $editModel->teachingPlanSave($id, $planName, $gradeID, $brief, $newUrl, '');
        if ($editPlan->resCode === BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->message = "修改成功！";
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '修改失败！';
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     * 教学计划详细页
     * @param $id
     */
    public function actionDetails($id){

        $teachingPlan = new pos_TeachingPlanInfoService();
        $teachingSearch = $teachingPlan->teachingPlanDetaileSearch($id, '');
        if ($teachingSearch->resCode == BaseService::successCode) {
            return $this->render('details', array('teachingSearch' => $teachingSearch->data));
        }

    }
}