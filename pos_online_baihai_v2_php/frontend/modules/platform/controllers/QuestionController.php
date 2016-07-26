<?php
namespace frontend\modules\platform\controllers;

use common\models\pos\SePaperQuesTypeRlts;
use common\models\sanhai\SeSchoolGrade;
use common\models\sanhai\SrChapter;
use common\models\search\Es_testQuestion;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\dicmodels\LoadTextbookVersionModel;
use Yii;
use yii\data\Pagination;

/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2016/1/18
 * Time: 10:57
 */
class QuestionController extends \frontend\components\BaseAuthController
{
    public $layout = "lay_platform";

    /**
     * 搜索选题
     * @return string
     */
    public function actionKeywordsChoose()
    {
        $proFirstime = microtime();
        $text = app()->request->getParam('text', '');
        $typeId = app()->request->getParam('type', '');
        $complexity = app()->request->getParam('complexity', '');
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $department =app()->request->getParam('department', '20201');
        $subjectid =app()->request->getParam('subjectId', '10010');
        $gradeList = SeSchoolGrade::find()->where(['schoolDepartment' => $department])->select('gradeId')->asArray()->all();
        $gradeArr = \yii\helpers\ArrayHelper::getColumn($gradeList, 'gradeId', false);
        $result=SePaperQuesTypeRlts::find()->where(['schoolLevelId'=>$department,'subjectId'=>$subjectid])->all();
        $searchArrMore= array(
            'type'=>$typeId,
            'complexity'=>$complexity,
            'department'=>$department,
            'subjectId'=>$subjectid,
            'text'=>$text

        );
        $Es_testQuestionQuery = Es_testQuestion::forFrondSearch()->andWhere(['gradeid' => $gradeArr]);
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
        $dataList = $Es_testQuestionQuery->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->highlight(['fields' => ['content' => ["number_of_fragments" => 0, "pre_tags" => ['<em class="highlight">'], "post_tags" => ["</em>"]]]])->all();
//       获取所有的题目ID
        $questionIDArray = array();
        foreach ($dataList as $item) {
            array_push($questionIDArray, $item->id);
        }
        foreach ($dataList as $item) {
            $highLight = $item->getHighlight();
            if ($highLight && isset($highLight['content'])) {
                $item->content = $highLight['content'][0];
            }
        }
        $pages->params = ['text' => $text, 'type' => $typeId, 'complexity' => $complexity, 'department' => $department, 'subjectId' => $subjectid];

        \Yii::info('试题库 '.(microtime()-$proFirstime),'service');
        if (app()->request->isAjax) {
            return $this->renderPartial('content_view', ['dataList' => $dataList, 'pages' => $pages,'result'=>$result,'searchArr'=>$searchArrMore]);
        }
        return $this->render('keywordsChoose', ['result' => $result,
            'department' => $department,
            'subjectid' => $subjectid,
            'dataList' => $dataList,
            'pages' => $pages,
            'text' => $text,
            'searchArrMore'=>$searchArrMore
        ]);
    }

