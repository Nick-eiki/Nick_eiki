<?php
namespace frontend\controllers;

use common\helper\DateTimeHelper;
use common\models\JsonMessage;
use common\models\pos\SeHomeworkQuestion;
use common\models\pos\SeHomeworkTeacher;
use common\models\pos\SeQuestionCart;
use common\models\pos\SeQuestionCartQeustions;
use common\services\JfManageService;
use frontend\components\BaseAuthController;
use frontend\components\helper\TreeHelper;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\LoadTextbookVersionModel;
use Yii;
use yii\db\Transaction;
use yii\helpers\Html;

/**
 * 选题栏相关操作
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2016/2/24
 * Time: 13:41
 */
Class BasketController extends BaseAuthController
{
    public $layout = "@app/modules/platform/views/layouts/lay_platform";

    /**
     * 选题篮列表
     * @return string
     */
    public function actionIndex()
    {
        $cartId = app()->request->getQueryParam('cartId');
        $cartResult = SeQuestionCart::findByUserAndCardId(user()->id,$cartId);
        if($cartResult==null){
         return $this->notFound('此选题篮不存在');
        }
        $subject = $cartResult->subjectId;
        $department = $cartResult->departmentId;
        //根据科目获取版本
        $versionList = LoadTextbookVersionModel::model($subject, '', $department)->getListData();
        $version = key($versionList);
        $chapterArray = ChapterInfoModel::getChapterArray($subject, $version, $department);
        //查询选题
        $questionCartQuestion = $cartResult->getQuestionCartQuestion()->all();
        return $this->render('index', ["questionCartQuestion" => $questionCartQuestion, 'subject' => $subject, 'department' => $department, 'versionList' => $versionList, 'chapterArray' => $chapterArray, 'cartId' => $cartId]);
    }

    /**
     *保存选题篮排序
     */
    public function actionSaveBasketOrder()
    {
        $dataArray = app()->request->getBodyParam('dataArray');
        foreach ($dataArray as $v) {
            SeQuestionCartQeustions::updateAll(['orderNumber' => $v['orderNumber']], ['cartQuestionId' => $v['cartQuestionId']]);
        }
    }

    /**
     *根据版本查询分册
     */
    public function actionGetTomeList()
    {
        $subject = app()->request->getBodyParam('subject');
        $version = app()->request->getBodyParam('version');
        $department = app()->request->getBodyParam('department');
        $chapterArray = ChapterInfoModel::getChapterArray($subject, $version, $department);
        echo ' 分册：' . Html::dropDownList('', '', $chapterArray, array(
                "id" => "tome",
                'data-validation-engine' => 'validate[required]'
            ));
    }

    /**
     * 章节树列表
     * @return string
     */
    public function actionGetChapterList()
    {
        $tome = app()->request->getBodyParam('tome');
        $subject = app()->request->getBodyParam('subject');
        $version = app()->request->getBodyParam('version');
        $department = app()->request->getBodyParam('department');
//章节树 查询章节
        $chapterTree = ChapterInfoModel::searchChapterPointToTree($subject, $department, $version, null, null, null, $tome);
        $tree = TreeHelper::streefun($chapterTree, [], 'tree pointTree');
        return $this->renderPartial('get_chapter_list', ['tree' => $tree]);
    }

    /**
     *创建作业
     * @return string
     */
    public function actionCreateHomework()
    {
        $jsonResult = new JsonMessage();
        $cartId = app()->request->getBodyParam('cartId');
        $version = app()->request->getBodyParam('version');
        $subject = app()->request->getBodyParam('subject');
        $department = app()->request->getBodyParam('department');
        $chapterId = app()->request->getBodyParam('chapterId');
        $homeworkName = app()->request->getBodyParam('homeworkName');
        $difficulty = app()->request->getBodyParam('difficulty');
        /** @var Transaction $transaction */

        $transaction = Yii::$app->db_school->beginTransaction();
        $questionArray = SeQuestionCartQeustions::find()->where(['cartId' => $cartId])->select('questionId,orderNumber')->asArray()->all();
        if (empty($questionArray)) {
            $jsonResult->message = '选题篮不能为空';
            return $this->renderJSON($jsonResult);
        }
        try {
            $homeworkModel = new SeHomeworkTeacher();
            $homeworkModel->chapterId = $chapterId;
            $homeworkModel->getType = 1;
            $homeworkModel->createTime = DateTimeHelper::timestampX1000();
            $homeworkModel->creator = user()->id;
            $homeworkModel->name = $homeworkName;
            $homeworkModel->subjectId = $subject;
            $homeworkModel->version = $version;
            $homeworkModel->department = $department;
            $homeworkModel->difficulty = $difficulty;
            if ($homeworkModel->save()) {
                $homeworkId = $homeworkModel->id;
                foreach ($questionArray as $v) {
                    $homeworkQuestionModel = new SeHomeworkQuestion();
                    $homeworkQuestionModel->questionId = $v['questionId'];
                    $homeworkQuestionModel->homeworkId = $homeworkId;
                    $homeworkQuestionModel->orderNumber = $v['orderNumber'];
                    $homeworkQuestionModel->save();
                }
                SeQuestionCartQeustions::deleteAll(['cartId' => $cartId]);
                SeQuestionCart::deleteAll(['cartId' => $cartId]);
                $transaction->commit();
                $jsonResult->success = true;
                //          创建电子作业增加积分
                $jfHelper = new JfManageService;
                $jfHelper->myAccount("pos-ele-question", user()->id);
            }

        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return $this->renderJSON($jsonResult);

    }

    /**
     * 更新选题篮
     * @return string
     */
    public function actionGetQuestionCart()
    {
        $subject = app()->request->getBodyParam('subject');
        $department = app()->request->getBodyParam('department');
        $cartQuery = SeQuestionCart::find()->where(['departmentId' => $department, 'subjectId' => $subject, 'userId' => user()->id]);
        $cartIsExisted = $cartQuery->exists();
        if (!$cartIsExisted) {
            $cartModel = new SeQuestionCart();
            $cartModel->departmentId = $department;
            $cartModel->subjectId = $subject;
            $cartModel->userId = user()->id;
            $cartModel->createTime = DateTimeHelper::timestampX1000();
            $cartModel->save();
            $num = 0;
            $cartId = $cartModel->cartId;
        } else {
            $cartId = $cartQuery->one()->cartId;
            $cartQuestionQuery = SeQuestionCartQeustions::find()->where(['cartId' => $cartId]);
            $num = $cartQuestionQuery->count();
        }
        $array = array('num' => $num, 'cartId' => $cartId);
        $jsonResult = new JsonMessage();
        $jsonResult->data = $array;
        return $this->renderJSON($jsonResult);
    }

    /**
     * 题目放到选题篮
     * @return string
     */
    public function actionAddCartQuestions()
    {
        $jsonResult = new JsonMessage();
        $cartId = app()->request->getBodyParam('cartId');
        $questionID = app()->request->getBodyParam('questionID');
//        判断当前题目是否已经在选题篮里面
        $isExisted = SeQuestionCartQeustions::find()->where(['cartId' => $cartId, 'questionId' => $questionID])->exists();
        if (!$isExisted) {
            $cartQuestionModel = new SeQuestionCartQeustions();
            $cartQuestionModel->cartId = $cartId;
            $cartQuestionModel->questionId = $questionID;
            $cartQuestionModel->createTime = DateTimeHelper::timestampX1000();
            $cartQuestionModel->orderNumber = (int)(microtime(true)*1000);
            if ($cartQuestionModel->save()) {
                $jsonResult->success = true;
            }
        };
        return $this->renderJSON($jsonResult);
    }

    /**
     *加载整个页面判断是否放入选题篮了
     */
    public function actionIfInCart()
    {
        $jsonResult = new JsonMessage();
        $department = app()->request->getBodyParam('department');
        $subject = app()->request->getBodyParam('subject');
        $cartResult = SeQuestionCart::find()->where(['subjectId' => $subject, 'departmentId' => $department, 'userId' => user()->id])->one();
        $cartId = $cartResult->cartId;
        $questionIDArray = app()->request->getBodyParam('questionIDArray');
        $isInCartArray = [];
        $isInCart = SeQuestionCartQeustions::find()->where(['cartId' => $cartId, 'questionId' => $questionIDArray])->select('questionId')->asArray()->all();
        foreach ($isInCart as $v) {
            array_push($isInCartArray, $v['questionId']);
        }
        $jsonResult->data = $isInCartArray;
        return $this->renderJson($jsonResult);
    }

    /**
     * 移出选题篮
     * @return string
     */
    public function actionDelQuestion()
    {
        $cartQuestionId = app()->request->get("cartQuestionId");
        $jsonResult = new JsonMessage();
        if ($cartQuestionId == null) {
            $cartId = app()->request->getBodyParam('cartId');
            $questionID = app()->request->getBodyParam('questionID');
            $cartResult = SeQuestionCartQeustions::find()->where(['cartId' => $cartId, 'questionId' => $questionID])->one();
            if ($cartResult) {
                $cartQuestionId = $cartResult->cartQuestionId;
            }
        }
        $checkQuestionCart = SeQuestionCartQeustions::find()->where(["cartQuestionId" => $cartQuestionId])->one();
        if (empty($checkQuestionCart)) {
            $jsonResult->success = false;
            $jsonResult->message = '请正确删除！';
        } else {
            $delQuestion = SeQuestionCartQeustions::deleteAll(['cartQuestionId' => $cartQuestionId]);
            if ($delQuestion == 1) {
                $jsonResult->success = true;
                $jsonResult->message = '删除成功！';
            } else {
                $jsonResult->success = false;
                $jsonResult->message = '删除失败！';
            }
        }
        return $this->renderJSON($jsonResult);
    }
}