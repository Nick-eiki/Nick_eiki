<?php
namespace frontend\modules\teacher\controllers;

use common\models\JsonMessage;
use common\models\pos\SeQuestionFavoriteFolderNew;
use common\models\sanhai\ShTestquestion;
use frontend\components\helper\StringHelper;
use frontend\components\PrintMakePager;
use frontend\components\TeacherBaseController;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\modules\teacher\models\MakePaperForm;
use frontend\services\apollo\Apollo_QuestionInfoService;
use frontend\services\BaseService;
use frontend\services\pos\pos_PaperManageService;
use stdClass;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * Created by yangjie
 * User: Administrator
 * Date: 14-10-16
 * Time: 下午16:27
 */
class MakepaperController extends TeacherBaseController
{
    public $layout = "lay_user";


    public function  actionIndex()
    {

        return $this->actionPaperHeader();
    }


    /**
     * 设置试卷结构
     */
    public function actionPaperHeader()
    {

        $useinfo = loginUser()->getModel(false);
        $schPaperManageService = new  pos_PaperManageService();
        $makePaperForm = new MakePaperForm();
        $re = $schPaperManageService->createPaperHeader(user()->id);
        $makePaperForm->paperName = $re->name;
        $makePaperForm->provience = $re->provience ?: $useinfo->provience;
        $makePaperForm->city = $re->city ?: $useinfo->city;
        $makePaperForm->subject = $re->subjectId;
        $makePaperForm->county = $re->country ?: $useinfo->country;
        //   $makePaperForm->gradeId = $re->gradeId ?: $useinfo->gradeID;
        $makePaperForm->version = $re->version;
        $makePaperForm->knowledgePointId = $re->knowledgeId;
        $makePaperForm->author = $re->author;
        $makePaperForm->paperType = $re->paperType;
        $makePaperForm->paperDescribe = $re->paperDescribe;


        if (app()->request->isPost) {
            if (isset($_POST['MakePaperForm'])) {
                $makePaperForm->attributes = $_POST['MakePaperForm'];
                if ($makePaperForm->validate()) {
                    $result = $schPaperManageService->updatePaperHead($re->paperId,
                        $makePaperForm->paperName, $makePaperForm->provience,
                        $makePaperForm->city,
                        $makePaperForm->county,
                        $makePaperForm->gradeId,
                        $makePaperForm->subject,
                        $makePaperForm->version,
                        $makePaperForm->knowledgePointId,
                        $makePaperForm->author,
                        $makePaperForm->paperDescribe,
                        user()->id,
                        $makePaperForm->paperType
                    );

                    if ($result->resCode == $schPaperManageService::successCode) {
                        return $this->redirect(['paper-structure', 'paperId' => $re->paperId]);
                    }
                } else {
                    // var_dump($makePaperForm->getErrors());
                }
            }

        }

        return $this->render('paperHeader', ['model' => $makePaperForm]);
    }


    /**
     * 设置试卷结构
     */
    public function actionPaperStructure($paperId)
    {
        $schPaperManageService = new  pos_PaperManageService();
        $re = $schPaperManageService->queryTempPaper($paperId);
        if ($re == null) {
            return $this->notFound();
        }

        if (app()->request->isAjax) {
            $jsonResult = new JsonMessage();
            if (isset($_POST['pageMain'])) {
                $pageMain = $_POST['pageMain'];
                $result = $schPaperManageService->updatePaperContent($paperId,
                    $this->toModelDataJson($pageMain));

                if ($result->resCode == $schPaperManageService::successCode) {
                    $jsonResult->success = true;
                    $jsonResult->data = $paperId;
                } else {
                    $jsonResult->message = $result->resMsg;
                }
            }
            return $this->renderJSON($jsonResult);
        }
        return $this->render('paperStructure', ['treejson' => $re->pageMain]);
    }

