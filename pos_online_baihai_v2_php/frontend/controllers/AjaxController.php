<?php

namespace frontend\controllers;

use common\models\JsonMessage;
use common\models\pos\SeFavoriteMaterial;
use common\models\pos\SeHomeworkRel;
use common\models\sanhai\SrChapter;
use common\models\sanhai\SrMaterial;
use common\services\JfManageService;
use Exception;
use frontend\components\BaseController;
use frontend\components\helper\AreaHelper;
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\KnowTreeHelper;
use frontend\components\helper\TreeHelper;
use frontend\components\WebDataKey;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\dicmodels\LoadSubjectModel;
use frontend\models\dicmodels\LoadTextbookVersionModel;
use frontend\models\dicmodels\QueryTypeModel;
use frontend\services\apollo\Apollo_BaseInfoService;
use frontend\services\apollo\Apollo_chapterInfoManage;
use frontend\services\apollo\Apollo_QuestionTypeService;
use frontend\services\BaseService;
use frontend\services\pos\pos_ClassInfoService;
use frontend\services\pos\pos_ClassMembersService;
use frontend\services\pos\pos_ExamService;
use frontend\services\pos\pos_MessageSentService;
use frontend\services\pos\pos_PersonalInformationService;
use frontend\services\pos\pos_TeachingGroupBinderService;
use frontend\services\pos\pos_UserRegisterService;
use frontend\services\pos\pos_UserSloganService;
use mithun\queue\services\QueueInterface;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


/**
 * Created by PhpStorm.
 * User: a
 * Date: 14-6-24
 * Time: 下午2:45
 */
class AjaxController extends BaseController
{

    /**
     * 队列名称
     */
    const  QUEUE_TYPE = 'queue_urge_home_work';

    /**
     *
     * 城市查询联动
     */
    public function actionGetArea($id)
    {
        echo Html::tag('option', '请选择', array('value' => ''));
        if (empty($id)) {
            return;
        }
        $data = AreaHelper::getList($id);
        foreach ($data as $item) {
            echo Html::tag('option', Html::encode($item["AreaName"]), array('value' => $item["AreaID"]));
        }


    }

    /**
     *
     * 城市查询联动
     */
    public function actionGetJsonArea($id)
    {
        $data = AreaHelper::getList($id);
        return $this->renderJSON($data);
    }

    /**
     *
     * 城市查询联动
     */
    public function actionGetJsonProvinceList()
    {
        $data = AreaHelper::getProvinceList();
        return $this->renderJSON($data);
    }

    /*
     * 根据年级查科目
     */
    public function actionGetItemForGrade($id)
    {
        echo Html::tag('option', '请选择', array('value' => ''));
        if (empty($id)) {
            return;
        }
        $obj = new Apollo_BaseInfoService();
        $data = $obj->loadSubjectByGrade($id, '');
        if ($data->resCode === BaseService::successCode) {
            foreach ($data->data->list as $item) {
                echo Html::tag('option', Html::encode($item->subjectName), array('value' => $item->subjectId));
            }
        }

    }

    /*
     * 根据年级、科目 查询题型
     */
    public function actionGetTopicType($grade, $subject)
    {
        echo Html::tag('option', '请选择', array('value' => ''));
        if (empty($grade) || empty($subject)) {
            return;
        }
        $obj = new Apollo_QuestionTypeService();
        $data = $obj->queryQuesTypeByGrade($grade, $subject);
        foreach ($data as $item) {
            echo Html::tag('option', Html::encode($item->typeName), array('value' => $item->typeId));
        }

    }

    /*
     * 根据大题ID 查询小题 题型
     */
    public function actionGetChildType($id)
    {
        echo Html::tag('option', '请选择', array('value' => ''));
        if (empty($id)) {
            return;
        }
        $obj = new Apollo_QuestionTypeService();
        $data = $obj->queryQuesTypeSubs($id);
        if ($data->resCode === BaseService::successCode) {
            foreach ($data->data->list as $item) {
                echo Html::tag('option', Html::encode($item->subjectName), array('value' => $item->subjectId));
            }
        }
    }

    /**
     * 查询本班教师考试列表
     * @param $id
     */
    public function actionGetExam()
    {
        $id = app()->request->getQueryParam('id', '');
        echo Html::tag('option', '请选择', array('value' => ''));
        if (empty($id)) {
            return;
        }
        $exams = new pos_ExamService();
        $examList = $exams->queryExamList($id, user()->id, null, null, null);
        if ($examList->resCode === BaseService::successCode) {
            foreach ($examList->data->examList as $item) {
                echo Html::tag('option', Html::encode($item->examName), array('value' => $item->examID));
            }
        }

    }

