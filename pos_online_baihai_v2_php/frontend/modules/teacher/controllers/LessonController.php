<?php
namespace frontend\modules\teacher\controllers;
use common\models\JsonMessage;
use frontend\components\TeacherBaseController;
use frontend\services\pos\pos_ListenTeachingService;
use yii\data\Pagination;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-9-18
 * Time: 下午2:31
 */
class LessonController extends TeacherBaseController
{


    public $layout = "lay_user";

    /**
     *教师听课
     */
    public function actionListenLessons()
    {
        $teachingPages = new  Pagination();
        $teachingPages->pageSize = 2;
        $listen = new pos_ListenTeachingService();
        if(app()->request->isAjax){
            $queryType=app()->request->getParam("queryType");
            $teachingListenList = $listen->queryListenTeaching(user()->id, $queryType, "","", $teachingPages->getPage() + 1, $teachingPages->pageSize);
             $teachingPages->totalCount=$teachingListenList->data->countSize;
            return $this->renderPartial("_teaching_list_view",array("teachingListenList"=>$teachingListenList,"pages"=>$teachingPages));

        }
        $teachingListenList = $listen->queryListenTeaching(user()->id, "", "","", $teachingPages->getPage() + 1, $teachingPages->pageSize);
        $teachingPages->totalCount = $teachingListenList->data->countSize;
//        $teachingPages->params['queryType']=2;
        return $this->render("listenLessons", array("teachingListenList" => $teachingListenList, "teachingPages" => $teachingPages));
    }

    /**
     *添加听课
     */
    public function actionAddListenLessons()
    {
        $listen = new pos_ListenTeachingService();
        $addResult = $listen->createListenTeaching($_POST["title"], $_POST["teacherID"], $_POST["chapterID"], "", $_POST["joinTime"], $_POST["joinUsers"], user()->id);
        $jsonResult = new JsonMessage();
        $jsonResult->success = $addResult->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *ajax 获取听课列表分页
     */
    public function actionGetLessonsPage()
    {
        $listen = new pos_ListenTeachingService();
        $queryType=app()->request->getParam("queryType");
        $teachingPages = new Pagination();
        $teachingPages->pageSize = 2;
        $listenList = $listen->queryListenTeaching(user()->id, $queryType, "","", $teachingPages->getPage() + 1, $teachingPages->pageSize);
        $teachingPages->totalCount = $listenList->data->countSize;
        return $this->renderPartial("_teaching_list_view", array("teachingListenList" => $listenList, "pages" => $teachingPages));

    }
}