    /**
     * 选择类型
     */
    public function  actionPaperSubject($paperId)
    {
        $this->layout = "lay_user_select_question";
        $schPaperManageService = new  pos_PaperManageService();
        $re = $schPaperManageService->queryTempPaper($paperId);


        //用于区别 题库类型   0平台题库  1我的题库  2我的收藏
        $n = app()->request->getParam('n', 0);
        if ($n == 0) {
            $userId = 0;
        } elseif ($n == 1 || $n == 2) {
            $userId = user()->id;
        }

        if ($re == null) {
            return $this->notFound();
        }

        $kid = app()->request->getParam('kid', '');
        $type = app()->request->getParam('type', '');
        $complexity = app()->request->getParam('complexity', '');

        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $questionQuery = ShTestquestion::find()->where(['mainQusId' => 0]);
        if ($n == 2) {
            //数据列表
            $favouriteQuestionList = SeQuestionFavoriteFolderNew::find()->where(['userId' => $userId, 'isDelete' => 0])->select('questionId')->asArray()->all();
            $favouriteQuestionArray = ArrayHelper::getColumn($favouriteQuestionList, 'questionId');

            $questionQuery->andWhere(['in', 'id', $favouriteQuestionArray]);
        }
        $questionQuery->andWhere(['operater' => $userId]);
        if ($type != null) {
            $questionQuery->andWhere(['tqtid' => $type]);
        }
        if ($complexity != null) {
            $questionQuery->andWhere(['complexity' => $complexity]);
        }
        if ($re->subjectId != null) {
            $questionQuery->andWhere(['subjectid' => $re->subjectId]);
        }

        if ($kid != null) {
            $questionQuery->andWhere(['like', 'kid', $kid]);
        }
        $questionResult = $questionQuery->orderBy('year desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();
        $pages->totalCount = $questionQuery->count();
        if (app()->request->isAjax) {

            return $this->renderPartial("_pageSubjectView", array('list' => $questionResult, 'pages' => $pages));

        }

        //知识点
        $knowTRee = KnowledgePointModel::searchKnowledgePointGradeToTree($re->subjectId, $re->gradeId);

        $queryType = $schPaperManageService->queryQuestions($paperId, user()->id);
        return $this->render('paperSubject', array('queryType' => $queryType, 'list' => $questionResult, 'pages' => $pages, 'knowTRee' => $knowTRee));
    }


    /**
     * 保存选中题目
     */
    public function  actionSavePaperSubject($paperId)
    {
        $jsonMessage = new JsonMessage();
        if (app()->request->isAjax && $_POST['items']) {
            $questions = [];
            foreach ($_POST['items'] as $key => $value) {
                $stdclass = new stdClass();
                $stdclass->typeId = $key;
                $stdclass->questions = [];
                $arrs = StringHelper::splitNoEMPTY($value);
                foreach ($arrs as $v) {
                    array_push($stdclass->questions, ['id' => $v]);
                }
                $questions[] = $stdclass;
            }
            $schPaperManageService = new  pos_PaperManageService();
            $result = $schPaperManageService->updateQuestionAll($paperId, user()->id, json_encode($questions));
            if ($result->resCode == pos_PaperManageService::successCode) {
                $jsonMessage->success = true;
            }
        }


        return $this->renderJSON($jsonMessage);
    }

    public function actionViewPagerById()
    {
        $qid = app()->request->getParam('qid', '');
        $questionInfoService = new Apollo_QuestionInfoService();
        $question = $questionInfoService->questionSearchById($qid);
        return $this->renderPartial('_pageProblemView', array('list' => $question->list));
    }


    /**
     * 设置分值
     */
    public function  actionPaperSetScore()
    {
        $schPaperManageService = new  pos_PaperManageService();

        $paperId = app()->request->getParam('paperId');
        if (isset($_POST['score'])) {
            $scores = array();
            foreach ($_POST['score'] as $key => $item) {
                $scores[] = array('id' => $key, 'score' => $item);
            }
            $result = $schPaperManageService->scoreAllQuestion($paperId, user()->id, json_encode($scores));
            if ($result->resCode == pos_PaperManageService::successCode) {
//                Yii::$app->getSession()->setFlash('success', '设置成功');
//                $this->refresh();
                return $this->redirect(url('teacher/managepaper'));
            }
        }

        $questions = $schPaperManageService->queryQuestions($paperId, user()->id);
        return $this->render('paperSetScore', ['selectQuestions' => $questions]);

    }

    /**
     *AJAX保存试卷分值
     */
    public function actionSetScore()
    {
        $paperId = app()->request->getParam("paperId");
        $scoreJson = app()->request->getParam("scoreJson");
        $jsonResult = new JsonMessage();
        $paperServer = new pos_PaperManageService();
        $paperResult = $paperServer->scoreAllQuestionNosta($paperId, user()->id, $scoreJson);
        if ($paperResult->resCode == BaseService::successCode) {
            $jsonResult->code = 1;
        } else {
            $jsonResult->code = 0;
            $jsonResult->message = $paperResult->resMsg;
        }
        return $this->renderJSON($jsonResult);

    }

    /**
     * 试卷预览
     */
    public function   actionPaperReview()
    {
        $this->layout = "lay_user_blank";

        $paperId = app()->request->getParam('paperId', '');

        $pagerServer = new pos_PaperManageService();
        $result = $pagerServer->queryTempPaper($paperId);

        if (empty($result->pageMain)) {
            return $this->notFound();
        } else {
            return $this->render('paperReview', array('result' => $result));
        }

    }


    public function     toZtreeData($jsonModel)
    {
        $jsondata = $jsonModel;
        $pageMain = new stdClass();

        $line = ['id' => 'line', 'name' => '装订线', 'dataid' => 0, 'pId' => 'testPaperHead', 'text' => '', 'checked' => true];
        if (isset($jsondata->line)) {
            $line['checked'] = $jsondata->line->ischecked == 'true';
        }
        $pageMain->paperHead[] = $line;


        $secret_sign = ['id' => 'secret_sign', 'name' => '绝密★启用前', 'pId' => 'testPaperHead', 'dataid' => 0, 'text' => '绝密★启用前', 'checked' => true];
        if (isset($jsondata->secret_sign)) {
            $secret_sign['checked'] = (bool)($jsondata->secret_sign->ischecked) == 'true';
        }
        $pageMain->paperHead[] = $secret_sign;


        $main_title = ['id' => 'main_title', 'name' => '主标题', 'pId' => 'testPaperHead', 'text' => '2013-2014学年度xx学校xx月考卷', 'dataid' => 0, 'checked' => true];
        if (isset($jsondata->main_title)) {
            $main_title['checked'] = $jsondata->main_title->ischecked == 'true';
            $main_title['text'] = $jsondata->main_title->title ? $jsondata->main_title->title : '2013-2014学年度xx学校xx月考卷';
        }
        $pageMain->paperHead[] = $main_title;

        $sub_title = ['id' => 'sub_title', 'name' => '副标题', 'pId' => 'testPaperHead', 'text' => '内部模拟考试', 'dataid' => 0, 'checked' => true];
        if (isset($jsondata->sub_title)) {
            $sub_title['checked'] = $jsondata->sub_title->ischecked == 'true';
        }

        $pageMain->paperHead[] = $sub_title;


        $info = ['id' => 'info', 'name' => '范围/时间', 'pId' => 'testPaperHead', 'text' => '内部模拟考试', 'dataid' => 0, 'checked' => true];
        if (isset($jsondata->info)) {
            $info['checked'] = $jsondata->info->ischecked == 'true';
            $info['text'] = $jsondata->info->title;
        }
        $pageMain->paperHead[] = $info;

        $student_input = ['id' => 'student_input', 'name' => '学生输入', 'pId' => 'testPaperHead', 'dataid' => 0, 'text' => '考试范围：xxx；考试时间：100分钟；命题人：xxx', 'checked' => true];

        if (isset($jsondata->student_input)) {
            $student_input['checked'] = $jsondata->student_input->ischecked == 'true';
            $student_input['text'] = $jsondata->student_input->title;
        }
        $pageMain->paperHead[] = $student_input;

        $pay_attention = ['id' => 'pay_attention', 'name' => '注意事项', 'pId' => 'testPaperHead', 'dataid' => 0, 'text' => '考试范围：xxx；考试时间：100分钟；命题人：xxx', 'checked' => true];


        if (isset($jsondata->pay_attention)) {
            $pay_attention['checked'] = $jsondata->pay_attention->ischecked == 'true';
            $pay_attention['text'] = $jsondata->pay_attention->title;
        }
        $pageMain->paperHead[] = $pay_attention;


        $win_paper_typeone = ['id' => 'win_paper_typeone', 'name' => '第一卷(选择题)', 'pId' => 'testPaperBody', 'dataid' => 0, 'text' => '注释内容', 'open' => true, 'checked' => true];

        if (isset($jsondata->win_paper_typeone)) {
            $win_paper_typeone['checked'] = $jsondata->win_paper_typeone->ischecked == 'true';
            $win_paper_typeone['text'] = $jsondata->win_paper_typeone->title;
            $win_paper_typeone['dataid'] = $jsondata->win_paper_typeone->id;
        }
        $pageMain->paperBody[] = $win_paper_typeone;


        $win_paper_typetwo = ['id' => 'win_paper_typetwo', 'name' => '第二卷(非选择题)', 'pId' => 'testPaperBody', 'dataid' => 0, 'text' => '第二卷(非选择题)', 'open' => true, 'checked' => true];

        if (isset($jsondata->win_paper_typetwo)) {
            $win_paper_typetwo['checked'] = $jsondata->win_paper_typetwo->ischecked == 'true';
            $win_paper_typetwo['text'] = $jsondata->win_paper_typetwo->title;
            $win_paper_typetwo['dataid'] = $jsondata->win_paper_typetwo->id;
        }

        $pageMain->paperBody[] = $win_paper_typetwo;

        if (isset($jsondata->win_paper_typeone->questionTypes)) {
            foreach ($jsondata->win_paper_typeone->questionTypes as $item) {
                $l = ['pId' => 'win_paper_typeone', 'name' => '', 'dataid' => 0, 'text' => '', 'checked' => true];
                $l['id'] = $l['dataid'] = $item->id;
                $l['name'] = $item->title;
                $l['text'] = $item->content;
                $l['checked'] = $item->ischecked == 'true';

                $pageMain->paperBody[] = $l;
            }
        }

        if (isset($jsondata->win_paper_typetwo->questionTypes)) {
            foreach ($jsondata->win_paper_typetwo->questionTypes as $item) {
                $l = ['id' => '', 'pId' => 'win_paper_typetwo', 'name' => '', 'dataid' => 0, 'text' => '', 'checked' => true];
                $l['id'] = $l['dataid'] = $item->id;
                $l['name'] = $item->title;
                $l['text'] = $item->content;
                $l['checked'] = $item->ischecked == 'true';
                $pageMain->paperBody[] = $l;
            }
        }

        return $pageMain;
    }

    public function   toModelDataJson($json)
    {

        //  $json = '{"paperHead":[{"id":"line","name":"装订线","dataid":0,"text":"","checked":true},{"id":"secret_sign","name":"绝密★启用前","dataid":0,"text":"绝密★启用前","checked":true},{"id":"main_title","name":"主标题","text":"2013-2014学年度xx学校xx月考卷","dataid":0,"checked":true},{"id":"sub_title","name":"副标题","text":"内部模拟考试","dataid":0,"checked":true},{"id":"info","name":"范围/时间","text":"","dataid":0,"checked":true},{"id":"student_input","name":"学生输入","dataid":0,"text":"","checked":true},{"id":"pay_attention","name":"注意事项","dataid":0,"text":"","checked":true}],"paperBody":[{"id":"win_paper_typeone","name":"第一卷(选择题)","dataid":10021,"text":"第I卷（选择题）","open":true,"checked":true},{"id":"win_paper_typetwo","name":"第二卷(非选择题)","dataid":10022,"text":"第II卷（非选择题）","open":true,"checked":true},{"pId":"win_paper_typeone","name":"单选题","dataid":"1","text":"","checked":true,"id":"1"},{"id":"2","pId":"win_paper_typetwo","name":"填空题","dataid":"2","text":"","checked":true},{"id":"3","pId":"win_paper_typetwo","name":"计算题","dataid":"3","text":"","checked":true},{"id":"4","pId":"win_paper_typetwo","name":"解答题","dataid":"4","text":"","checked":true},{"id":"5","pId":"win_paper_typetwo","name":"判断题","dataid":"5","text":"","checked":true}]}';
        $jsonArr = json_decode($json, true);


        $pageMain = new stdClass();
        $pageMain->student_input = ['title' => '', 'content' => '', 'ischecked' => 0];
        $pageMain->line = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
        $pageMain->main_title = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
        $pageMain->sub_title = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
        $pageMain->pay_attention = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
        $pageMain->info = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
        $pageMain->secret_sign = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
        $pageMain->performance = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];


        if (isset($jsonArr['paperHead'])) {

            $paperHead = $jsonArr['paperHead'];
            $line = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'line';
            });
            if ($line != null) {
                $pageMain->line['title'] = $line['name'];
                $pageMain->line['ischecked'] = $line['checked'];
                $pageMain->line['content'] = $line['text'];

            }

