<?php
namespace frontend\modules\teacher\controllers;

use common\models\JsonMessage;
use common\models\pos\SeQuestionFavoriteFolderNew;
use common\models\sanhai\SeSchoolGrade;
use common\models\sanhai\ShQuestionError;
use common\models\sanhai\ShTestquestion;
use common\models\search\Es_testQuestion;
use frontend\components\helper\KnowTreeHelper;
use frontend\components\TeacherBaseController;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\dicmodels\LoadTextbookVersionModel;
use frontend\services\apollo\Apollo_chapterInfoManage;
use frontend\services\apollo\Apollo_QuestionInfoService;
use frontend\services\apollo\Apollo_QuestionTypeService;
use frontend\services\pos\pos_MessageSendByUserService;
use frontend\services\pos\pos_QueFavoriteFolder;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-13
 * Time: 下午3:45
 */
class SearchquestionsController extends TeacherBaseController
{
    public $layout = 'lay_user';

    /**
     *按照自定义标签搜索试题
     */
    public function actionKeywordQuestions()
    {
        $this->layout = "lay_user_select_question";
        $text = app()->request->getParam('text', '');
        $typeId = app()->request->getParam('type', '');
        $complexity = app()->request->getParam('complexity', '');
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $department = app()->request->getParam('department', loginUser()->getModel()->department);
        $gradeList = SeSchoolGrade::find()->where(['schoolDepartment' => $department])->select('gradeId')->asArray()->all();
        $gradeArr = \yii\helpers\ArrayHelper::getColumn($gradeList, 'gradeId', false);
        $subjectid = app()->request->getParam('subjectid', loginUser()->getModel()->subjectID);
        $type = new Apollo_QuestionTypeService();
        $result = $type->queryQuesType($department, $subjectid);



        $Es_testQuestionQuery = Es_testQuestion::find()->where(['operater' => 0,'mainQusId'=>0])->andWhere(['gradeid' => $gradeArr]);
        if ($typeId != null) {
            $Es_testQuestionQuery->andWhere(['tqtid' => $typeId]);
        }
        if ($complexity != null) {
            $Es_testQuestionQuery->andWhere(['complexity' => $complexity]);
        }
        $Es_testQuestionQuery->andWhere(['subjectid' => $subjectid]);
        if ($text != null) {
            $Es_testQuestionQuery->query([
                "match" => [
                    "content" => ["query" =>
                        $text,
                        "operator" => "and"
                    ]]
            ]);
        }
        $pages->totalCount = $Es_testQuestionQuery->count();
        /** @var Es_testQuestion $dataList */
        $dataList = $Es_testQuestionQuery->orderBy('year desc')->offset($pages->getOffset())->limit($pages->getLimit())->highlight(['fields' => ['content' => ["number_of_fragments" => 0, "pre_tags" => ['<em class="highlight">'], "post_tags" => ["</em>"]]]])->all();
        $this->collectedData($dataList,user()->id,1);
        $pages->params = ['text' => $text, 'type' => $typeId, 'complexity' => $complexity, 'department' => $department, 'subjectid' => $subjectid];

        if (app()->request->isAjax) {
            return $this->renderPartial('_new_topicListData', array('topic_list' => $dataList, 'page' => $pages));

        }


        return $this->render('keywordQuestions', array("topic_list" => $dataList, "page" => $pages, "tags" => $text, "result" => $result, "department" => $department, 'subjectid' => $subjectid));
    }

