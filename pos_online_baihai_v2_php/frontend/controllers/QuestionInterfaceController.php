<?php
namespace frontend\controllers;

use common\models\search\Es_testQuestion;
use frontend\models\dicmodels\EditionModel;
use Yii;
use yii\base\Controller;
use yii\web\Response;

/**
 * 给课海查提接口
 * Created by PhpStorm.
 * User: aaa
 * Date: 2016/1/11
 * Time: 9:13
 */
class QuestionInterfaceController extends Controller
{
    public function actionIndex()
    {
        yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isGet) {
            $str = Yii::$app->request->get('id');
        } elseif (Yii::$app->request->isPost) {
            $str = Yii::$app->request->post('id');
        } else {
            $str = '';
        }
        $array = explode(',', $str);
        $questionList = [];
        foreach ($array as $key => $val) {
            /** @var Es_testQuestion $questionModel */
            $questionModel = Es_testQuestion::forFrondSearch()->where(['id' => $val])->one();

            if (empty($questionModel)) {
                continue;
            }
            $data = $this->questionContent($questionModel);

            $list['subjectId'] = $questionModel->subjectid;
            $list['content'] = $data;
            $list['kid'] = $questionModel->kid;
            $list['versionName'] = '';
            $list['questionId'] = $val;
            if (isset($questionModel->versionid)) {
                $list['versionName'] = EditionModel::model()->getEditionNames(explode(',', $questionModel->versionid));

            }

            $questionList[] = $list;
        }
        if ($questionList) {
            $arrays['resCode'] = '000';
            $arrays['resMsg'] = '成功';
            $arrays['data']['qs'] = $questionList;
            return $arrays;
        } else {
            $arrays['resCode'] = '001';
            $arrays['resMsg'] = '失败';
            $arrays['data']['qs'] = $questionList;
            return $arrays;
        }

    }

    /**
     * 关键字搜题
     * @return array
     */
    public function actionSearchByKey()
    {
        yii::$app->response->format = Response::FORMAT_JSON;
        $searchKey = Yii::$app->request->get('searchKey', Yii::$app->request->post('searchKey'));
        $subjectId = Yii::$app->request->get('subjectId', Yii::$app->request->post('subjectId'));
        $currentPage = Yii::$app->request->get('currentPage', Yii::$app->request->post('currentPage'));
        $pageSize = Yii::$app->request->get('pageSize', Yii::$app->request->post('pageSize'));
        if ($pageSize == null) {
            $pageSize = 10;
        }
        if ($currentPage < 1) {
            $currentPage = 1;
        }
        $Es_testQuestionQuery = Es_testQuestion::forFrondSearch();
        if ($subjectId != null) {
            $Es_testQuestionQuery->where(['subjectid' => $subjectId]);
        }
        if ($searchKey != null) {
            $Es_testQuestionQuery->query([
                "match" => [
                    "content" => ["query" =>
                        $searchKey,
                        "minimum_should_match" => "30%"
                    ]]
            ]);
        }
        $countSize = $Es_testQuestionQuery->count();
        $offset = ($currentPage - 1) * $pageSize;
        $questionList = $Es_testQuestionQuery->offset($offset)->limit($pageSize)->all();
        $array = [];
        $array['data']['qs'] = [];
        if (!empty($questionList)) {
            $array['resCode'] = '000';
            $array['resMsg'] = '成功';
            $array['data']['currentPage'] = $currentPage;
            $array['data']['pageSize'] = $pageSize;
            $array['data']['countSize'] = $countSize;
            $array['data']['totalPages'] = ceil($countSize / $pageSize);
            foreach ($questionList as $v) {
                array_push($array['data']['qs'], ['subjectId' => $v->subjectid, 'content' => $this->questionContent($v), 'kid' => $v->kid, 'questionId' => $v->id]);
            }
        } else {
            $array['resCode'] = '001';
            $array['resMsg'] = '失败';
        }
        return $array;
    }

    /**
     * 根据ID搜原题和相似题
     * @return array
     */
    public function actionFindById()
    {

        yii::$app->response->format = Response::FORMAT_JSON;
        $questionId = Yii::$app->request->get('questionId', Yii::$app->request->post('questionId'));
        $currentPage = Yii::$app->request->get('currentPage', Yii::$app->request->post('currentPage'));
        $pageSize = Yii::$app->request->get('pageSize', Yii::$app->request->post('pageSize'));
        if ($pageSize == null) {
            $pageSize = 10;
        }
        if ($currentPage < 1) {
            $currentPage = 1;
        }
        $offset = ($currentPage - 1) * $pageSize;

        /** @var Es_testQuestion $oriQuestion */
        $oriQuestion = Es_testQuestion::find()->where(['id' => $questionId])->one();
        $array = [];
        if ($oriQuestion != null) {
            $array['resCode'] = '000';
            $array['resMsg'] = '成功';
            $array['data']['qinfo']['subjectId'] = $oriQuestion->subjectid;
            $array['data']['qinfo']['content'] = $this->questionContent($oriQuestion);
            $array['data']['qinfo']['kid'] = $oriQuestion->kid;
            $array['data']['qinfo']['questionId'] = $oriQuestion->id;

            $Es_testQuestionQuery = Es_testQuestion::forFrondSearch();
            //去除文本中的空格
            $content =  str_replace('&nbsp;','',strip_tags($oriQuestion->content));
            if ($questionId != null) {
                $Es_testQuestionQuery->query([
                    "match" => [
                        "content" => ["query" =>
                            $content,
                            "minimum_should_match" => "50%"
                        ]]
                ])->andWhere(['subjectid' => $oriQuestion->subjectid]);
            }

            $countSize = $Es_testQuestionQuery->count();
            $simiQuesion = $Es_testQuestionQuery->offset($offset)->limit($pageSize)->all();
            $array['data']['qs'] = [];
            $array['data']['currentPage'] = $currentPage;
            $array['data']['pageSize'] = $pageSize;
            $array['data']['countSize'] = $countSize;
            $array['data']['totalPages'] = ceil($countSize / $pageSize);
            if (!empty($simiQuesion)) {
                foreach ($simiQuesion as $v) {
                    array_push($array['data']['qs'], ['subjectId' => $v->subjectid, 'content' => $this->questionContent($v), 'kid' => $v->kid, 'questionId' => $v->id]);
                }
            }
        } else {
            $array['resCode'] = '001';
            $array['resMsg'] = '失败';
        }
        return $array;

    }


    /**
     * 组合小题
     * @param Es_testQuestion $questionModel
     * @return string
     */
    public function questionContent(Es_testQuestion $questionModel)
    {
        $smallQuestion = $questionModel->getQuestionChildCache();
        if (empty($smallQuestion)) {
            $smallQuestion = [];
        }

        $data = $this->renderPartial('/publicView/questionInterface/_itemQuestion', ['questionModel' => $questionModel, 'smallQuestion' => $smallQuestion]);
        return $data;
    }


}