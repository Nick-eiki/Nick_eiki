<?php
namespace frontend\controllers;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-9-4
 * Time: 上午11:11
 */
use common\models\JsonMessage;
use common\models\pos\SeAnswerQuestion;
use common\models\pos\SeSchoolPublicity;
use common\models\pos\SeUserinfo;
use frontend\components\BaseAuthController;
use frontend\components\WebDataCache;
use frontend\models\BriefForm;
use frontend\models\PublicityForm;
use frontend\models\SchoolForm;
use frontend\services\BaseService;
use frontend\services\pos\pos_ClassInfoService;
use frontend\services\pos\pos_ClassMembersService;
use frontend\services\pos\pos_EnrollmentGuideInfoService;
use frontend\services\pos\pos_PersonalInformationService;
use frontend\services\pos\pos_PointLineInfoService;
use frontend\services\pos\pos_SchoolInfoService;
use frontend\services\pos\pos_SchoolSloganService;
use frontend\services\pos\pos_TeachingGroupService;
use Yii;
use yii\data\Pagination;

class SchoolController extends BaseAuthController
{
    /**
     * @var string
     */
    public $layout = 'lay_new_school';

    /**
     *学校主页
     * @param $schoolId
     */
    public function actionIndex($schoolId)
    {
        $this->layout = 'main';

        $schoolModel = $this->getSchoolModel($schoolId);

        $pointLineSearch = new pos_PointLineInfoService();
        $pointLineSearchList = $pointLineSearch->search_curr($schoolId);

        return $this->render("index", ['schoolModel' => $schoolModel, 'pointLineSearchList' => $pointLineSearchList]);
    }