    /**
     *  ajax验证邮箱
     */
    public function actionCheckEmail()
    {

        $_fieldId = $_GET['fieldId'];
        $email = $_GET['fieldValue'];
        $student = new  pos_UserRegisterService();
        echo json_encode(array($_fieldId, !$student->emailIsExist($email)));
    }


    /**
     *  ajax验证邮箱
     */
    public function actionCheckPhone()
    {

        $_fieldId = $_GET['fieldId'];
        $phone = $_GET['fieldValue'];
        $student = new  pos_UserRegisterService();
        echo json_encode(array($_fieldId, !$student->phoneIsExist($phone)));
    }


    /**
     *  裁剪图片
     */
    public function actionImage()
    {
        $oldname = $_POST['name'];
        $x = $_POST['x'];
        $y = $_POST['y'];
        $width = $_POST['width'];
        $height = $_POST['height'];
        $oldPic = \Yii::getPathOfAlias('webroot') . $oldname;

        $newPicName = "header" . rand(1, 1000) . basename($oldPic);
        $path = dirname($oldPic);


        $image = \Gregwar\Image\Image::open($oldPic);
        $newImagePath = $path . '/' . $newPicName;
        $image->crop($x, $y, $width, $height)->save($path . '/' . $newPicName);
        //缩略图
        $thumbImage = \Gregwar\Image\Image::open($newImagePath);
        $thumbImage->cropResize(200, 200)->save($path . '/200x200_' . $newPicName);
        $thumbImage = \Gregwar\Image\Image::open($newImagePath);
        $thumbImage->cropResize(100, 100)->save($path . '/100x100_' . $newPicName);
        $thumbImage = \Gregwar\Image\Image::open($newImagePath);
        $thumbImage->cropResize(50, 50)->save($path . '/50x50_' . $newPicName);


        $paths = explode('/', $oldname);
        array_pop($paths);
        echo implode('/', $paths) . '/' . $newPicName;
    }

    public function actionImagePic()
    {
        $oldname = $_POST['name'];
        $x = $_POST['x'];
        $y = $_POST['y'];
        $width = $_POST['width'];
        $height = $_POST['height'];
        $oldPic = \Yii::getAlias('@webroot') . $oldname;

        $newPicName = "header" . rand(1, 1000) . basename($oldPic);
        $path = dirname($oldPic);


        $image = \Gregwar\Image\Image::open($oldPic);
        $newImagePath = $path . '/' . $newPicName;
        $image->crop($x, $y, $width, $height)->save($path . '/' . $newPicName);
        //缩略图
        $thumbImage = \Gregwar\Image\Image::open($newImagePath);
        $thumbImage->cropResize(230, 230)->save($path . '/230x230_' . $newPicName);
        $thumbImage = \Gregwar\Image\Image::open($newImagePath);
        $thumbImage->cropResize(70, 70)->save($path . '/70x70_' . $newPicName);
        $thumbImage = \Gregwar\Image\Image::open($newImagePath);
        $thumbImage->cropResize(50, 50)->save($path . '/50x50_' . $newPicName);

        $paths = explode('/', $oldname);
        array_pop($paths);
        $headPic = implode('/', $paths) . '/' . $newPicName;

        $student = new pos_PersonalInformationService();
        $result = $student->updateHeadImg(user()->id, $headPic);
        if ($result) {
//                    修改头像增加积分
            $jfHelper = new JfManageService;
            $jfHelper->myAccount("pos-picture", user()->id);
        }
//        \Yii::$app->getUser()->switchIdentity()
//		user()->switchIdentity(User::findIdentity(user()->id));
        $jsonResult = new JsonMessage();
        $jsonResult->success = $result;
        return $this->renderJSON($jsonResult);
    }


    /*
     * 请求章节数据 高
     */
    public function actionChapter()
    {
        $jsonResult = new JsonMessage();
        $subject = $_POST['subject'];
        $grade = $_POST['grade'];
        $edition = $_POST['edition'];
        $schoolLevel = $_POST['schoolLevel'];
        $schoolLength = $_POST['schoolLength'];

        $chapterInfo = new Apollo_chapterInfoManage();
        $subjectModel = $chapterInfo->chapterSearch($subject, $schoolLevel, $edition, $schoolLength, $grade);
        if ($subjectModel->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->data = $subjectModel->data->cPointList;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "没有多余的数据";
            return $this->renderJSON($jsonResult);
        }
    }