    /**
     * 章节选题
     * @return string
     */
    public function actionChapterChoose(){

        $proFirstime = microtime();
        //学部
        $departments = app()->request->getParam('department','20201');
        $subjectid = app()->request->getParam('subjectId','10010');

        //题型id
        $type = app()->request->getParam('type', null);
        //难度
        $complexity = app()->request->getParam('complexity', null);
        //学部 科目
        $result=SePaperQuesTypeRlts::find()->where(['schoolLevelId'=>$departments,'subjectId'=>$subjectid])->all();
        //根据学部展示年级
        $gradeList = SeSchoolGrade::find()->where(['schoolDepartment' => $departments])->select('gradeId')->asArray()->all();
        $gradeArr = \yii\helpers\ArrayHelper::getColumn($gradeList, 'gradeId', false);
        //根据科目获取版本
        $versionList = LoadTextbookVersionModel::model($subjectid,'',$departments)->getListData();
        $version = app()->request->getParam('version',key($versionList));

       $chapterTomeResult=SrChapter::find()->where(['subject'=>$subjectid,'version'=>$version,'schoolLevel'=>$departments,'pid'=>0])->all();

        //章节id
        if(!empty($chapterTomeResult)) {
            $chapterId = app()->request->getParam('chapterId', $chapterTomeResult[0]->cid);
        }else{
            $chapterId=null;
        }
        //章节树 查询章节
        $chapterTree = ChapterInfoModel::searchChapterPointToTree($subjectid, $departments, $version, null, null, null, $chapterId);
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $Es_testQuestionQuery =Es_testQuestion::forFrondSearch()->andWhere(['gradeid' => $gradeArr]);
        if ($type!= null) {
            $Es_testQuestionQuery->andWhere(['tqtid' => $type]);
        }
        if ($complexity != null) {
            $Es_testQuestionQuery->andWhere(['complexity' => $complexity]);
        }
        if($version!=null){
            $Es_testQuestionQuery->andWhere(['versionid'=>$version]);
        }
        $Es_testQuestionQuery->andWhere(['subjectid' => $subjectid]);
        if($chapterId!=null){
            $Es_testQuestionQuery->andWhere(['chapterId'=>$chapterId]);
        }
        /** @var Es_testQuestion $dataList */
        $dataList = $Es_testQuestionQuery->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();
        if (!empty($dataList)) {
            $pages->totalCount = intval($Es_testQuestionQuery->count());
        }
        $pages->params = ['type' => $type, 'complexity' => $complexity, 'department' => $departments, 'subjectId' => $subjectid,'chapterId'=>$chapterId,'version'=>$version];
        $searchArrMore= array(
            'type'=>$type,
            'complexity'=>$complexity,
            'department'=>$departments,
            'subjectId'=>$subjectid,
            'chapterId'=>$chapterId,
            'version'=>$version
        );

        \Yii::info('章节选题 '.(microtime()-$proFirstime),'service');
        if(app()->request->isAjax){
            return $this->renderPartial('content_view',['searchArr'=>$searchArrMore,'dataList'=>$dataList,'pages'=>$pages,'result'=>$result]);
        }
        return $this->render('chapterChoose',['result' => $result,
            'department' => $departments,
            'subjectId' => $subjectid,
            'pages' => $pages,
            'versionList'=>$versionList,
            'version'=>$version,
            'chapterTomeResult' => $chapterTomeResult,
            'chapterId'=>$chapterId,
            'chapterTree'=>$chapterTree,
            'dataList'=>$dataList,
            'searchArrMore'=>$searchArrMore
        ]);
    }

    /**
     * 知识点选题
     * @return string
     */
    public function actionKnowledgeChoose(){
        $proFirstime = microtime();
        //学部
        $department = app()->request->getParam('department', '20201');
        $subjectid = app()->request->getParam('subjectId', '10010');
        //题型id
        $type = app()->request->getParam('type', null);
        //难度
        $complexity = app()->request->getParam('complexity', null);

        //学部学科
        $result=SePaperQuesTypeRlts::find()->where(['schoolLevelId'=>$department,'subjectId'=>$subjectid])->all();
        //知识点
        $kid = app()->request->getParam('kid', null);
        //知识点树
        $knowtree = KnowledgePointModel::searchAllKnowledgePoint($subjectid, $department);
        $gradeList = SeSchoolGrade::find()->where(['schoolDepartment' => $department])->select('gradeId')->asArray()->all();
        $gradeArr = \yii\helpers\ArrayHelper::getColumn($gradeList, 'gradeId', false);


        $Es_testQuestionQuery = Es_testQuestion::forFrondSearch()->andWhere(['gradeid' => $gradeArr]);
        if ($type!= null) {
            $Es_testQuestionQuery->andWhere(['tqtid' => $type]);
        }
        if ($complexity != null) {
            $Es_testQuestionQuery->andWhere(['complexity' => $complexity]);
        }
        $Es_testQuestionQuery->andWhere(['subjectid' => $subjectid]);
        if($kid!=null){
            $Es_testQuestionQuery->andWhere(['kid'=>$kid]);
        }
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        /** @var Es_testQuestion $dataList */
        $dataList = $Es_testQuestionQuery->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();
        if (!empty($dataList)) {
            $pages->totalCount = intval($Es_testQuestionQuery->count());
        }
        $pages->params = ['type' => $type, 'complexity' => $complexity, 'department' => $department, 'subjectId' => $subjectid,'kid'=>$kid];
        $searchArrMore= array(
            'type'=>$type,
            'complexity'=>$complexity,
            'department'=>$department,
            'subjectId'=>$subjectid,
            'kid'=>$kid
        );

        \Yii::info('知识点选题 '.(microtime()-$proFirstime),'service');
         if(app()->request->isAjax){
             return $this->renderPartial('content_view',['searchArr'=>$searchArrMore,'dataList'=>$dataList,'pages'=>$pages,'result'=>$result]);
         }
        return $this->render('knowledgeChoose',['result' => $result, 'department' => $department, 'subjectid' => $subjectid, 'pages' => $pages,'knowtree'=>$knowtree,'dataList'=>$dataList,'kid'=>$kid]);
    }
}