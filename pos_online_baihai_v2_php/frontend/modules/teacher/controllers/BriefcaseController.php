<?php
namespace frontend\modules\teacher\controllers;
use common\models\JsonMessage;
use frontend\components\helper\ImagePathHelper;
use frontend\components\TeacherBaseController;
use frontend\models\InformationPackForm;
use frontend\models\UploadmaterialsForm;
use frontend\services\apollo\Apollo_MaterialService;
use frontend\services\BaseService;
use frontend\services\pos\pos_SchoolTeacherService;
use frontend\services\pos\pos_TeacherMaterialService;
use yii\data\Pagination;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-15
 * Time: 下午3:05
 */
class BriefcaseController extends TeacherBaseController
{
    public $layout = 'lay_user';

    /**
     * 高：2014.10.17
     *公文包列表
     */
    public function actionBriefcaseList()
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 9;
        $teacherId = user()->id;
        $type = 2;
        $material = new pos_TeacherMaterialService();
        $modelList = $material->queryTeacherMaterial($type, '', '', '', $teacherId, '', '', '', $pages->getPage() + 1, $pages->pageSize, '');
        $pages->totalCount = intval($modelList->countSize);
        return $this->render('briefcaseList', array('modelList' => $modelList->list, 'pages' => $pages));
    }

    /**
     * 高：2014.10.17
     *公文袋添加
     */
    public function actionAddBriefcase()
    {
        $type = 2;
        $name = app()->request->getQueryParam('name', '');
        $departmentMemLimit = app()->request->getQueryParam('departmentMemLimit', 0);
        $stuLimit = app()->request->getParam('stuLimit', 0);
        $groupMemberLimit = app()->request->getParam('groupMemberLimit', 0);
        $teacherId = user()->id;
        $material = new pos_TeacherMaterialService();
        $teacherMaterial = $material->createTeacherMaterial($name, $type, $teacherId, $stuLimit, $groupMemberLimit, $departmentMemLimit, '');
        $jsonResult = new JsonMessage();
        if ($teacherMaterial->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "添加失败！";
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     *根据id获取公文包详细信息
     */
    public function actionGetBriefcaseId()
    {
        $id = app()->request->getParam('id', '');
        $getData = new pos_TeacherMaterialService();
        $model = $getData->getTeacherMaterial($id);
        if (!empty($model)) {
            return $this->renderPartial('_briefcase_view', array('id' => $id, 'model' => $model));
        }
    }

    /**
     * 高：2014.10.17
     *公文包修改
     */
    public function actionEditBriefcase()
    {
        $jsonResult = new JsonMessage();
        $teacherId = user()->id;
        $id = app()->request->getParam('id', '');
        $name = app()->request->getParam('name', '');
        $departmentMemLimit = app()->request->getParam('department', 0);
        $student = app()->request->getParam('student', 0);
        $group = app()->request->getParam('group', 0);
        $editData = new pos_TeacherMaterialService();
        $materialType = 2;
        $saveModel = $editData->updateTeacherMaterial($id, $teacherId, $name, $student, $group, $departmentMemLimit, $materialType, '');
        if ($saveModel->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '修改失败!';
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     * 高：2014.10.17
     * 获取id查询公文包列表
     * @param $id
     */
    public function actionGetList($id)
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 2;
        $type = app()->request->getParam('type', '');
        $pages->params =['id'=>$id,'type'=>$type];
        $getData = new pos_TeacherMaterialService();
        $model = $getData->queryTeacherMaterialDetail($id, "", "", $type, $pages->getPage() + 1, $pages->pageSize, "");
        $pages->totalCount = intval($model->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_briefcase_list_view', array('model' => $model->list, 'id' => $id, 'pages' => $pages));
            return;
        }
        if (!empty($model)) {
            return $this->render('getList', array('model' => $model->list, 'id' => $id, 'pages' => $pages,'packName'=>$model->packName));
        }
    }

    /**
     *高：214.10.22
     * 查询公文包详细内容
     */
    public function actionDetail($id)
    {

        $detail = new Apollo_MaterialService();
        $userId = user()->id;
        $model = $detail->getMaterialById($id,$userId,'');
        if ($model==null){
            return $this->notFound();
        }
        return $this->render('detail', array('model' => $model));
    }

    /**
     *添加下载次数
     */
    public function actionGetDownNum()
    {
        $id = app()->request->getParam('id', 0);
        $readNum = new Apollo_MaterialService();
        $model = $readNum->increaseDownNum($id, '');
        $jsonResult = new JsonMessage();
        if ($model->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->data = $model->data->downNum;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "添加失败！";
            return $this->renderJSON($jsonResult);
        }

    }


    /**
     * 高：2014.10.15
     *资料袋列表
     */
    public function actionDataList()
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 9;
        $type = 1;
        $teacherId = user()->id;
        $material = new pos_TeacherMaterialService();
        $materialList = $material->queryTeacherMaterial($type, '', '', '', $teacherId, '', '', '', $pages->getPage() + 1, $pages->pageSize, '');
        $pages->totalCount = intval($materialList->countSize);
        return $this->render('dataList', array('material' => $materialList->list, 'pages' => $pages));
    }

    /**
     * 高：2014.10.15
     *添加资料袋
     */
    public function actionAddMaterial()
    {
        $type = 1;
        $name = app()->request->getParam('name', '');
        $departmentMemLimit = app()->request->getParam('departmentMemLimit', 0);
        $stuLimit = app()->request->getParam('stuLimit', 0);
        $groupMemberLimit = app()->request->getParam('groupMemberLimit', 0);
        $teacherId = user()->id;
        $material = new pos_TeacherMaterialService();
        $teacherMaterial = $material->createTeacherMaterial($name, $type, $teacherId, $stuLimit, $groupMemberLimit, $departmentMemLimit, '');
        $jsonResult = new JsonMessage();
        if ($teacherMaterial->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "添加失败！";
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     * 高：2014.10.16
     *根据id查询出详情
     */
    public function actionGetDataBag()
    {
        $id = app()->request->getParam('id', '');
        $getData = new pos_TeacherMaterialService();
        $model = $getData->getTeacherMaterial($id);
        if ($model==null){
            return $this->notFound();
        }
            return $this->renderPartial('_dataBag_view', array('id' => $id, 'model' => $model));

    }


    /**
     * 高：2014.10.16
     *修改资料袋
     */
    public function actionEditDataBag()
    {
        $jsonResult = new JsonMessage();
        $teacherId = user()->id;
        $id = app()->request->getParam('id', '');
        $name = app()->request->getParam('name', '');
        $departmentMemLimit = app()->request->getParam('department', 0);
        $student = app()->request->getParam('student', 0);
        $group = app()->request->getParam('group', 0);
        $editData = new pos_TeacherMaterialService();
        $materialType = 1;
        $saveModel = $editData->updateTeacherMaterial($id, $teacherId, $name, $student, $group, $departmentMemLimit, $materialType, '');
        if ($saveModel->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '修改失败!';
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     * 高 2014.10.16
     * 根据id获取资料袋列表
     * @param $id
     */
    public function actionDetailsList($id)
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 2;
        $type = 6; //6 素材
        $getData = new pos_TeacherMaterialService();
        $model = $getData->queryTeacherMaterialDetail($id, "", "", $type, $pages->getPage() + 1, $pages->pageSize, "");
        $pages->totalCount = intval($model->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_folder_list_view', array('model' => $model->list, 'pages' => $pages, 'id' => $id));

        }
        if (!empty($model)) {
            return $this->render('detailsList', array('model' => $model->list, 'pages' => $pages, 'id' => $id,'packName'=>$model->packName));
        }
    }

    /**
     * 高 2014.10.16
     *资料袋上传资料
     */
    public function actionUploadInformation($id)
    {
        $dataBag = new InformationPackForm();
        $userId = user()->id;
        $userInfo = loginUser()->getModel();
        $dataBag->provience=$userInfo->provience;
        $dataBag->city =$userInfo->city;
        $dataBag->county =$userInfo->country;
        $teacher = new pos_SchoolTeacherService();
        $school = $teacher->teacherGetSchoolID($userId);
        $dataBag->type='1';
        if (isset($_POST['InformationPackForm'])) {
            $dataBag->attributes = $_POST['InformationPackForm'];
            $dataBag->school = $school;
            $dataBag->url = ImagePathHelper::replace_pic($dataBag->url);

            if ($dataBag->validate()) {
                $material = new Apollo_MaterialService();
                $result = $material->uploadMaterial($dataBag, $userId, $id);
                if ($result->resCode == BaseService::successCode) {

                    return $this->redirect(url('teacher/briefcase/get-list', array('id' => $id)));
                }
            }
        }


        return $this->render('uploadInformation', array('model' => $dataBag, 'id' => $id,'isEdit'=>'1'));
    }

    /**
     * 修改公文包上传
     * @param $id
     * @param $infoId
     */
    public function actionUpdateBriefcase($id,$infoId){
       $material = new Apollo_MaterialService();
       $dataBag = new UploadmaterialsForm();
       $userId = user()->id;
       $model = $material->getMaterialById($infoId,$userId, '');
          if (!empty($model)) {
           $dataBag->type = $model->matType;
           $dataBag->name = $model->name;
           $dataBag->provience = $model->provience;
           $dataBag->city = $model->city;
           $dataBag->county = $model->country;
           $dataBag->subjectID = $model->subjectid;
           $dataBag->grade = $model->gradeid;
           $dataBag->materials =$model->versionid;
           $dataBag->url = $model->url;
           $dataBag->brief = $model->matDescribe;
           $dataBag->chapKids = $model->chapKids;
           $dataBag->tags = $model->tags;
           $dataBag->contentType =$model->contentType;

       }else{
              return $this->notFound();
          }
       if (app()->request->isPost) {
           if (isset($_POST['UploadmaterialsForm'])) {
               $dataBag->attributes = $_POST['UploadmaterialsForm'];
               if ($dataBag->validate()) {
                   $result = $material->updateMaterial(
                       $infoId,
                       $dataBag->name,
                       $dataBag->provience,
                       $dataBag->city,
                       $dataBag->county,
                       $dataBag->grade,
                       $dataBag->subjectID,
                       $dataBag->materials,
                       $dataBag->contentType,
                       $dataBag->chapKids,
                       $dataBag->tags,
                       $dataBag->url,
                       $dataBag->brief

                   );
                   if ($result->resCode == BaseService::successCode) {
                       return $this->redirect(url('teacher/briefcase/get-list', array('id' => $id)));
                   }
               }
           }

       }
       return $this->render('uploadInformation', array('model' => $dataBag, 'id' => $id,'isEdit'=>'2' ));
   }
    /**
     *添加上传素材包
     */
    public function actionUploadPack($id)
    {
        $material = new Apollo_MaterialService();
        $dataBag = new UploadmaterialsForm();
        $userId = user()->id;
        $type = 6; //6为素材
        if (isset($_POST['UploadmaterialsForm'])) {
            $dataBag->attributes = $_POST['UploadmaterialsForm'];
            $dataBag->type = $type;
            $dataBag->url = ImagePathHelper::replace_pic($dataBag->url);
            if ($dataBag->validate()) {
                $result = $material->uploadMaterial($dataBag, $userId, $id);
                if ($result->resCode == BaseService::successCode) {
                    return $this->redirect(url('teacher/briefcase/details-list', array('id' => $id)));

                }
            }
        }
        return $this->render('uploadPack', array('model' => $dataBag, 'id' => $id));
    }

    /**
     * 修改上传素材
     * @param $id
     * @param $infoId
     */
    public function actionUpdateMaterial($id, $infoId)
    {
        $material = new Apollo_MaterialService();
        $dataBag = new UploadmaterialsForm();
        $userId = user()->id;
        $model = $material->getMaterialById($infoId,$userId,'' );
        if (!empty($model)) {
            $dataBag->type = $model->matType;
            $dataBag->name = $model->name;
            $dataBag->provience = $model->provience;
            $dataBag->city = $model->city;
            $dataBag->county = $model->country;
            $dataBag->subjectID = $model->subjectid;
            $dataBag->grade = $model->gradeid;
            $dataBag->school = $model->school;
            $dataBag->url = $model->url;
            $dataBag->brief = $model->matDescribe;
            $dataBag->chapKids = $model->chapKids;
            $dataBag->tags = $model->tags;

        }else{
            return $this->notFound();
        }
        if (app()->request->isPost) {
            if (isset($_POST['UploadmaterialsForm'])) {
                $dataBag->attributes = $_POST['UploadmaterialsForm'];

                if ($dataBag->validate()) {
                    $result = $material->updateMaterial(
                        $infoId,
                        $dataBag->name,
                        null,
                        null,
                        null,
                        null,
                        $dataBag->subjectID,
                        null,
                        null,
                        null,
                        $dataBag->tags,
                        $dataBag->url,
                        $dataBag->brief
                    );
                    if ($result->resCode == BaseService::successCode) {
                        return $this->redirect(url('teacher/briefcase/details-list', array('id' => $id)));
                    }
                }
            }

        }
        return $this->render('uploadPack', array('model' => $dataBag, 'id' => $id));
    }

    /**
     * 获取详情
     * @param $id
     */
    public function actionMaterialDetail($id)
    {

        $detail = new Apollo_MaterialService();
        $userId = user()->id;
        $model = $detail->getMaterialById($id,$userId,'');
        if($model==null){
            return $this->notFound();
        }
             return $this->render('materialDetail', array('model' => $model));

    }


    /**
     *高：2014.11.4
     * 获取学校所有教师
     */
    public function actionGetTeacher()
    {

        $schoolId = app()->request->getParam('schoolId', 0);
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 2;
        $school = new pos_SchoolTeacherService();
        $model = $school->queryAllTeacherBySchoolID($schoolId, $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($model->countSize);
        $pages->params['schoolId'] = $schoolId;
        return $this->renderPartial('_getTeacher_view', array('model' => $model->teacherList, 'pages' => $pages));
    }

    /**
     *获取讲义列表
     */
    public function actionGetDoc()
    {
        $id = app()->request->getParam('id', 0);
        $detailType = 2;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 2;
        $getData = new pos_TeacherMaterialService();
        $model = $getData->queryTeacherMaterialDetail($id, "", "", $detailType, $pages->getPage() + 1, $pages->pageSize, "");
        $pages->totalCount = intval($model->countSize);
        $pages->params['id'] = $id;
        return $this->renderPartial('_getDoc_view', array('model' => $model->list, 'pages' => $pages));
    }


}