    /**
     *根据学部获取科目
     */
    public function actionGetSubject($schoolLevel)
    {
        echo Html::tag('option', '请选择', array('value' => ''));
        if (empty($schoolLevel)) {
            return;
        }
        $data = LoadSubjectModel::model()->getData($schoolLevel, 1);
        foreach ($data as $item) {
            echo Html::tag('option', Html::encode($item->subjectName), array('value' => $item->subjectId));
        }
    }

    /**
     * 根据科目查询版本
     * @param $subject
     */
    public function actionGetVersion($subject, $prompt = true, $grade = null)
    {
        if ($prompt) {
            echo Html::tag('option', '请选择', array('value' => ''));
        }
        if (empty($subject)) {
            return;
        }
        $data = LoadTextbookVersionModel::model($subject, $grade)->getListData();
        foreach ($data as $key => $item) {
            echo Html::tag('option', Html::encode($item), array('value' => $key));
        }
    }

    /**
     * 根据 学段 科目查询版本
     * @param $subject
     */
    public function actionGetVersions($subject, $department)
    {

        echo Html::tag('option', '请选择', array('value' => ''));
        if (empty($subject) || empty($department)) {
            return;
        }
        $data = LoadTextbookVersionModel::model($subject, null, $department)->getListData();
        foreach ($data as $key => $item) {
            echo Html::tag('option', Html::encode($item), array('value' => $key));
        }
    }

    /**
     *根据科目查询题型
     */
    public function actiongetTopic($subject, $schoolLevel)
    {

        echo Html::tag('option', '请选择', array('value' => ''));
        if (empty($schoolLevel) || empty($subject)) {
            return;
        }
        $data = QueryTypeModel::queryQuesType($schoolLevel, $subject);
        foreach ($data as $item) {
            echo Html::tag('option', Html::encode($item->typeName), array('value' => $item->typeId));
        }
    }

    /*
         *  根据学制更改年级
         */
    public function actionChangeGrade()
    {
        $schoolLength = app()->request->getQueryParam('schoolLength');
        $data = GradeModel::model()->getList($schoolLength);
        $data = ArrayHelper::map($data, 'id', 'name');

        return $this->renderJSON($data);

    }

