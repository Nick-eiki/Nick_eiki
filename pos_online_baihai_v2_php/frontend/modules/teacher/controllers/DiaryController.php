<?php
namespace frontend\modules\teacher\controllers;
use frontend\components\TeacherBaseController;
use frontend\models\DiaryForm;
use frontend\services\BaseService;
use frontend\services\pos\pos_TeachDairyService;
use yii\data\Pagination;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-9-19
 * Time: 下午1:37
 */
class DiaryController extends TeacherBaseController
{

    public $layout = 'lay_user';

    /**
     *  添加日记
     */
    public function actionAddDiary(){

        $model = new DiaryForm();
        $type = app()->request->getParam('type',1);
        $courseID = app()->request->getParam('courseID','');
        $model->type = $type;
        $model->ketiTitle = $courseID;
        if (isset($_POST['DiaryForm'])) {
            $model->attributes = $_POST['DiaryForm'];
            $diary = new pos_TeachDairyService();
            $result = $diary->createTeachDairy($model->name, $model->type, $model->tingkeTitle, $model->ketiTitle, $model->content, user()->id);
            if ($result->resCode == BaseService::successCode) {
                return $this->redirect('diaryList');
            }
        }

        return $this->render('addDiary', array('model' => $model));
    }

    /**
     * 日记列表
     */
    public function actionDiaryList()
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;

        $type = app()->request->getParam('type','');

        $pages->params = ['type' => $type];
        $diary  = new pos_TeachDairyService();

        $result = $diary->queryTeachDairy('', user()->id, '', $type, $pages->getPage() + 1, $pages->pageSize);

        $pages->totalCount = intval($result->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_diary_data', array(
                'data' => $result->list,
                'pages' => $pages
            ));

        }

        return $this->render('diaryList',array(
            'data'=>$result->list,
            'pages' => $pages
        ));
    }

    /**
     *  日记详情
     */
    public function actionDiaryView($id,$type) {
        $userId = user()->id;
        $diary  = new pos_TeachDairyService();
        $result=$diary->queryTeachDairy($id,'','',$type)->list;
        if (empty($result)) {
            return $this->notFound();
        }
        $previous =$diary->queryPreviousPageByid($userId,$result[0]->diaryID,$type);
        if(empty($previous->list)){
            $previous->list[0]=array();
        }

        $next=$diary->queryNextPageByid($userId,$result[0]->diaryID,$type);
        if(empty($next->list)){
            $next->list[0]=array();
        }

        return $this->render('diaryView', array('data' => $result[0],'previous'=>$previous->list[0],'next'=>$next->list[0]));
    }

    /**
     * 日记更新
     * @param int $id
     */
    public function actionDiaryUpdate($id) {
        //显示数据
        $diary  = new pos_TeachDairyService();
        $result = $diary->queryTeachDairy($id)->list;
        if (empty($result)) {
            return $this->notFound();
        }
        $data = $result[0];
        $model = new DiaryForm();
        $model->name = $data->headline;
        $model->type = $data->diaryType;
        $model->ketiTitle = $data->courseID;
        $model->tingkeTitle = $data->lectureID;
        $model->content = $data->diaryInfo;

        if (isset($_POST['DiaryForm'])) {
            $model->attributes = $_POST['DiaryForm'];
            $result = $diary->updateTeachDairy($id, $model->name, $model->type, $model->tingkeTitle, $model->ketiTitle, $model->content, user()->id);
            if ($result->resCode == BaseService::successCode) {
                return $this->redirect(url('teacher/diary/diary-list'));
            }
        }
        return $this->render('addDiary', array('model' => $model));
    }


}