            $student_input = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'student_input';
            });
            if ($student_input != null) {

                $pageMain->student_input['title'] = $student_input['name'];
                $pageMain->student_input['ischecked'] = $student_input['checked'];
                $pageMain->student_input['content'] = $student_input['text'];


            }

            $main_title = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'main_title';
            });

            if ($main_title != null) {
                $pageMain->main_title['title'] = $main_title['name'];
                $pageMain->main_title['ischecked'] = $main_title['checked'];
                $pageMain->main_title['content'] = $main_title['text'];

            }

            $sub_title = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'sub_title';
            });

            if ($sub_title != null) {
                $pageMain->sub_title['title'] = $sub_title['name'];
                $pageMain->sub_title['ischecked'] = $sub_title['checked'];
                $pageMain->sub_title['content'] = $sub_title['text'];
            }

            $pay_attention = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'pay_attention';
            });

            if ($pay_attention != null) {
                $pageMain->pay_attention['title'] = $pay_attention['name'];
                $pageMain->pay_attention['ischecked'] = $pay_attention['checked'];
                $pageMain->pay_attention['content'] = $pay_attention['text'];
            }

            $info = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'info';
            });

            if ($info != null) {
                $pageMain->info['title'] = $info['name'];
                $pageMain->info['ischecked'] = $info['checked'];
                $pageMain->info['content'] = $info['text'];
            }

            $secret_sign = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'secret_sign';
            });

            if ($info != null) {
                $pageMain->secret_sign['title'] = $secret_sign['name'];
                $pageMain->secret_sign['ischecked'] = $secret_sign['checked'];
                $pageMain->secret_sign['content'] = $secret_sign['text'];
            }


        }

        $pageMain->win_paper_typeone = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => '', 'questionTypes' => []];
        $pageMain->win_paper_typetwo = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => '', 'questionTypes' => []];

        if (isset($jsonArr['paperBody'])) {
            $paperBody = $jsonArr['paperBody'];
            $win_paper_typeone = from($paperBody)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'win_paper_typeone';
            });

            if ($win_paper_typeone != null) {
                $pageMain->win_paper_typeone['title'] = $win_paper_typeone['name'];
                $pageMain->win_paper_typeone['ischecked'] = $win_paper_typeone['checked'];
                $pageMain->win_paper_typeone['content'] = $win_paper_typeone['text'];
                $pageMain->win_paper_typeone['id'] = $win_paper_typeone['dataid'];


                $win_paper_typeoneList = from($paperBody)->where(
                    function ($v) {
                        return isset($v["pId"]) && $v["pId"] == 'win_paper_typeone';
                    }
                )->toArray();

                foreach ($win_paper_typeoneList as $item) {
                    $o = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
                    $o['title'] = $item['name'];
                    $o['ischecked'] = $item['checked'];
                    $o['content'] = $item['text'];
                    $o['id'] = $item['dataid'];
                    $pageMain->win_paper_typeone["questionTypes"][] = $o;
                }


            }

            $win_paper_typetwo = from($paperBody)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'win_paper_typetwo';
            });

            if ($win_paper_typetwo != null) {
                $pageMain->win_paper_typetwo['title'] = $win_paper_typetwo['name'];
                $pageMain->win_paper_typetwo['ischecked'] = $win_paper_typetwo['checked'];
                $pageMain->win_paper_typetwo['content'] = $win_paper_typetwo['text'];
                $pageMain->win_paper_typetwo['id'] = $win_paper_typetwo['dataid'];
            }

            $win_paper_typetwoList = from($paperBody)->where(
                function ($v) {
                    return isset($v["pId"]) && $v["pId"] == 'win_paper_typetwo';
                }
            )->toArray();

            foreach ($win_paper_typetwoList as $item) {
                $o = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
                $o['title'] = $item['name'];
                $o['ischecked'] = $item['checked'];
                $o['content'] = $item['text'];
                $o['id'] = $item['dataid'];
                $pageMain->win_paper_typetwo["questionTypes"][] = $o;
            }


        }

        return $pageMain;

    }

    //下载试卷
    public function   actionPrintWord($id)
    {
//        foreach (Yii::$app->log->routes as $route) {
//            if ($route instanceof CWebLogRoute) {
//                $route->enabled = false;
//            }
//        }
        $q = new  pos_PaperManageService();
        $result = $q->queryMakerPaperById($id);

        $pager = new   PrintMakePager($result);
        $pager->run();


    }


}