    /**
     *  手拉手申请
     */
    public function actionApplyShoulashou()
    {
        $classIdA = $_POST['classIdA'];
        $classIdB = $_POST['classIdB'];

        $classInfo = new pos_ClassInfoService();
        $result = $classInfo->requestClassBinder($classIdA, $classIdB, loginUser()->getModel()->trueName);

        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
            $jsonResult->data = $result->resMsg;
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     *  处理手拉手申请
     */
    public function actionHandleShoulashou()
    {
        $classId = app()->request->post('classId');
        $connectId = app()->request->post('connectId');
        $reason = app()->request->post('reason');
        $msgCode = app()->request->post('msgCode');

        $classInfo = new pos_ClassInfoService();
        $result = $classInfo->saveClassResponse($connectId, $classId, $reason, $msgCode);

        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
            $jsonResult->data = $result->resMsg;
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     *  班级人员管理页面修改教师信息
     */
    public function actionSaveTeachJob()
    {
        $classId = app()->request->post('classid');
        $classMemID = app()->request->post('classMemid');
        $identity = app()->request->post('identity');
        $subject = app()->request->post('subject');

        $classMemeber = new pos_ClassMembersService();
        $jsonResult = new JsonMessage();

        if ($this->MasterClassByClass($classId)) {
            $result = $classMemeber->saveTeachJob($classId, $classMemID, $identity, $subject);

            if ($result->resCode == BaseService::successCode) {
                $jsonResult->success = true;
            } else {
                $jsonResult->success = false;
                $jsonResult->data = $result->resMsg;
            }
        }

        return $this->renderJSON($jsonResult);
    }

    /**
     *  班级人员管理页面修改学生信息
     */
    public function actionSaveStudentJob()
    {
        $classMemID = app()->request->post('classmemid');
        $duty = app()->request->post('classduty');
        $classId = app()->request->post('classId', '');
        $stuId = app()->request->post('stuID', '');

        $classMember = new pos_ClassMembersService();
        $jsonResult = new JsonMessage();

        if ($this->MasterClassByClass($classId)) {
            $result = $classMember->saveStudentMembers($classId, $classMemID, '', $stuId, $duty);

            if ($result->resCode == BaseService::successCode) {
                $jsonResult->success = true;
            } else {
                $jsonResult->success = false;
                $jsonResult->data = $result->resMsg;
            }
        }

        return $this->renderJSON($jsonResult);
    }

    /**
     *  添加班级学生
     */
    public function actionAddStudent()
    {


        $classId = app()->request->post('classId');
        $memName = app()->request->post('memName');
        $stuID = app()->request->post('stuID');
        $classduty = app()->request->post('classduty');

        $classMember = new pos_ClassMembersService();
        $jsonResult = new JsonMessage();

        if ($this->MasterClassByClass($classId)) {
            $data = $classMember->loadRegisteredMembers($classId);
            //获取未注册的成员
            $unRegData = $classMember->loadNoRegMembers($classId);

            //班级总成员名单
            $names = array_merge($data, $unRegData);
            foreach ($names as $val) {
                if ($stuID != '' && $stuID === $val->stuID) {
                    $jsonResult->data = "学号已存在！";
                    return $this->renderJSON($jsonResult);


                } else if ($memName == $val->memName) {
                    $jsonResult->success = false;
                    if ($stuID == '') {
                        $jsonResult->data = "请输入学号！";
                        return $this->renderJSON($jsonResult);

                    } else if ($stuID == $val->stuID) {
                        $jsonResult->data = "学号已存在！";
                        return $this->renderJSON($jsonResult);

                    }
                }
            }
            $result = $classMember->saveStudentMembers($classId, '', $memName, $stuID, $classduty);

            $jsonResult = new JsonMessage();
            if ($result->resCode == BaseService::successCode) {
                $jsonResult->success = true;
            } else {
                $jsonResult->success = false;
                $jsonResult->data = $result->resMsg;
            }
        }


        return $this->renderJSON($jsonResult);


    }

    /**
     *  删除班级成员
     */
    public function actionRemoveMember()
    {
        $classMemID = app()->request->post('classmemid');

        $classMember = new pos_ClassMembersService();
        $result = $classMember->removeMember($classMemID);

        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
            $jsonResult->data = $result->resMsg;
        }
        return $this->renderJSON($jsonResult);
    }

    /*
     *applyShoulashougroup
     *添加教研组手拉手
     */
    public function actionApplyShoulashougroup()
    {
        $groupIdA = $_POST['groupIdA'];
        $groupIdB = $_POST['groupIdB'];
        $groupInfo = new pos_TeachingGroupBinderService();
        $result = $groupInfo->requestTeaGroupBinder($groupIdA, $groupIdB, loginUser()->getModel()->trueName);

        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
            $jsonResult->data = $result->resMsg;
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     *  处理手拉手教研组申请
     */
    public function actionHandleShoulashougroup()
    {
        $groupId = app()->request->getQueryParam('groupId');
        $connectId = app()->request->getQueryParam('connectId');
        $reason = app()->request->getQueryParam('reason');
        $msgCode = app()->request->getQueryParam('msgCode');

        $groupInfo = new pos_TeachingGroupBinderService();
        $result = $groupInfo->saveGroupResponse($connectId, $groupId, $reason, $msgCode);
        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
            $jsonResult->data = $result->resMsg;
        }
        return $this->renderJSON($jsonResult);
    }

    public function actionGetKnowlage()
    {
        $str = app()->request->getQueryParam('str');
        $konwlage = KnowledgePointModel::findKnowledgeArrVal($str);
        return $this->renderJSON($konwlage);
    }

    public function actionAjaxUserSlogan()
    {
        $slogan = app()->request->getQueryParam('data', '');
        $obj = new pos_UserSloganService();
        $result = $obj->modifyUserSlogan(user()->id, $slogan);
        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            return $this->renderJSON($jsonResult);
        }
    }

    //点击下载，不是浏览器打开，而是之家下载
    public function actionDownloadImg($id)
    {

        $srMaterial = \common\models\sanhai\SrMaterial::findOne($id);
        $file = \Yii::getAlias('@webroot') . $srMaterial->url;
        $name = $srMaterial->name;

        header("Content-type: octet/stream");
        header("Content-disposition: attachment; filename=" . $name . ";");
        header("Content-Length: " . filesize($file));
        readfile($file);
        exit;
    }


