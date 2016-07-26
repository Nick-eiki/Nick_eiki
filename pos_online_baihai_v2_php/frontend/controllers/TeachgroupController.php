<?php
namespace frontend\controllers;

use common\models\JsonMessage;
use common\models\pos\SeFavoriteMaterial;
use common\models\pos\SeGroupCourse;
use common\models\pos\SeGroupCourseMember;
use common\models\pos\SeGroupCourseReport;
use common\models\pos\SeGroupLecturePlan;
use common\models\pos\SeGroupLecturePlanMember;
use common\models\pos\SeGroupLecturePlanReport;
use common\models\pos\SeGroupMembers;
use common\models\sanhai\SrMaterial;
use common\services\JfManageService;
use common\services\KeyWordsService;
use frontend\components\TeacherBaseController;
use frontend\components\WebDataCache;
use frontend\services\apollo\Apollo_MaterialService;
use frontend\services\BaseService;
use frontend\services\pos\pos_FavoriteFolderService;
use frontend\services\pos\pos_GroupSloganService;
use Yii;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Url;


/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 14-9-15
 * Time: 下午4:24
 * BaseAuthController 为登录权限
 */
class TeachgroupController extends TeacherBaseController
{
    public $layout = 'lay_new_teachgroup';


    /**
     * 教研组首页
     */
    public function actionIndex($groupId)
    {
        $this->getTeachingGroupModel($groupId);
        //教研资料
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 6;
        $teacherMaterial = new Apollo_MaterialService();
        $allInfo = $teacherMaterial->getMaterialGroupType('', '', user()->id, $groupId, $pages->getPage() + 1, $pages->pageSize);

        //教研课题
        $pagination = new Pagination();
        $pagination->validatePage = false;
        $pagination->pageSize = 3;
        $course = SeGroupCourse::find()->where(['teachingGroupID' => $groupId])->orderBy("createTime desc")->offset($pagination->getOffset())->limit($pagination->getLimit())->all();

        return $this->render('index', array(
            'pages' => $pages,
            'groupId' => $groupId,
            'model' => $allInfo->list,
            'course'=>$course
        ));
    }


    /**
     * 教研资料列表
     * @param $groupId
     * @return string
     */
    public function actionTeachData($groupId)
    {
        $this->getTeachingGroupModel($groupId);
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;

        $gradeid = app()->request->getQueryParam('gradeid', '');
        $mattype = app()->request->getQueryParam('mattype', '');

        $teacherMaterial = new Apollo_MaterialService();
        $allInfo = $teacherMaterial->getMaterialGroupType('', '', '', $groupId, '', '');
        $allNum = intval($allInfo->countSize);


        $classMatInfo = $teacherMaterial->getMaterialGroupType($mattype, $gradeid, user()->id, $groupId, $pages->getPage() + 1, $pages->pageSize);

        $pages->totalCount = intval($classMatInfo->countSize);
        $pages->params = ['mattype' => $mattype,
            'gradeid' => $gradeid,
            'groupId' => $groupId
        ];
        if (app()->request->isAjax) {
            return $this->renderPartial('_teachdata_list', array(
                'model' => $classMatInfo->list,
                'groupId' => $groupId,
                'pages' => $pages
            ));

        }
        return $this->render('teachdata', array(
            'gradeid' => $gradeid,
            'mattype' => $mattype,
            'allNum' => $allNum,
            'model' => $classMatInfo->list,
            'groupId' => $groupId,
            'pages' => $pages,
            'allReadNum' => $classMatInfo->allReadNum
        ));

    }


    /**
     *教研资料列表详情
     */
    public function actionTeachDataDetails($groupId)
    {
        $this->getTeachingGroupModel($groupId);
        $id = app()->request->getQueryParam('id');
        $type = app()->request->getQueryParam('type');
        $userId = user()->id;
        $teacherMaterial = SrMaterial::find()->where(['id'=>$id,'matType'=>$type])->one();
        $teacherMaterial->readNum += 1;    //增加阅读次数
        $teacherMaterial->save(false);

        //判断有没有收藏过该资料
        $favoriteMaterial = SeFavoriteMaterial::find()->where(['favoriteId'=>$id,'userId'=>$userId])->one();
        if($favoriteMaterial){
            $isCollected = 1;
        }else{
            $isCollected = 0;
        }

        return $this->render('teachdatadetails', array('model' => $teacherMaterial,'isCollected'=>$isCollected));
    }

