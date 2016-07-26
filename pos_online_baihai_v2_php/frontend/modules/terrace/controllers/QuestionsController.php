<?php
namespace frontend\modules\terrace\controllers;

use frontend\components\BaseAuthController;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\dicmodels\LoadSubjectModel;
use frontend\services\apollo\Apollo_QuestionInfoService;
use frontend\services\apollo\Apollo_QuestSearchModel;
use stdClass;
use yii\data\Pagination;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 *
 * User: gaocailong
 * Date: 14-11-17
 * Time: 下午3:23
 */
class QuestionsController extends BaseAuthController
{
    public $layout = "lay_ku";

    /**
     *题库首页
     */
    public function actionIndex()
    {

        $small = '20201';
        $middleSchool = '20202';
        $highSchool = '20203';
        return $this->render('index', array('grade' => $small, 'middleSchool' => $middleSchool, 'highSchool' => $highSchool));
    }

    /**
     *搜索试题
     */
    public function actionSearchKnowledgePoint()
    {

        $kid = app()->request->getQueryParam('kid', '');
        $tags = app()->request->getQueryParam('tags', '');
        $provience = app()->request->getQueryParam('provience', '');
        $city = app()->request->getQueryParam('city', '');
        $country = app()->request->getQueryParam('country', '');
        $gradeid = app()->request->getQueryParam('gradeid', '');
        $versionid = app()->request->getQueryParam('versionid', '');
        $typeId = app()->request->getQueryParam('typeId', '');
        $provenance = app()->request->getQueryParam('provenance', '');
        $school = app()->request->getQueryParam('school', '');
        $year = app()->request->getQueryParam('year', '');
        $department = app()->request->getQueryParam('department', '');
        $subject = app()->request->getQueryParam('subjectid', '');
        if ($subject == "") {
            return $this->redirect(url("ku/questions/index"));

        }
        $kModelList = KnowledgePointModel::searchAllKnowledgePoint($subject, $department);
        $kModel = $this->makeTree($kModelList, $kid);
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        $questionInfoService = new Apollo_QuestionInfoService();
        $qu = new Apollo_QuestSearchModel();

        if (!empty($kid)) {
            $qu->kid = $kid;
            $qu->subjectid = $subject;
            $qu->schoolLevel = $department;

        } else {
            $qu->tags = $tags;
            $qu->provience = $provience;
            $qu->city = $city;
            $qu->country = $country;
            $qu->gradeid = $gradeid;
            $qu->versionid = $versionid;
            $qu->typeId = $typeId;
            $qu->provenance = $provenance;
            $qu->school = $school;
            $qu->year = $year;
            $qu->subjectid = $subject;

        }
        $qu->pageSize = $pages->pageSize;
        $qu->currPage = $pages->getPage() + 1;

        $result = $questionInfoService->questionSearch($qu);
        $pages->params = ['tags' => $tags, 'provience' => $provience, 'city' => $city, 'country' => $country, 'gradeid' => $gradeid, 'versionid' => $versionid,
            'typeId' => $typeId, 'provenance' => $provenance, 'school' => $school, 'year' => $year, 'kid' => $kid, 'subjectid' => $subject, 'department' => $department];
        $pages->totalCount = $result->countSize;
        $list = $result->list;

        if (app()->request->isAjax) {
            $this->layout = '@app/views/layouts/blank';;
            return $this->renderPartial("_search_list", array('list' => $list, 'pages' => $pages));
        }

        return $this->render('searchKnowledgePoint', array('kModel' => $kModel, 'department' => $department, 'subject' => $subject, 'model' => $qu, 'pages' => $pages, 'list' => $list));
    }

    /**
     *重新定义树形结构并添加链接
     * @param $kModelList
     * @return array
     */
    protected function makeTree($kModelList, $kid)
    {
        $kModel = [];
        $callback =
            function ($item) use ($kid) {
                $k = new  stdClass();
                $k->id = $item->id;
                $k->pId = $item->pId;
                $k->name = $item->name;
                $k->url =  Url::to(['', 'department' => $item->schoolLevel, 'subjectid' => $item->subject, 'kid' => $item->id]);
                $k->target = '_self';
                if ($kid == $item->id) {
                    $k->font = ["color" => "red"];
                    $k->open = true;
                }
                return $k;
            };
        foreach ($kModelList as $item) {
            $kModel[] = $callback($item);
        }
        return $kModel;
    }

    /**
     * 根据小学部查询学科和知识点
     * @param $department
     */
    public function actionSmallSchool()

    {
        $department = '20201';
        $subject = LoadSubjectModel::model()->getData($department, '');
        $this->getView()->title="平台--小学";
        return $this->render('school', array('subject' => $subject, 'department' => $department));
    }

    /**
     * 根据中学查询学科知识点
     * @param $department
     */
    public function actionMiddleSchool()
    {
        $department = '20202';
        $subject = LoadSubjectModel::model()->getData($department, '');

        $this->getView()->title="平台--初中";
        return $this->render('school', array('subject' => $subject, 'department' => $department));
    }

    /**
     * 根据高中查询学科和知识点
     * @param $department
     */
    public function actionHighSchool()
    {
        $department = '20203';
        $subject = LoadSubjectModel::model()->getData($department, '');
        $this->getView()->title="平台--高中";
        return $this->render('school', array('subject' => $subject, 'department' => $department));
    }

    /**
     *平台搜索
     */
    public function actionGetSearchByName()
    {
        $tags = app()->request->getQueryParam('tags', '');
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 1;
        $questionInfoService = new Apollo_QuestionInfoService();
        $qu = new Apollo_QuestSearchModel();

        $qu->tags = $tags;
        $qu->pageSize = $pages->pageSize;
        $qu->currPage = $pages->getPage() + 1;
        $result = $questionInfoService->questionSearch($qu);
        $pages->params = ['tags' => $tags];
        $pages->totalCount = $result->countSize;

        $list = $result->list;

        return $this->render('getSearchByName', array('tagsModel' => $list, 'pages' => $pages));

    }
}