    //点击下载，不是浏览器打开，而是直接下载
    public function actionDownloadFile($id)
    {
        $srMaterial = \common\models\sanhai\SrMaterial::findOne($id);

        try {
            $url = $srMaterial->url;
            if (stripos($url, '/upload') !== 0) {
                $url = '/res' . $url;
            }
            $file = \Yii::getAlias('@webroot') . $url;
            $name = $srMaterial->name;
            $flag = parse_user_agent();
            if ($flag['browser'] == 'MSIE') {
                $name = urlencode($name);
            }
            $filehand = @fopen($file, 'r');
            $name = $name . '.' . strtolower(strrchr($file, '.'));
            yii::$app->response->sendStreamAsFile($filehand, $name)->send();
        } catch (Exception $e) {
            echo "无法下载";
        }

    }

    //点击下载，不是浏览器打开，而是直接下载
    public function actionNewDownloadFile($id)
    {

        $material = SrMaterial::find()->where(['id' => $id])->one();
        $url = $material->url;
        $name = $material->name;

        try {

            if (stripos($url, '/upload') !== 0) {
                $url = '/res' . $url;
            }
            $file = \Yii::getAlias('@webroot') . $url;
            $flag = parse_user_agent();
            if ($flag['browser'] == 'MSIE') {
                $name = urlencode($name);
            }
            $filehand = @fopen($file, 'r');
            $name = $name . '.' . strtolower(strrchr($file, '.'));
            yii::$app->response->sendStreamAsFile($filehand, $name)->send();

            //增加下载次数
            $material->downNum = $material->downNum + 1;
            $material->save(false);
        } catch (Exception $e) {
            echo "无法下载";
        }
    }

    /**
     * 班级文件详情
     */
    public function actionFileDetails()
    {

        $id = app()->request->getQueryParam('id');
        $url = app()->request->getQueryParam('url');

        $materialModel = SrMaterial::find()->where(['id' => $id])->one();
        $materialModel->readNum = $materialModel->readNum + 1;
        $materialModel->save(false);

        if (ImagePathHelper::judgeImage($url)) {
            $this->redirect(ImagePathHelper::resUrl1($url));
        } else {
            $this->redirect('http://officeweb365.com/o/?i=5362&furl=' . urlencode(ImagePathHelper::resUrl1($url)));
        }

    }

    /**
     * 收藏讲义
     */
    public function actionCollect()
    {
        $jsonResult = new JsonMessage();
        $favoriteId = app()->request->post('id');
        $matType = app()->request->post('type');
        $userId = user()->id;

        $srMaterial = SrMaterial::getMaterialInfo($favoriteId);     //创建默认分组的科目——使用文件自身的学段，科目
        if (empty($srMaterial)) {
            $jsonResult->message = '收藏失败！';
            return $this->renderJSON($jsonResult);
        }

        if ($srMaterial->creator == $userId) {
            $jsonResult->message = '自己上传的课件不能收藏！';
            return $this->renderJSON($jsonResult);
        }


        $totalNumResult = SeFavoriteMaterial::getTotalMaterialNum($userId);
        if($totalNumResult >= 1000){
            $jsonResult->message = '已经达到最大收藏数1000！';
            return $this->renderJSON($jsonResult);
        }

        $result = SeFavoriteMaterial::materialCollect($srMaterial, $favoriteId, $userId, $matType);    //收藏课件操作

        if($result == false){
            $jsonResult->message = '收藏失败！';
        }else{
            $jsonResult->success = true;
            $jsonResult->message = '收藏成功！';
        }

        return $this->renderJSON($jsonResult);
    }

    /**
     * 取消收藏
     */
    public function actionCancelCollect()
    {
        $jsonResult = new JsonMessage();
        $favoriteId = app()->request->post('id');
        $userId = user()->id;

        $result = SeFavoriteMaterial::materialCancelCollect($favoriteId ,$userId );     //取消收藏课件操作

        if($result == false){
            $jsonResult->message = '取消收藏失败！';
        }else{
            $jsonResult->success = true;
            $jsonResult->message = '取消收藏成功！';
        }

        return $this->renderJSON($jsonResult);
    }