    /**
     *教研组组内成员
     */
    public function actionTeachGroupMember($groupId)
    {
        $this->getTeachingGroupModel($groupId);
        $seGroupQu = \common\models\pos\SeGroupMembers::find()->where(['groupID' => $groupId])->active();
        $se1master = clone $seGroupQu;
        $masterModelList = $se1master->andWhere(['identity' => '20301'])->all();
        $memberModelList = $seGroupQu->andWhere(['identity' => '20302'])->all();
        $count = count($masterModelList) + count($memberModelList);

        return $this->render('teachgroupmember', array('member' => $memberModelList, 'master' => $masterModelList, 'count' => $count, 'groupId' => $groupId));
    }

    /**
     * 删除教研组成员
     */
    public function actionDeleteGroupMember()
    {

        $jsonResult = new JsonMessage();
        $groupId = app()->request->post('groupid', '');
        $userId = app()->request->post('userid', '');
        $isInGroup = \common\models\pos\SeGroupMembers::find()->where(['teacherID' => user()->id])->one();

        //当前用户是否在此教研组中
        if (isset($isInGroup) && !empty($isInGroup)) {

            //要删除的用户是否是当前人
            if ($userId != user()->id) {
                $groupMemberModel = \common\models\pos\SeGroupMembers::find()->where(['groupID' => $groupId, 'teacherID' => $userId])->one();
                $groupMemberModel->isDelete = '1';

                if ($groupMemberModel->update()) {
                    $jsonResult->success = true;
                    $jsonResult->message = '删除成功！';
                } else {
                    $jsonResult->message = '删除失败！';
                }
            } else {
                $jsonResult->message = '删除失败！';
            }

        }

        return $this->renderJSON($jsonResult);

    }