    /**
     * 按知识点搜索题目 wgl
     */
    public function actionKnowledgePointQuestions()
    {
        $this->layout = "lay_user_select_question";
        //用于区别平台题库和我的题库 0平台 1 我的 2收藏
        $nub = intval(app()->request->getParam('n', 0));
        if ($nub == 0) {
            $userId = 0;
        } elseif ($nub == 1 || $nub == 2) {
            $userId = user()->id;
        }

        //学部
        $departments = app()->request->getParam('department', loginUser()->getModel()->department);
        $gradeList = SeSchoolGrade::find()->where(['schoolDepartment' => $departments])->select('gradeId')->asArray()->all();
        $gradeArr = \yii\helpers\ArrayHelper::getColumn($gradeList, 'gradeId', false);
        $subjectid = app()->request->getParam('subjectid', loginUser()->getModel()->subjectID);
        //题型id
        $type = app()->request->getParam('type', null);
        //难度
        $complexity = app()->request->getParam('complexity', null);
        $isPic=app()->request->getQueryParam('isPic',null);
        //学部学科
        $departmentSubject = new Apollo_QuestionTypeService();
        $result = $departmentSubject->queryQuesType($departments, $subjectid);
        //知识点
        $kid = app()->request->getParam('kid', null);

        //知识点树
        $kModelList = KnowledgePointModel::searchAllKnowledgePoint($subjectid, $departments);
        $knowtree = KnowTreeHelper::knowledgeMakeTree($kModelList, app()->request->getParam('kid', ''), $type, $complexity, $nub, $this->getRoute());

        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $questionQuery=Es_testQuestion::find()->where(['mainQusId'=>'0','gradeid'=>$gradeArr]);
        if (!empty($nub) && $nub == 2) {
//            $material = new pos_QueFavoriteFolder();
//            //数据列表
//            $questionList = $material->queryQueFavoriteFolder($userId, '', '', '', '', $departments, '', $subjectid, '', $kid, $type, '', '', '', $complexity, '', '', '', '', '', '', '', $pages->getPage() + 1, $pages->pageSize);
            $favouriteQuestionList=SeQuestionFavoriteFolderNew::find()->where(['userId'=>$userId,'isDelete'=>0])->select('questionId')->asArray()->all();
            $favouriteQuestionArray= ArrayHelper::getColumn($favouriteQuestionList,'questionId');

            $questionQuery->andWhere(['in','id',$favouriteQuestionArray]);


        } elseif ($nub == 0 || $nub == 1) {
//            $material = new Apollo_QustionManageService();
//            //题列表
//            $questionList = $material->questionSearch($userId, '', $pages->getPage() + 1, $pages->pageSize, '', '', '', '', '', $departments, '', $subjectid, '', $kid, $type, '', '', '', $complexity, '', '', '', '', '', user()->id);


            $questionQuery->andWhere(['operater'=>$userId]);


        }
        if($subjectid!=null){
            $questionQuery->andWhere(['subjectid'=>$subjectid]);
        }
        if($complexity!=null){
            $questionQuery->andWhere(['complexity'=>$complexity]);
        }
        if($type!=null){
            $questionQuery->andWhere(['tqtid'=>$type]);
        }
        if($kid!=null){
            $questionQuery->query([
                "match" => [
                    "kid" => ["query" =>
                        $kid,
                        "operator" => "and"
                    ]]
            ]);
        }
        if($isPic!=null){
            $questionQuery->andWhere(['isPic'=>$isPic]);
        }
        $questionResult=$questionQuery->orderBy('year desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();
        $this->collectedData($questionResult,user()->id,0);
        $pages->totalCount = intval($questionQuery->count());
        if (app()->request->isAjax) {
            return $this->renderPartial('_knowledge_point_right', array("questionList" => $questionResult, 'result' => $result, "pages" => $pages,'isPic'=>$isPic));

        }
        return $this->render('knowledgePointQuestions', array("questionList" => $questionResult, "pages" => $pages, 'result' => $result, "department" => $departments, 'subjectid' => $subjectid, 'knowtree' => $knowtree));
    }

    /**
     * 判断题目是否被收藏并且给题目结果赋值
     * @param array $dataList
     * @param $userID
     */
    public function collectedData( array $dataList,$userID,$isHighLight){
        //       获取所有的题目ID
        $questionIDArray = array();
        foreach ($dataList as $item) {
            array_push($questionIDArray, $item->id);
        }
//        查出当前人收藏的列表并取出题目ID放进数组
        $favouriteDate = SeQuestionFavoriteFolderNew::find()->where(['questionId' => $questionIDArray])->andWhere(['userId' => $userID])->andWhere(['isDelete' => 0])->select('questionId')->all();
        $favouriteIDArray = ArrayHelper::getColumn($favouriteDate, 'questionId');

        //        判断题目ID是否在收藏的数组里面并且给isCollected赋值
        foreach ($dataList as $item) {
            if (in_array($item->id, $favouriteIDArray)) {
                $item->isCollected = 1;
            } else {
                $item->isCollected = 0;
            }
            if($isHighLight){
                $highLight = $item->getHighlight();
                if ($highLight && isset($highLight['content'])) {
                    $item->content = $highLight['content'][0];
                }
            }

        }

    }

    /**
     * 章节搜题  wgl
     */
    public function  actionChapterQuestions()
    {
        $this->layout = "lay_user_select_question";
        //用于区别平台题库和我的题库 0平台 1 我的 2 收藏
        $nub = intval(app()->request->getParam('n', 0));

        if ($nub == 0) {
            $userId = 0;
        } elseif ($nub == 2) {
            $userId = user()->id;
        }
        $version = app()->request->getParam('version', loginUser()->getModel()->textbookVersion);
        //章节id
        $chapterId = app()->request->getParam('chapId', null);
        //学部
        $departments = app()->request->getParam('department', loginUser()->getModel()->department);
        $gradeList = SeSchoolGrade::find()->where(['schoolDepartment' => $departments])->select('gradeId')->asArray()->all();
        $gradeArr = \yii\helpers\ArrayHelper::getColumn($gradeList, 'gradeId', false);
        $subjectid = app()->request->getParam('subjectid', loginUser()->getModel()->subjectID);

        //题型id
        $type = app()->request->getParam('type', null);
        //难度
        $complexity = app()->request->getParam('complexity', null);
        //学部 科目
        $departmentSubject = new Apollo_QuestionTypeService();
        $result = $departmentSubject->queryQuesType($departments, $subjectid);

        //根据学部展示年级
        $gradeList = GradeModel::model()->getData($departments, '');
        //根据科目获取版本
        $versionList = LoadTextbookVersionModel::model()->getData($subjectid, null, $departments);
        if(!empty($versionList)){
            $versionResult=  from($versionList)->firstOrDefault($versionList[0],function($v,$k)use($version){
                return $v->secondCode==$version;
            });
            $version=$versionResult->secondCode;
        }
        $chapterTomeModel = new Apollo_chapterInfoManage();
        $chapterTomeResult = $chapterTomeModel->chapterBaseNodeSearch($subjectid, $departments, $version, null, null);
        //章节树 查询章节
        $kModelList = ChapterInfoModel::searchChapterPointToTree($subjectid, $departments, $version, '', '', null, $chapterId);
        $chapterTree = KnowTreeHelper::chapterMakeTree($kModelList, $departments, $subjectid, $chapterId, $type, $complexity, $nub, $version, $this->getRoute());

        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $questionQuery=Es_testQuestion::find()->where(['mainQusId'=>0,'gradeid'=>$gradeArr]);
        if (!empty($nub) && $nub == 2) {
            $favouriteQuestionList=SeQuestionFavoriteFolderNew::find()->where(['userId'=>$userId,'isDelete'=>0])->select('questionId')->asArray()->all();
            $favouriteQuestionArray= ArrayHelper::getColumn($favouriteQuestionList,'questionId');

            $questionQuery->andWhere(['in','id',$favouriteQuestionArray]);

        }elseif($nub==0){
            $questionQuery->andWhere(['operater'=>$userId]);
        }
        if($subjectid!=null){
            $questionQuery->andWhere(['subjectid'=>$subjectid]);
        }
        if($complexity!=null){
            $questionQuery->andWhere(['complexity'=>$complexity]);
        }
        if($type!=null){
            $questionQuery->andWhere(['tqtid'=>$type]);
        }
        if($chapterId!=null){

            $questionQuery->query([
                "match" => [
                    "chapterId" => ["query" =>
                        $chapterId,
                        "operator" => "and"
                    ]]
            ]);

        }
        if($version!=null){
            $questionQuery->andWhere(['versionid'=>$version]);
        }
        $questionResult=$questionQuery->orderBy('year desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();
        $this->collectedData($questionResult,user()->id,0);
        $pages->totalCount = intval($questionQuery->count());
        if (app()->request->isAjax) {
            return $this->renderPartial('_knowledge_point_right', array("questionList" => $questionResult, 'result' => $result, "pages" => $pages));

        }
        return $this->render('chapterQuestions', array("questionList" => $questionResult, "pages" => $pages, 'result' => $result, "department" => $departments, 'subjectid' => $subjectid, 'gradeList' => $gradeList, 'chapterTree' => $chapterTree, 'versionList' => $versionList, 'chapterTomeResult' => $chapterTomeResult->cPointList,'version'=>$version));
    }

    //题目收藏
    public function actionCollectionQuestion()
    {

        $qid = app()->request->getParam('qid');
        $userId = user()->id;
        $jsonResult = new JsonMessage();
        $work = new  pos_QueFavoriteFolder();

        $verify = $work->addQueFavoriteFolder($qid, $userId);

        if ($verify->resCode = pos_MessageSendByUserService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->message = '收藏失败';
        }

        return $this->renderJSON($jsonResult);

    }

    //取消题目收藏
    public function actionCancelCollectionQuestion()
    {

        $qid = app()->request->getParam('qid');
        $userId = user()->id;
        $jsonResult = new JsonMessage();
        $work = new  pos_QueFavoriteFolder();

        $verify = $work->delQueFavoriteFolderByDtl($qid, $userId);

        if ($verify->resCode = pos_MessageSendByUserService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->message = '取消收藏失败';
        }

        return $this->renderJSON($jsonResult);

    }

    //删除题目
    public function actionDelQuestion()
    {

        $qid = app()->request->post('qid');
        $userId = user()->id;
        $jsonResult = new JsonMessage();
        $work = new  Apollo_QuestionInfoService();

        $verify = $work->questionDelete($qid, $userId);

        if ($verify->resCode = pos_MessageSendByUserService::successCode) {
            $jsonResult->success = true;
            $jsonResult->message = '删除成功';
        } else {
            $jsonResult->message = '删除失败';
        }

        return $this->renderJSON($jsonResult);

    }

    //题目纠错
    public function actionQuestionError()
    {
        $jsonResult = new JsonMessage();
        $questionId = app()->request->post('questionId', '');
        $question = ShTestquestion::find()->where(['id' => $questionId])->one();

        if ($question && !empty($_POST)) {
            $questionError = ShQuestionError::find()->where(['questionId' => $questionId])->one();
            if ($questionError) {
                $jsonResult->success = true;
            } else {
                $errorType = app()->request->post('errorType', '');
                $questionErrorModel = new ShQuestionError();
                $questionErrorModel->questionId = $questionId;
                $questionErrorModel->errorType = $errorType;
                $questionErrorModel->userName = \frontend\components\WebDataCache::getTrueName(user()->id);
                $questionErrorModel->brief = $_POST['brief'];
                $questionErrorModel->userId = user()->id;
                $questionErrorModel->createTime = times();
                if ($questionErrorModel->save()) {
                    $jsonResult->success = true;
                }
            }
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->message = '非法操作！';
            return $this->renderJSON($jsonResult);
        }
    }

}