    /**
     * 加载整个页面判断每个课件是否被收藏了
     */
    public function actionFileIsCollected()
    {
        $jsonResult=new JsonMessage();
        $materialIdArray = Yii::$app->request->post('materialIdArray');

        $result = SeFavoriteMaterial::getMaterialIsCollected($materialIdArray, user()->id);     //课件是否收藏

        $jsonResult->data=$result;
        return $this->renderJson($jsonResult);
    }

    /**
     * 检查是否签到
     */
    public function actionCheckSign()
    {
        $jsonResult = new JsonMessage();

        $cache = Yii::$app->cache;
        $key = WebDataKey::USER_IS_SIGN . user()->id;
        $data = $cache->get($key);

        if ($data === false) {
            $jsonResult->success = false;
        } else {
            $jsonResult->success = true;
        }

        return $this->renderJSON($jsonResult);
    }

    /**
     * 签到积分
     */
    public function actionSign()
    {
        $jsonResult = new JsonMessage();
        $jfHelper = new JfManageService();
        $result = $jfHelper->Sign(user()->id);

        $key = WebDataKey::USER_IS_SIGN . user()->id;
        $endTime = strtotime(date('Y-m-d 23:59:00', time()));
        $nowTime = strtotime(date('Y-m-d H:i:s', time()));
        if ($endTime - $nowTime > 0) {
            Yii::$app->cache->set($key, true, $endTime - $nowTime);
        }
        $jsonResult->success = true;
        $jsonResult->code = 1;
        return $this->renderJSON($jsonResult);
    }

    /**
     * 获取分册
     */
    public function actionGetSection($versionId = null)
    {

        $versionId = app()->request->post('versionId');

        //获取科目,学段
        $subjectid = loginUser()->getModel()->subjectID;
        $departments = loginUser()->getModel()->department;

        $chapterTomeModel = new Apollo_chapterInfoManage();
        $chapterTomeResult = $chapterTomeModel->chapterBaseNodeSearchList($subjectid, $departments, $versionId, null, null);

        foreach ($chapterTomeResult as $key => $item) {
            echo Html::tag('option', Html::encode($item), array('value' => $key));
        }

    }

    /**
     * 获取章节
     */
    public function actionGetChaplist()
    {

        $sectionId = app()->request->post('sectionId', '');
        //获取科目,学段
        $subjectid = loginUser()->getModel()->subjectID;
        $departments = loginUser()->getModel()->department;
        $obj = SrChapter::find()->where(['bookAtt' => $sectionId])->select('cid,pid,chaptername')->all();
        $chapterTree = KnowTreeHelper::chapterMakeTree($obj, '', '', '', '', '', 0, '', '');
        $treeData = TreeHelper::streefun($chapterTree, "", "tree pointTree");
        return $treeData;
    }

    /**
     * 积分兑换
     */
    public function actionJfExchange()
    {

        $goodsId = app()->request->post('goodsId');
        $jfManageModel = new JfManageService();
        $result = $jfManageModel->JfExchange(user()->id, $goodsId);

        return $this->renderJSON($result);

    }

    /**
     * 催作业
     * 班级作业列表 和 教师个人中心作业列表共用
     * @return string
     */
    public function actionUrgeHomework()
    {
        $jsonResult = new JsonMessage();
        $relId = app()->request->post('relId');
        try {
            $relHomeworkQuery = SeHomeworkRel::find()->where(['id' => $relId, 'isSendMsgStudent' => 0])->one();
            if (empty($relHomeworkQuery)) {
                $jsonResult->success = false;
                $jsonResult->message = "催作业失败";
            } else {
                $relHomeworkQuery->isSendMsgStudent = 1;
                if ($relHomeworkQuery->save()) {
                    /** @var QueueInterface $queue */
                    $queue = \yii::$app->queue;
                    $queue->push($relId, self::QUEUE_TYPE);
                    $jsonResult->success = true;
                }
            }

        } catch (Exception $e) {

        }
        return $this->renderJSON($jsonResult);
    }


    /**
     * 网站头部导航 通知数字显示
     * @return string
     */
    public function actionMsgNum()
    {
        $userId = user()->id;
        $obj = new pos_MessageSentService();
        $cache = Yii::$app->cache;
        $key = WebDataKey::TOP_NAV_MSG_NUM_CACHE_KEY . "_" . $userId;
        $data = $cache->get($key);
        if($data === false)
        {
            $data = $obj->stasticUserMessage($userId);
            if(!empty($data))
            {
                $cache->set($key, $data,300);
            }
        }
        return $this->renderJSON($data);
    }
} 