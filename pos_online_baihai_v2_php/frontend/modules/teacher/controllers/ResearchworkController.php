<?php
namespace frontend\modules\teacher\controllers;
use frontend\components\TeacherBaseController;
use frontend\services\BaseService;
use frontend\services\pos\pos_TaskCourseInfoService;
use yii\data\Pagination;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-18
 * Time: 下午2:51
 */
class ResearchworkController extends TeacherBaseController
{
    public $layout = "lay_user";

    public function actionTopics()
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 3;
        $grade = app()->request->getParam('grade', '');
        $userId =user()->id;
        $pages->params = ['grade' => $grade];
        $taskCourse = new pos_TaskCourseInfoService();
        $taskCourseList = $taskCourse->searchByMemberPager($userId,$grade,$pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($taskCourseList->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_list_course', array(
                'taskCourseList' => $taskCourseList->courseList,
                'pages' => $pages
            ));

        }
        return $this->render('topics', array('taskCourseList' => $taskCourseList->courseList, 'pages' => $pages));
    }

    public function actionDetails($id){
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 2;
        $pages->params = ['id' => $id];
        $getCourse = new pos_TaskCourseInfoService();
        $taskCourse = $getCourse->taskCourseSearchById($id, '');
        if(empty($taskCourse->data->courseMemberList)){
            return $this->notFound();
        }
        if ($taskCourse->resCode == BaseService::successCode) {
            $dairySearchList = $getCourse->dairySearchByTaskCourseID($id, $pages->getPage() + 1, $pages->pageSize, '');
            $total = $dairySearchList->countSize;
            $pages->totalCount = intval($dairySearchList->countSize);
        }
        return $this->render('details',array('taskCourse' => $taskCourse->data,'dairySearchList'=>$dairySearchList->courseDairyList,'pages'=>$pages,'total'=>$total));
    }
}