    /**
     * 收藏教研组
     */
    public function actionCollect()
    {
        $id = app()->request->post('id');
        $matType = app()->request->post('type');
        $userId = user()->id;
        $material = new pos_FavoriteFolderService();
        $result = $material->addFavoriteFolder($id, $matType, $userId);

        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->message = '收藏成功！';

        } else {
            $jsonResult->success = false;
            $jsonResult->message = '收藏失败！';
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * 教研组右部
     */
    public function rightContent($groupId)
    {
        //教研组成员
        $groupMemberModel = \common\models\pos\SeGroupMembers::find()->where(['groupID' => $groupId])->active()->limit(11)->all();
        $count = count($groupMemberModel);
        $lessonsArray=array();
//        我参与的课程
        $joinResult=SeGroupLecturePlan::find()->where("lecturePlanID in (select  lecturePlanID  from  se_groupLecturePlanMember where userID=".user()->id.")")->all();
        foreach($joinResult as $v){

            $year=date("Y",strtotime($v->joinTime));
            $month=date("m",strtotime($v->joinTime));
            $day=date("d",strtotime($v->joinTime));
            $title=Html::encode($v->title);
            $teacher=$v->teacherName;
            $joinMember=$v->groupLecturePlanMember;
            $joinMemberArray=array();
            foreach($joinMember as $value){
                array_push($joinMemberArray,$value->userName);
            }
            array_push($lessonsArray,array("id"=>intval($v->lecturePlanID),"year"=>intval($year),"month"=>intval($month),"day"=>intval($day),"type"=>"join","teacher"=>$teacher,"lesson"=>$title,"time"=>date("Y-m-d",strtotime($v->joinTime)),"joiner"=>$joinMemberArray));
        }
//        我主讲的课程
        $speechResult=SeGroupLecturePlan::find()->where(["teacherID"=>user()->id])->all();
        foreach ($speechResult as $v) {
            $year=date("Y",strtotime($v->joinTime));
            $month=date("m",strtotime($v->joinTime));
            $day=date("d",strtotime($v->joinTime));
            $title=Html::encode($v->title);
            $teacher=$v->teacherName;
            $joinMember=$v->groupLecturePlanMember;
            $joinMemberArray=array();
            foreach($joinMember as $value){
                array_push($joinMemberArray,$value->userName);
            }
            array_push($lessonsArray,array("id"=>intval($v->lecturePlanID),"year"=>intval($year),"month"=>intval($month),"day"=>intval($day),"type"=>"speech","teacher"=>$teacher,"lesson"=>$title,"time"=>date("Y-m-d",strtotime($v->joinTime)),"joiner"=>$joinMemberArray));
        }
        return $this->renderPartial('_teachgroup_right_content', array('model' => $groupMemberModel, 'count' => $count, 'groupId' => $groupId,"lessonJson"=>json_encode($lessonsArray)));
    }

    //AJAX 修改教研组口号
    /**
     *
     */
    public function actionAjaxGroupSlogan()
    {
        $jsonResult = new JsonMessage();
        $slogan = app()->request->post('txt', '');
        if ((mb_strlen($slogan, 'utf-8') > 30)) {
            $jsonResult->success = false;
            $jsonResult->message = '请输入30字以内';
            return $this->renderJSON($jsonResult);
        }
        $classID = app()->request->post('classId', '');
        $userID = user()->id;
        $jsonResult = new JsonMessage();
        $isInGroup = loginUser()->getInGroupInfo($classID);
        if (!empty($isInGroup)) {
            $obj = new pos_GroupSloganService();
            $result = $obj->modifyGroupSlogan($classID, $slogan, $userID);

            if ($result->resCode == BaseService::successCode) {
                $jsonResult->success = true;
                return $this->renderJSON($jsonResult);
            } else {
                $jsonResult->success = false;
                return $this->renderJSON($jsonResult);
            }
        } else {
            $jsonResult->success = false;
            return $this->renderJSON($jsonResult);
        }

    }
    /**
     * 教研课题列表
     */
    public function actionTopic($groupId)
    {
        $this->getTeachingGroupModel($groupId);
        $gradeId = app()->request->getQueryParam('gradeId', '');
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;


        $condtion = ['teachingGroupID' => $groupId];
        if (!empty($gradeId)) {
            $condtion = array_merge($condtion, ['gradeID' => $gradeId]);
        }

        $pages->totalCount = SeGroupCourse::find()->where($condtion)->count();
        //所有教研课题
        $course = SeGroupCourse::find()->where($condtion)->orderBy("createTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();

        if (app()->request->isAjax) {
            return $this->renderPartial("_topic_list", array('groupId' => $groupId, 'course' => $course, "pages" => $pages));
        }

        $groupCourseModel = new SeGroupCourse();
        $groupCourseMemberModel = new SeGroupCourseMember();
        //教研组所有成员
        $memberModel = \common\models\pos\SeGroupMembers::find()->where(['groupID' => $groupId])->active();
        $member = $memberModel->all();
        return $this->render('topic', array(
            'groupId' => $groupId,
            'member' => $member,
            'pages' => $pages,
            'seGroupCourseModel' => $groupCourseModel,
            'segroupCourseMemberModel' => $groupCourseMemberModel,
            'course' => $course,

        ));

    }


    /** 添加课题
     * @return string
     */
    public function actionAddTopic()
    {

        $jsonResult = new JsonMessage();

        //当前用户是否在此教研组中
        $groupId = app()->request->post('groupId', '');
        $isInGroup = loginUser()->getInGroupInfo($groupId);
        $file = app()->request->post('file', '');
        $url = implode($file, ',');

        if ($_POST) {

            //当前用户是否在此教研组中
            if (!empty($isInGroup)) {

                $groupCourseModel = new SeGroupCourse();
                $groupCourseModel->load(\Yii::$app->request->post());
                $groupCourseModel->url = $url;
                $groupCourseModel->createTime = time() * 1000;
                $groupCourseModel->creatorID = user()->id;
                $groupCourseModel->teachingGroupID = $groupId;

                if ($groupCourseModel->save()) {

                    $courseID = $groupCourseModel->courseID;
                    $teacherId = app()->request->post('teacherID', '');
                    $memId = explode(',', $teacherId);

                    $value = true;
                    foreach ($memId as $val) {
                        $groupCourseMemberModel = new SeGroupCourseMember();
                        $groupCourseMemberModel->courseID = $courseID;
                        $groupCourseMemberModel->teacherID = $val;
                        $value = $value && $groupCourseMemberModel->save();
                    }

                    if ($value) {
                        $jsonResult->success = true;
                        $jsonResult->message = '添加成功！';
                    } else {
                        $jsonResult->message = '添加失败！';
                    }
                }

            } else {
                $jsonResult->message = '添加失败！';
            }
            return $this->renderJSON($jsonResult);
        }

    }

    /**
     * 修改教研课题
     */
    public function actionModifyTopic()
    {

        $groupId = app()->request->post('groupId', '');
        $courseId = app()->request->post('courseId', '');
        $memberModel = \common\models\pos\SeGroupMembers::find()->where(['groupID' => $groupId])->active();
        $member = $memberModel->all();

        //源数据
        $courseModel = SeGroupCourse::find()->where(['courseID' => $courseId]);
        $course = $courseModel->one();
        $courseMemberModel = SeGroupCourseMember::find()->where(['courseID' => $courseId]);
        $courseMember = $courseMemberModel->all();
        $arr = [];
        foreach ($courseMember as $val) {
            $arr[] = $val->teacherID;
        }

        //赋值
        $groupCourseModel = new SeGroupCourse();
        $groupCourseMemberModel = new SeGroupCourseMember();

        $groupCourseModel->courseName = $course->courseName;
        $groupCourseModel->gradeID = $course->gradeID;
        $groupCourseModel->url = $course->url;
        $groupCourseModel->brief = $course->brief;

        $this->layout = 'blank';
        return $this->render('_modify_topic', array(
            'groupId' => $groupId,
            'courseId' => $courseId,
            'seGroupCourseModel' => $groupCourseModel,
            'segroupCourseMemberModel' => $groupCourseMemberModel,
            'member' => $member,
            'course' => $course,
            'courseMember' => $arr
        ));
    }

    /**
     * 课题修改操作
     */
    /**
     * 课题修改操作
     */
    public function actionModifyTopicOne()
    {

        $jsonResult = new JsonMessage();

        //当前用户是否在此教研组中
        $groupId = app()->request->post('groupId', '');
        $courseId = app()->request->post('courseId', '');
        $isInGroup = loginUser()->getInGroupInfo($groupId);
        $file = app()->request->post('file', '');
        $url = implode($file, ',');

        $reportNum = SeGroupCourseReport::find()->where(['courseId' => $courseId])->count();
        if ($reportNum > 0) {
            $jsonResult->message = '已经有人提交报告，不能修改！';
        } else {
            if ($_POST) {

                //当前用户是否在此教研组中
                if (!empty($isInGroup)) {
                    //保存除成员外的内容
                    $groupCourseModel = SeGroupCourse::find()->where(['courseID' => $courseId, 'teachingGroupID' => $groupId])->one();
                    $groupCourseModel->load(\Yii::$app->request->post());
                    $groupCourseModel->url = $url;
                    $groupCourseModel->createTime = time() * 1000;
                    $groupCourseModel->creatorID = user()->id;
                    $groupCourseModel->teachingGroupID = $groupId;

                    if ($groupCourseModel->save()) {

                        $courseID = $groupCourseModel->courseID;
                        $teacherId = app()->request->post('teacherID', '');
                        $memId = explode(',', $teacherId);

                        //删除原有成员
                        $num = SeGroupCourseMember::deleteAll(['courseID' => $courseId]);
                        if ($num > 0) {

                            //添加新成员
                            $value = true;
                            foreach ($memId as $val) {
                                $groupCourseMemberModel = new SeGroupCourseMember();
                                $groupCourseMemberModel->courseID = $courseID;
                                $groupCourseMemberModel->teacherID = $val;
                                $value = $value && $groupCourseMemberModel->save();
                            }
                            if ($value) {
                                $jsonResult->success = true;
                                $jsonResult->message = '修改成功！';
                            } else {
                                $jsonResult->message = '修改失败！';
                            }
                        } else {
                            $jsonResult->message = '修改失败！';
                        }
                    } else {
                        $jsonResult->message = '修改失败！';
                    }

                } else {
                    $jsonResult->message = '您不是该教研组成员！';
                }
            }

        }
        return $this->renderJSON($jsonResult);
    }


    /**
     * 教研课题详情
     * @param $groupId
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionTopicDetails($groupId)
    {

        $this->getTeachingGroupModel($groupId);

        $courseId = app()->request->getQueryParam("courseId");

        $course = SeGroupCourse::find()->where(['courseID' => $courseId])->one();

        $courseMembers = SeGroupCourseMember::find()->where(['courseId' => $courseId])->all();

        if ($course && $course->teachingGroupID == $groupId) {
            $pages = new Pagination();
            $pages->validatePage = false;
            $pages->pageSize = 3;
            $pages->totalCount = SeGroupCourseReport::find()->where(['courseId' => $courseId])->count();
            $Report = SeGroupCourseReport::find()->where(['courseId' => $courseId, 'teachingGroupID' => $groupId]);
            $courseReport = $Report->orderBy("createTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();
            $gradeId = $course->gradeID;
            $gradeName = \frontend\components\WebDataCache::getGradeName($gradeId);

            if (app()->request->isAjax) {
                return $this->renderPartial("_topicdetail_list", array('groupId' => $groupId, 'gradeName' => $gradeName, 'courseReport' => $courseReport, "pages" => $pages, 'courseId' => $courseId, 'course' => $course, 'courseMembers' => $courseMembers));
            }

            return $this->render('topicdetails', array('groupId' => $groupId, 'gradeName' => $gradeName, 'courseId' => $courseId, 'courseReport' => $courseReport, "pages" => $pages, 'course' => $course, 'courseMembers' => $courseMembers));
        } else {
            return $this->notFound();
        }

    }

    /**
     * 教研课题——写报告
     */
    public function actionTopicReport($groupId)
    {
        $this->getTeachingGroupModel($groupId);
        $userId = user()->id;

        $isInGroup = loginUser()->getInGroupInfo($groupId);
        $courseId = app()->request->getQueryParam("courseId");
        $currentGroup = SeGroupCourse::find()->where(['courseID' => $courseId])->one();
        $courseGroupMembers = SeGroupCourseMember::find()->where(['courseID' => $courseId, 'teacherID' => $userId])->one();

        if ($currentGroup && $courseGroupMembers && $currentGroup->teachingGroupID == $groupId && !empty($isInGroup)) {
            $courseReport = SeGroupCourseReport::find()->where(['courseID' => $courseId, 'userID' => $userId])->one();
            if (!$courseReport) {
                $courseReport = new SeGroupCourseReport();
            }
            if ($_POST) {
                $courseReport->reportContent = $_POST["SeGroupCourseReport"]["reportContent"];
                $courseReport->reportTitle = $_POST["SeGroupCourseReport"]["reportTitle"];
                $courseReport->teachingGroupID = $groupId;
                $courseReport->userID = $userId;
                $courseReport->courseID = $_POST['courseId'];
                $courseReport->createTime = time() * 1000;
                $courseReport->updateTime = time() * 1000;
                if ($courseReport->save()) {
                    return $this->redirect(Url::to(['topic-details', 'groupId' => $groupId, 'courseId' => $_POST['courseId']]));
                }
            }
            return $this->render('topicreport', array('groupId' => $groupId, 'courseReport' => $courseReport, 'courseId' => $courseId));
        } else {
            $this->notFound('非本课题参与人员！',403);
        }

    }

    /**
     * 教研课题——报告详情
     * @param $groupId
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionReportDetails($groupId)
    {

        $this->getTeachingGroupModel($groupId);

        $courseId = app()->request->getQueryParam("courseId");
        $course = SeGroupCourse::find()->where(['courseID' => $courseId])->one();

        $courseReportId = app()->request->getQueryParam("courseReportId");
        $qu = SeGroupCourseReport::find()->where(['teachingGroupID' => $groupId, 'courseID' => $courseId]);
        $cur = clone $qu;
        $qu_last = clone $qu;

        $quData = $cur->andWhere(["courseReportId" => $courseReportId])->one();
        if ($quData && $course && $course->teachingGroupID == $groupId) {
            $updateTime = $quData->createTime;
            $userId = $quData->userID;
            $gradeId = $course->gradeID;
            $gradeName = \frontend\components\WebDataCache::getGradeName($gradeId);
//        下一篇
            $nextData = $qu->andWhere([">", "updateTime", $updateTime])->orderBy("updateTime asc")->one();
//        上一篇
            $lastData = $qu_last->andWhere(["<", "updateTime", $updateTime])->orderBy("updateTime desc")->one();

            return $this->render('reportdetails', array('groupId' => $groupId, "quData" => $quData, "nextData" => $nextData, "lastData" => $lastData, 'userId' => $userId, 'gradeName' => $gradeName, 'courseId' => $courseId));

        } else {
            $this->notFound();
        }
    }

    /**
     * 听课评课主页
     * @param $groupId
     * @return string
     */
    public function actionListenLessons($groupId)
    {
        $this->getTeachingGroupModel($groupId);

        $quData=SeGroupLecturePlan::find()->where(["teachingGroupID"=>$groupId]);
        $type=app()->request->getQueryParam("type");
        $pages = new Pagination();
        $pages->pageSize = 10;
        if($type==1){
            $quData->andWhere(["teacherID"=>user()->id]);
            $pages->params["type"]=1;
        }elseif($type==2){
            $quData->andWhere("lecturePlanID in (select  lecturePlanID  from  se_groupLecturePlanMember where userID=".user()->id.")");
            $pages->params["type"]=2;
        }
        $pages->totalCount = $quData->count();
        $lessonList = $quData->orderBy("joinTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();

        if(app()->request->isAjax){
            return $this->renderPartial("_lesson_list",array("lessonList"=>$lessonList,"groupId"=>$groupId,"pages"=>$pages));
        }

        //查询教研组教师列表
        $teacherList=array();
        $teacherListModel = SeGroupMembers::find()->where(['groupId'=>$groupId])->active()->all();
        foreach($teacherListModel as $v){
            $teacherList[$v->teacherID]=WebDataCache::getTrueName($v->teacherID);
        }
        return $this->render("listenLessons",array('groupId' => $groupId,
            'teacherList' => $teacherList,
            'lessonList'=>$lessonList,
            "pages"=>$pages
        ));
    }

    /**
     * AJAX课程分页
     * @return string
     */
    public function actionGetLessonsPage(){
        $page=Yii::$app->request->getParam("page");
        $type=Yii::$app->request->getParam("type");
        $groupId=Yii::$app->request->getParam("groupId");
        $pages=new Pagination();
        $pages->pageSize=10;
        $quData=SeGroupLecturePlan::find()->where(["teachingGroupID"=>$groupId]);
        if($type==1){
            $quData->andWhere(["teacherID"=>user()->id]);
        }elseif($type==2){
            $quData->andWhere("lecturePlanID in (select  lecturePlanID  from  se_groupLecturePlanMember where userID=".user()->id.")");
        }
        $pages->totalCount = $quData->count();
        $pages->params["page"]=$page;
        $pages->params["groupId"]=$groupId;
        $pages->params["type"]=$type;
        $lessonList = $quData->orderBy("joinTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();
        return $this->renderPartial("_lesson_list",array("pages"=>$pages,"lessonList"=>$lessonList,"groupId"=>$groupId));

    }

    /**
     * 听课评课修改内容的获取
     * @return string
     */
    public function actionGetListenDetails(){
        $lecturePlanID=app()->request->getBodyParam("lecturePlanID");
        $this->layout=false;
        $groupId=app()->request->getBodyParam("groupId");
        $teacherListModel = SeGroupMembers::find()->where(['groupId'=>$groupId])->active()->all();
        $model=new SeGroupLecturePlan();
        $lectureResult=$model->find()->where(["lecturePlanID"=>$lecturePlanID])->one();
        $joinModel=new SeGroupLecturePlanMember();
        $joinResult=$joinModel->find()->where(["lecturePlanID"=>$lecturePlanID])->all();
        $joinerArray=array();
        foreach($joinResult as $v){
            array_push($joinerArray,$v->userID);
        }
        $teacherList=array();
        foreach($teacherListModel as $v){
            $teacherList[$v->teacherID]=WebDataCache::getTrueName($v->teacherID);
        }
        return  $this->render("_listen_update_plan",array( 'teacherList' => $teacherList,'lectureResult'=>$lectureResult,'joinArray'=>$joinerArray));
    }

    /**
     *安排听课
     */
    public function actionArrangeLessons(){
        $groupId=Yii::$app->request->getParam("groupId");
        $jsonResult = new JsonMessage();
        if(loginUser()->getInGroupInfo($groupId)) {
            $speaker = Yii::$app->request->getParam("speaker");
            $joiner = Yii::$app->request->getParam("joiner");
//        去掉既是听课人又是参与人的参与人
            $joinerArray = explode(",", $joiner);
            foreach ($joinerArray as $key => $v) {
                if ($v == $speaker) {
                    unset($joinerArray[$key]);
                }
            }
            $finalJoiner = implode(",", $joinerArray);
            $title = Yii::$app->request->getParam("title");
            $listenTime = Yii::$app->request->getParam("listenTime");
            $model = new SeGroupLecturePlan();
            $model->teachingGroupID = $groupId;
            $model->teacherID = $speaker;
            $model->teacherName = WebDataCache::getTrueName($speaker);
            $model->joinTime = $listenTime;
            $model->createTime = time() * 1000;
            $model->creatorID = user()->id;
            $model->title = $title;
            if ($model->save()) {
                $lecturePlanID = $model->lecturePlanID;
                $valid = true;
                if (!empty($finalJoiner)) {
                    $joinerArray = explode(",", $finalJoiner);
                    foreach ($joinerArray as $v) {
                        $memberModel = new SeGroupLecturePlanMember();
                        $joinerName = WebDataCache::getTrueName($v);
                        $memberModel->lecturePlanID = $lecturePlanID;
                        $memberModel->userID = $v;
                        $memberModel->userName = $joinerName;
                        $joinResult = $memberModel->save();
                        if (!$joinResult) {
                            $valid = false;
                        }
                    }
                }

                if ($valid) {
                    $jfHelper=new JfManageService;
                    $jfHelper->myAccount("pos-plan-lesson",user()->id);
                    $jsonResult->success = true;
                    $jsonResult->message = "添加成功";
                }
            }
        }else{
            $jsonResult->success = false;
            $jsonResult->message = "对不起，您不是当前教研组的成员";
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     *修改听课
     */
    public function actionUpdateLessons(){
        $lecturePlanID=Yii::$app->request->getBodyParam("lecturePlanID");
        $groupId=Yii::$app->request->getParam("groupId");

        $jsonResult = new JsonMessage();
        if(loginUser()->getInGroupInfo($groupId)) {
            $speaker = Yii::$app->request->getParam("speaker");
            $joiner = Yii::$app->request->getParam("joiner");
            //        去掉既是听课人又是参与人的参与人
            $joinerArray = explode(",", $joiner);
            foreach ($joinerArray as $key => $v) {
                if ($v == $speaker) {
                    unset($joinerArray[$key]);
                }
            }
            $finalJoiner = implode(",", $joinerArray);
            $title =KeyWordsService::ReplaceKeyWord(Yii::$app->request->getParam("title"));
            $listenTime = Yii::$app->request->getParam("listenTime");
            $model = SeGroupLecturePlan::find()->where(["lecturePlanID" => $lecturePlanID])->one();
            $model->teachingGroupID = $groupId;
            $model->teacherID = $speaker;
            $model->teacherName = WebDataCache::getTrueName($speaker);
            $model->joinTime = $listenTime;
            $model->createTime = time() * 1000;
            $model->creatorID = user()->id;
            $model->title = $title;
            SeGroupLecturePlanMember::deleteAll(["lecturePlanID" => $lecturePlanID]);
            if ($model->save()) {
                $lecturePlanID = $model->lecturePlanID;
                $valid = true;
                if (!empty($finalJoiner)) {
                    $joinerArray = explode(",", $finalJoiner);

                    foreach ($joinerArray as $v) {
                        $memberModel = new SeGroupLecturePlanMember();
                        $joinerName = WebDataCache::getTrueName($v);
                        $memberModel->lecturePlanID = $lecturePlanID;
                        $memberModel->userID = $v;
                        $memberModel->userName = $joinerName;
                        $joinResult = $memberModel->save();
                        if (!$joinResult) {
                            $valid = false;
                        }
                    }
                }
                if ($valid) {
                    $jsonResult->success = true;
                    $jsonResult->message = "修改成功";
                }
            }
        }else{
            $jsonResult->success = false;
            $jsonResult->message = "对不起，您不是当前教研组的成员";
        }
        return $this->renderJSON($jsonResult);
    }



    /**
     * 听课报告详情
     * @param $groupId
     * @return string
     */
    public function actionListenReportDetails($groupId)
    {
        $this->getTeachingGroupModel($groupId);
        $lecturePlanID=app()->request->getQueryParam("lecturePlanID");
        $lecturePlanReportId = app()->request->getQueryParam('lecturePlanReportId');
        $model = SeGroupLecturePlanReport::find()->where(['teachingGroupID' => $groupId])->andWhere(["lecturePlanID"=>$lecturePlanID]);
        $cur = clone $model;
        $qu_last = clone $model;
        $detailsModel = $cur->andWhere(['lecturePlanReportId' => $lecturePlanReportId])->one();
        $datetime = $detailsModel->updateTime;
        //下一篇
        $next = $model->andWhere(['>', 'updateTime', $datetime])->orderBy("updateTime asc")->one();
        //上一篇
        $up = $qu_last->andWhere(['<', 'updateTime', $datetime])->orderBy('updateTime desc')->one();


        return $this->render('listenReportdetails', array('detailsModel' => $detailsModel, 'groupId' => $groupId, 'next' => $next, 'up' => $up));
    }

    /**
     * 填写听课报告
     * @param $groupId
     * @return string
     */

	public function actionWriteReport($groupId)
	{
		$this->getTeachingGroupModel($groupId);

		$lecturePlanId = app()->request->getQueryParam('lecturePlanId');
		//查询教研组是否正确
		$selectGroup = SeGroupLecturePlan::find()->where(['lecturePlanID'=>$lecturePlanId])->one();
		//查看改用户是否在该教研组
		$isInGroup = loginUser()->getInGroupInfo($groupId);

		if($selectGroup->teachingGroupID != $groupId || empty($isInGroup)){
			return $this->notFound();
		}
		$model = SeGroupLecturePlanReport::find()->where(['teachingGroupID'=>$groupId])->andWhere(['lecturePlanID'=>$lecturePlanId,'userID'=>user()->id])->one();
		if(empty($model)) {
			$model = new SeGroupLecturePlanReport();
			$model->createTime = strtotime(date('Y-m-d H:i:s', time())) * 1000;
			$model->updateTime = strtotime(date('Y-m-d H:i:s', time())) * 1000;
			$model->lecturePlanID = $lecturePlanId;
			$model->teachingGroupID = $groupId;
			$model->userID = user()->id;
		}

		if($_POST){
			$report_title = app()->request->post('report_title');
			$report_content = app()->request->post('report_content');

			$model->reportTitle = $report_title;
			$model->reportContent = $report_content;
			if(empty($report_title)){

				$model->addError('reportTitle','请输入名称！');
			}
			if(empty($report_content)){

				$model->addError('reportContent','请输入内容！');
			}

			$model->updateTime = strtotime(date('Y-m-d H:i:s', time())) * 1000;
			if(!$model->hasErrors() && $model->save()){
                $jfHelper=new JfManageService;
                $jfHelper->myAccount("pos-fill-report",user()->id);
				return $this->redirect(Url::to(['listen-lessons', 'groupId' => $groupId]));
			}

		}
		return $this->render('writeReport',['model'=>$model]);
	}





}