    /**
     *
     * 学校公示
     */
    public function actionPublicity($schoolId)
    {
        $this->getSchoolModel($schoolId);
        $publicityType = Yii::$app->request->getParam("publicityType", 1);
        $quData = \common\models\pos\SeSchoolPublicity::find()->where(["schoolID" => $schoolId]);

        if ($publicityType != null) {
            $quData->andWhere(array('publicityType' => $publicityType));

        }

        $pages = new Pagination();
        $pages->pageSize = 5;
        $pages->totalCount = $quData->count();
        $pages->params["publicityType"] = $publicityType;
        $pages->params["schoolId"] = $schoolId;
        $pages->params["page"] = Yii::$app->request->get("page", 1);
        $publicityList = $quData->orderBy("updateTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();

        if (app()->request->isAjax) {

            return $this->renderPartial("_publicity_list", array('schoolId' => $schoolId, "publicityList" => $publicityList, "pages" => $pages));

        }


        return $this->render('publicity', array('schoolId' => $schoolId, "publicityList" => $publicityList, "pages" => $pages));
    }

    /**
     *
     * 添加公示
     */
    public function actionNewPublicity($schoolId)
    {
        $this->getSchoolModel($schoolId);
        if (WebDataCache::getSchoolId(user()->id) == $schoolId) {
            $userId = user()->id;
            $publicityForm = new PublicityForm();
            $userName = SeUserinfo::find()->where(["userID" => $userId])->one()->trueName;
            if ($_POST) {
                $model = new \common\models\pos\SeSchoolPublicity();
                if (!empty($_POST['PublicityForm']['imageUrl'])) {
                    $imageUrl = implode(",", $_POST['PublicityForm']['imageUrl']);
                    $model->imageUrl = $imageUrl;
                }
                $model->publicityContent = $_POST["PublicityForm"]["publicityContent"];
                $model->publicityType = $_POST["PublicityForm"]["publicityType"];
                $model->publicityTitle = $_POST["PublicityForm"]["publicityTitle"];
                $model->schoolID = $schoolId;
                $model->userName = $userName;
                $model->userID = $userId;
                $model->createTime = time() * 1000;
                $model->updateTime = time() * 1000;
                if ($model->save()) {
                    return $this->redirect('publicity');
                }
            }
            return $this->render('newPublicity', array('schoolId' => $schoolId, "publicityForm" => $publicityForm));
        } else {
            $this->notFound('非本校人员！', 403);
        }

    }

    /**
     *
     * 更新公示
     */
    public function actionUpdatePublicity($schoolId)
    {
        $this->getSchoolModel($schoolId);
        if (WebDataCache::getSchoolId(user()->id) == $schoolId) {
            $publicityId = app()->request->getQueryParam('publicityId', '');
            $model = SeSchoolPublicity::find()->where(['publicityId' => $publicityId])->one();
            if ($model) {
                $userName = \common\models\pos\SeUserinfo::find()->where(["userID" => user()->id])->one()->trueName;
                $arr = explode(',', $model['imageUrl']);
                if ($_POST) {
                    $model->load(yii::$app->request->post());
                    if (isset($_POST['Model']['imgUrl'])) {
                        $arr = implode(",", $_POST['Model']['imgUrl']);
                        $model->imageUrl = $arr;
                    } else {
                        $model->imageUrl = '';
                    };
                    $model->updateTime = time() * 1000;
                    $model->userName = $userName;
                    if ($model->save()) {
                        return $this->redirect('publicity');
                    }
                }
                return $this->render('updatePublicity', array('schoolId' => $schoolId, "model" => $model, 'imageUrl' => $arr));
            } else {
                $this->notFound();
            }
        } else {
            $this->notFound('非本校人员！', 403);
        }
    }


    /**
     *
     * 公示详情
     */
    public function actionPublicityDetails($schoolId)
    {
        $this->getSchoolModel($schoolId);
        $publicityId = app()->request->getQueryParam("publicityId");

        $qu = SeSchoolPublicity::find()->where(['schoolId' => $schoolId]);
        $cur = clone $qu;
        $qu_last = clone $qu;

        $quData = $cur->andWhere(["publicityId" => $publicityId])->one();
        if ($quData) {
            $updateTime = $quData->updateTime;
            $publicityType = $quData->publicityType;
//        下一篇
            $nextData = $qu->andWhere([">", "updateTime", $updateTime])->andWhere(['publicityType' => $publicityType])->orderBy("updateTime asc")->one();
//        上一篇
            $lastData = $qu_last->andWhere(["<", "updateTime", $updateTime])->andWhere(['publicityType' => $publicityType])->orderBy("updateTime desc")->one();
            return $this->render('publicityDetails', array('schoolId' => $schoolId, "quData" => $quData, "nextData" => $nextData, "lastData" => $lastData));
        } else {
            $this->notFound();
        }
    }


    /**
     *编辑学校信息
     * @param $schoolId
     */
    public function actionSchoolEditor($schoolId)
    {
        $this->getSchoolModel($schoolId);
        $schoolModel = new SchoolForm();
        $school = new pos_SchoolInfoService();
        $schoolSelect = $school->searchSchoolInfoById($schoolId);
        if ($_POST) {
            $schoolModel->attributes = $_POST["SchoolForm"];
            $schoolModel->schoolID = $schoolId;
            if (!empty($schoolModel->beginTime)) {
                $addNewLen = $school->addNewLenOfSch($schoolId, $schoolModel->lengthOfSchooling, $schoolModel->beginTime);

            }

            $schoolUpdate = $school->updateSchoolinfo($schoolModel);
            $logoUpdate = $school->modifySchoolLogo($schoolId, $schoolModel->faceIcon);

            if ($logoUpdate->resCode == pos_SchoolInfoService::successCode) {

                if ($schoolUpdate->resCode == pos_SchoolInfoService::successCode) {
                    return $this->redirect(url("school/index", array("schoolId" => $schoolId)));
                }
            }
        }

        return $this->render("schoolEditor", array("schoolModel" => $schoolModel,
            "schoolSelect" => $schoolSelect, "schoolId" => $schoolId));
    }

    /**
     * @param $schoolId
     * 校内组织管理
     */
    public function actionManageGroup($schoolId)
    {

        $this->getSchoolModel($schoolId);
        $teaching = new pos_TeachingGroupService();
//        小学教研组列表
        $primaryTeachingList = $teaching->searchTeachingGroup($schoolId, null, null, "20201", null, null, null, null);
//        初中教研组列表
        $juniorTeachingList = $teaching->searchTeachingGroup($schoolId, null, null, "20202", null, null, null, null);
//        高中教研组列表
        $highTeachingList = $teaching->searchTeachingGroup($schoolId, null, null, "20203", null, null, null, null);

        return $this->render("manageGroup", array("schoolId" => $schoolId, "primaryTeachingList" => $primaryTeachingList,
            "juniorTeachingList" => $juniorTeachingList, "highTeachingList" => $highTeachingList,
        ));
    }

    /**
     * @param $schoolId
     */
    public function actionManageClass($schoolId)
    {
        $this->getSchoolModel($schoolId);
        $class = new pos_ClassInfoService();
//        小学部班级列表
        $primaryClassList = $class->searchClassInfo($schoolId, null);
//        小学部年级列表
        $primaryYearArray = array();
        foreach ($primaryClassList->classList as $v) {
            if (!in_array($v->joinYear, $primaryYearArray)) {
                array_push($primaryYearArray, $v->joinYear);
            }
        }
//        初中部班级列表
        $juniorClassList = $class->searchClassInfo($schoolId, "20202");
//        初中部年级列表
        $juniorYearArray = array();
        foreach ($juniorClassList->classList as $v) {
            if (!in_array($v->joinYear, $juniorYearArray)) {
                array_push($juniorYearArray, $v->joinYear);
            }
        }
//        高中部班级列表
        $highClassList = $class->searchClassInfo($schoolId, "20203");
//        高中部年级列表
        $highYearArray = array();
        foreach ($highClassList->classList as $v) {
            if (!in_array($v->joinYear, $highYearArray)) {
                array_push($highYearArray, $v->joinYear);
            }
        }
        return $this->render("manageClass", array("schoolId" => $schoolId, "primaryYearArray" => $primaryYearArray, "juniorYearArray" => $juniorYearArray, "highYearArray" => $highYearArray));
    }

    /**
     * @param $schoolId
     */
    public function actionClassList($schoolId)
    {

        return $this->render("classList");
    }

    /**
     *添加招生简章
     */
    public function actionAddBrief($schoolId)
    {
        //权限验证
        $this->getSchoolModel($schoolId);
        if (!loginUser()->isTeacher() || !loginUser()->getTeacherInSchool($schoolId)) {
            return $this->notFound();
        }

        $model = new BriefForm();

        if (isset($_POST['BriefForm'])) {
            $model->attributes = $_POST["BriefForm"];

            $brief = new pos_EnrollmentGuideInfoService();
            $briefResult = $brief->EnrollmentGuideAdd($model->name, $model->schoolLevel, $model->year, $schoolId, $model->content, user()->id);


            if ($briefResult->resCode == BaseService::successCode) {
                return $this->redirect(url('school/brieflist', array('schoolId' => $schoolId)));
            }
        }
        return $this->render("addBrief", array("model" => $model));
    }

    /**
     *  招生简章列表
     */
    public function actionBriefList($schoolId)
    {
        $this->getSchoolModel($schoolId);
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;

        $year = app()->request->getQueryParam('year', '');
        $schoolLevel = app()->request->getQueryParam('schoolLevel', '');
        $pages->params = array('year' => $year, 'schoolLevel' => $schoolLevel, 'schoolId' => $schoolId);

        $enrollment = new pos_EnrollmentGuideInfoService();

        $result = $enrollment->enrollmentGuideSearch($schoolLevel, $schoolId, $year, $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($result->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_brief_data', array('data' => $result->enrollmentGuidelist, 'pages' => $pages));

        }

        return $this->render('brieflist', array('data' => $result->enrollmentGuidelist, 'pages' => $pages));
    }

    /**
     *  招生简章详情
     */
    public function actionBriefView($id, $schoolId)
    {
        $this->getSchoolModel($schoolId);
        $enro = new pos_EnrollmentGuideInfoService();

        //根据招生简讯id 和 学校id 查询上一条记录详情（旧记录 下一篇）
        $upPage = $enro->queryPreviousPageByid($id, $schoolId, '');
        //根据招生简讯id 和 学校id 查询下一条简讯（新记录 上一篇）
        $nextPage = $enro->queryNextPageByid($id, $schoolId, '');
        $data = $enro->egDetailSearch($id);
        return $this->render('briefView', array('data' => $data, 'upPage' => $upPage->data, 'nextPage' => $nextPage->data));
    }

    /**
     * 招生简章修改
     * @param $id
     */
    public function actionBriefUpdate($id, $schoolId)
    {
        //验证权限
        $this->getSchoolModel($schoolId);
        if (!loginUser()->isTeacher() || !loginUser()->getTeacherInSchool($schoolId)) {
            return $this->notFound();
        }

        $model = new BriefForm();
        $enro = new pos_EnrollmentGuideInfoService();
        $result = $enro->egDetailSearch($id);

        $model->name = $result->briefName;
        $model->schoolLevel = $result->department;
        $model->year = $result->year;
        $model->content = $result->detailOfBrief;

        if (isset($_POST['BriefForm'])) {
            $model->attributes = $_POST['BriefForm'];
            $result = $enro->enrollmentGuideSave($model->name, $id, $model->schoolLevel, $model->year, $model->content);
            if ($result->resCode == BaseService::successCode) {
                return $this->redirect(url('school/brieflist', array('schoolId' => $schoolId)));
            }
        }

        return $this->render('addBrief', array('model' => $model));
    }


    /**
     *添加教研组
     */
    public function  actionAddTeachingGroup()
    {

        $group = new pos_TeachingGroupService();
        $addGroup = $group->addTeachingGroup($_POST["schoolId"], $_POST["groupName"], "", $_POST["subjectID"], $_POST["department"], "", "", "");
        $jsonResult = new JsonMessage();
        if ($addGroup->resCode == pos_TeachingGroupService::successCode) {
            $jsonResult->success = $addGroup->resMsg;
            $jsonResult->code = 1;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = $addGroup->resMsg;
            $jsonResult->code = 0;
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     *添加班级
     */
    public function   actionAddClass()
    {
        $class = new pos_ClassInfoService();

        $addClass = $class->addClassInfo(user()->id, $_POST["schoolId"], $_POST["department"], $_POST["joinYear"], $_POST["classNumber"], $_POST["className"], "");
        $jsonResult = new JsonMessage();
        if ($addClass->resCode == pos_ClassInfoService::successCode) {
            $jsonResult->code = 1;
            $jsonResult->success = $addClass->resMsg;
            $jsonResult->data = $addClass;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->code = 0;
            $jsonResult->success = $addClass->resMsg;
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     * 高
     * 教研列表
     * @param $schoolId
     * @throws CException
     * @throws CHttpException
     */
    public function actionTeachingList($schoolId)
    {
        $this->getSchoolModel($schoolId);
        //学校id为1002==》》为测试数据
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 12;
        $schoolLevel = app()->request->getQueryParam('schoolLevel', '');
        $subject = app()->request->getQueryParam('subject', '');
        $pages->params = ['schoolLevel' => $schoolLevel, 'schoolId' => $schoolId, 'subject' => $subject];
        $list = new pos_TeachingGroupService();
        $modelList = $list->searchTeachingGroup($schoolId, '', $subject, $schoolLevel, '', 1, $pages->getPage() + 1, $pages->pageSize, '');
        $pages->totalCount = intval($modelList->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_teaching_list_view', array(
                'modelList' => $modelList->teachingGroupList,
                'pages' => $pages
            ));

        }
        return $this->render('teachingList', array('modelList' => $modelList->teachingGroupList, 'pages' => $pages, 'schoolId' => $schoolId));

    }

    /**
     * 分数线列表
     *
     */
    public function actionFraction($schoolId)
    {
        $year = app()->request->post('sel_year', null);
        $frction = $this->getSchoolModel($schoolId);
        $frctionList = explode(',', $frction->department);
        $teacherLine = loginUser()->getTeacherInSchool($schoolId);
        $array = array();
        $frctionModel = in_array('20203', $frctionList);
        if ($frctionModel == true) {
            $pointLineSearch = new pos_PointLineInfoService();
            $modelList = $pointLineSearch->search($schoolId, $year, 20203);

            if (!empty($modelList)) {
                foreach ($modelList->pointLinelist as $key => $val) {
                    $array[] = $val;
                }
            }
        }
        if (app()->request->isAjax) {
            return $this->renderPartial('_fraction_list_view', array(
                "arr" => $array,
                'schoolId' => $schoolId,
                'frctionList' => $frctionList,
                'teacherLine' => $teacherLine
            ));

        }

        return $this->render('fraction', array("arr" => $array, 'schoolId' => $schoolId, 'frctionList' => $frctionList, 'teacherLine' => $teacherLine));

    }

    /**
     * 高
     * 添加分数线
     *
     */
    public function actionAddPointLine()
    {
        $departmentId = '20203';
        $year = $_POST['year'];
        $admission = intval(app()->request->post('admission', 0));
        $choiceSchool = intval(app()->request->post('choiceSchool', 0));
        $userId = user()->id;
        $accommodation = intval(app()->request->post('accommodation', 0));
        $schoolId = app()->request->post('schoolId', 0);
        $jsonResult = new JsonMessage();
        $model = new pos_PointLineInfoService();
        $addModel = $model->add($departmentId, $year, $admission, $choiceSchool, $userId, $accommodation, $schoolId, "");

        if ($addModel->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->message = '添加成功！';
            return $this->renderJSON($jsonResult);
        } elseif ($addModel->resCode == 100102) {
            $jsonResult->success = false;
            $jsonResult->message = $addModel->resMsg . ',请修改！';
            return $this->renderJSON($jsonResult);

        } else {
            $jsonResult->success = false;
            $jsonResult->message = '添加失败！';
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     * 编辑分数线
     */
    public function actionUpdateScore()
    {

        $pointLineId = intval(app()->request->post('plId', null));
        $departmentId = '20203';
        $year = $_POST['year'];
        $admission = intval(app()->request->post('admission', null));
        $choiceSchool = intval(app()->request->post('choiceSchool', null));
        $accommodation = intval(app()->request->post('accommodation', null));

        $model = new pos_PointLineInfoService();
        $result = $model->update($pointLineId, $departmentId, $year, $admission, $choiceSchool, $accommodation, '');
        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->message = '修改成功！';
            return $this->renderJSON($jsonResult);
        } elseif ($result->resCode == 100102) {
            $jsonResult->success = false;
            $jsonResult->message = $result->resMsg . ',请修改';
            return $this->renderJSON($jsonResult);

        } else {
            $jsonResult->success = false;
            return $this->renderJSON($jsonResult);
        }
    }


    /**
     * 高
     * 教师管理列表
     * @param $schoolId
     * @return string
     */
    public function actionTeacher($schoolId)
    {
        $this->getSchoolModel($schoolId);
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $schoolLevel = app()->request->post('schoolLevel', '');
        $subject = app()->request->post('subject', '');
        $teacherName = app()->request->post('teacherName', '');
        $teacherInfo = SeUserinfo::find()->where(["schoolID"=>$schoolId, "type"=>1]);

        //搜用户名
        if(!empty($teacherName)){
            $teacherInfo->andWhere(["trueName"=>$teacherName]);
        }
        //搜学部、学段
        if (!empty($schoolLevel)) {
            $teacherInfo->andWhere(["department"=>$schoolLevel]);
        }
        //搜学科
        if (!empty($subject)) {
            $teacherInfo->andWhere(["subjectID"=>$subject]);
        }
        $teacherList = $teacherInfo->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();
        $pages->totalCount = $teacherInfo->count();
        if (app()->request->isAjax) {
            return $this->renderPartial('_teacher_list', array(
                'teacherList' => $teacherList,
                'pages' => $pages,
                'schoolId' => $schoolId
            ));
        }
        return $this->render('teacher', array('teacherList' => $teacherList, 'pages' => $pages, 'schoolId' => $schoolId));
    }

    /**
     *导入名单
     */
    public function actionUploadTeacherList()
    {
        if(loginUser()->isTeacher()) {
        $schoolID = app()->request->getQueryParam("schoolID");
            $jsonResult = new JsonMessage();
            $uploadfile = app()->request->getQueryParam("uploadfile");
            $model = \common\models\pos\SeSchoolUploadList::find()->where(["schoolID" => $schoolID])->one();
            if ($model == null) {
                $model = new \common\models\pos\SeSchoolUploadList();
            }
            $model->uploadfile = $uploadfile;
            $model->schoolID = $schoolID;
            $result = $model->save();
            $jsonResult->success = $result;
            return $this->renderJSON($jsonResult);
        }else {
            return false;
        }
    }

    /**
     * 检测是否可以进入教师主页
     */
    public function actionIsOn()
    {
        $teacherId = app()->request->getQueryParam('teacherId', null);
        $userId = user()->id;
        $model = new pos_PersonalInformationService();
        $result = $model->judgeUserCanIn($userId, $teacherId, 1);
        $jsonResult = new JsonMessage();
        if ($result['isUserCanIn'] == 1) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } elseif ($result['isUserCanIn'] != 1) {
            $jsonResult->success = false;
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     * 修改教师
     */
    public function actionUpTeachergroup()
    {
        $jsonMessage = new JsonMessage();
        $groupid = app()->request->getQueryParam('groupid');
        $userid = app()->request->getQueryParam('userid');
        $duty = app()->request->getQueryParam('duty');

        $pos_TeachingGroupService = new  pos_TeachingGroupService();

        if ($pos_TeachingGroupService->modifyMemberOfGroup($groupid, $userid, $duty)) {
            $jsonMessage->success = true;
        }

        return $this->renderJSON($jsonMessage);
    }


    /**
     * 修改教研在教研组的身份
     */
    public function  actionUpteacherclass()
    {

        $classid = app()->request->getQueryParam('classid');
        $userId = app()->request->getQueryParam('userid');
        $identity = app()->request->getQueryParam('identity');
        $jsonMessage = new JsonMessage();

        $pos_TeachingGroupService = new  pos_ClassMembersService();

        if ($pos_TeachingGroupService->modifyClassTeahcer($classid, $userId, $identity)) {
            $jsonMessage->success = true;
        }

        return $this->renderJSON($jsonMessage);


    }

    /**
     * 班级列表
     */
    public function actionClasses($schoolId)
    {
        $this->getSchoolModel($schoolId);
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;

        $department = app()->request->getQueryParam('departments', array());
        $gradeId = app()->request->getQueryParam('gradeID', '');
        $classID = app()->request->getQueryParam('classID', '');
        $classname = app()->request->getQueryParam('classname', '');

        $classInfoModel = new pos_ClassInfoService();
        $classInfoList = $classInfoModel->querySchoolClass($schoolId, implode(',', $department), '', $gradeId, '', $classname, user()->id, $classID, $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($classInfoList->countSize);

        if (app()->request->isAjax) {
            return $this->renderPartial('_schoolClass_all', array(
                'model' => $classInfoList->classList, 'schoolId' => $schoolId, 'pages' => $pages,
            ));

        }
        return $this->render('schoolClass', array('model' => $classInfoList->classList, 'pages' => $pages, 'schoolId' => $schoolId));
    }


    /**
     * AJAX 修改学校口号
     */
    public function actionAjaxSchoolSlogan()
    {
        $slogan = app()->request->getQueryParam('data', '');
        $schoolID = app()->request->getQueryParam('schoolID', '');
        $userID = user()->id;
        $jsonResult = new JsonMessage();
        $isTeacher = loginUser()->isTeacher();
        if ($isTeacher != null) {
            $inSchool = loginUser()->getIsSchool($schoolID);
            if ($inSchool == 1) {
                $obj = new pos_SchoolSloganService();
                $result = $obj->modifySchoolSlogan($schoolID, $slogan, $userID);
                if ($result->resCode == BaseService::successCode) {
                    $jsonResult->success = true;
                    return $this->renderJSON($jsonResult);
                } else {
                    $jsonResult->success = false;
                    return $this->renderJSON($jsonResult);
                }
            }
        } else {
            $jsonResult->success = false;
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     *修改学校头像
     */
    public function actionSetSchoolPic($schoolId)
    {
        $schoolModel = $this->getSchoolModel($schoolId);
//        $this->layout = "lay_user_info";
        return $this->render("setSchoolPic", array('schoolModel' => $schoolModel, 'schoolId' => $schoolId));
    }

    /**
     *修改保存学校图片
     */
    public function actionUpdateSchoolPic()
    {
        $headImgUrl = app()->request->getQueryParam('headImgUrl', '');
        $schoolId = app()->request->getQueryParam('schoolId', '');
        $school = new pos_SchoolInfoService();
        $result = $school->modifySchoolLogo($schoolId, $headImgUrl);
        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->message = '修改成功';
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->message = '修改失败';
            $jsonResult->success = false;
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     * 校内答疑
	 * @param $schoolId
	 * @return string
	 */

	public function actionAnswerQuestions($schoolId)
	{
		$this->getSchoolModel($schoolId);
		$pages = new Pagination();
		$pages->validatePage=false;
		$pages->pageSize =10;

		$keyWord = app()->request->getParam('keyWord', '');
		$subjectID = app()->request->getQueryParam('subjectID', '');

		$answerQuery = SeAnswerQuestion::find()->active()->andWhere(['schoolID'=>$schoolId]);

		if(!empty($keyWord)){
			$answerQuery->andWhere(['like','aqName',$keyWord]);
		}

		if(!empty($subjectID)){
			$answerQuery->andWhere(['subjectID'=>$subjectID]);
		}

		$answerList = $answerQuery->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();
		$pages->totalCount = $answerQuery->count();
		if (app()->request->isAjax) {
			return $this->renderPartial('//publicView/answer/_answer_list', array('modelList'=>$answerList,'pages' => $pages, 'schoolId' => $schoolId));
		}
		return $this->render('answerQuestions', array('modelList' => $answerList, 'pages' => $pages, 'schoolId' => $schoolId));
	}


}