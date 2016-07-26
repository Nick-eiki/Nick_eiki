<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 2016/1/19
 * Time: 11:25
 */

namespace frontend\modules\platform\controllers;


use common\models\sanhai\SrMaterial;
use frontend\components\helper\TreeHelper;
use frontend\components\helper\VersionHelper;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\LoadGradeModel;
use frontend\models\dicmodels\LoadTextbookVersionModel;
use yii\data\Pagination;

class FileController extends \frontend\components\BaseAuthController
{

    public $layout = "lay_platform";
    /**
     *平台资料库列表
     */
    public function actionIndex()
    {
        $proFirstime = microtime();
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $userInfo = loginUser()->getModel();

        $department = app()->request->get('department', '20201');
        $subjectId = app()->request->get('subjectId', '10010');
        $fileName = app()->request->get('fileName','');
        $mattype = app()->request->get('mattype','');
        $sortType = app()->request->get('sortType','createTime');


        $versions = VersionHelper::getVersionArr($department, $subjectId, LoadTextbookVersionModel::model($subjectId, '', $department)->getListData());
        $edition = app()->request->getParam('edition', key($versions));

        $gradeId = empty(LoadGradeModel::model()->getData($userInfo->schoolID, $department)[0]->gradeId) ? '' : LoadGradeModel::model()->getData($userInfo->schoolID, $department)[0]->gradeId;
        $tomeResult = ChapterInfoModel::getMajorChapter($subjectId,$department,$edition,$gradeId);
        $tomeDefault = '';
        if($tomeResult){
            $tomeDefault = $tomeResult[0]->id;
        }
        $tome = app()->request->get('tome', $tomeDefault);

        $chapterData = ChapterInfoModel::searchChapterPointToTree($subjectId, $department, $edition, null, null, null, $tome);
        $treeData = TreeHelper::streefun($chapterData, "", "tree pointTree");

        //列表
        $materialQuery = SrMaterial::find()->where(['department'=>$department,'subjectid'=>$subjectId ]);
        $materialQuery->andWhere(['like', 'versionid', $edition]);
        $materialQuery->andWhere(['like', 'chapterId', $tome]);
        if(!empty($mattype)){
            $materialQuery->andWhere(['matType'=>$mattype]);
        }
        if(!empty($sortType)){
            $materialQuery->orderBy("$sortType desc");
        }
        if(!empty($fileName)){
            $materialQuery->andWhere(['like', 'name', $fileName]);
        }

        $pages->totalCount = $materialQuery->count();
        $materialList = $materialQuery->offset($pages->getOffset())->limit($pages->getLimit())->all();

        $searchArr = array(
            'mattype' => $mattype,
            'fileName' => $fileName,
            'edition' => $edition,
            'gradeId' => $gradeId
        );
        $arr = [
            'sortType' => $sortType,
            'mattype' => $mattype,
            'fileName' => $fileName,
            'edition' => $edition,
            'gradeId' => $gradeId,
            'department' => $department,
            'subjectId' => $subjectId,
            'materialList' => $materialList,
            'tome' => $tome,
            'treeData' => $treeData,
            'tomeResult'=>$tomeResult,
            'versions'=>$versions,
            'pages' => $pages,
            'searchArr' => $searchArr,
        ];
        \Yii::info('课件库 '.(microtime()-$proFirstime),'service');
        if (app()->request->isAjax) {
            return $this->renderPartial('_index_list',$arr);
        }

        return $this->render('index',$arr);
    }


    /**
     * @param $text
     * @param $type
     * @param $yourType
     * @param $timeorder
     * @param $hotorder
     * @param $userId
     * @return array
     */
    private function reusableMaterials($text, $type, $yourType, $timeorder, $userId, $department, $subject, $edition, $grade, $chapter)
    {
        $pages = new Pagination();
        $pages->pageSize = 10;
        $data = SrMaterial::find()->where(["subjectid" => $subject, 'isplatform' => '1','isDelete'=>0]);
        if ($text != null) {
            $data->andWhere(['like', 'name', $text]);
        }
        if ($type != null) {
            $data->andWhere(["matType" => $type]);
        }
        if ($department != null) {
            $data->andWhere(["department" => $department]);
        }

        if ($chapter != null) {
            $data->andWhere(['like', "chapterId", $chapter]);
        }


        if ($edition != null) {
            $data->andWhere(['like', "versionid", $edition]);
        }
        $pages->totalCount = $data->count();
        if ($timeorder == 1) {
            $data->orderBy('createTime asc');
        } elseif ($timeorder == 2) {
            $data->orderBy('createTime desc');
        } elseif ($timeorder == 3) {
            $data->orderBy('readNum asc');
        } elseif ($timeorder == 4) {
            $data->orderBy('readNum desc');
        }
        $dataResult = $data->offset($pages->getOffset())->limit($pages->getLimit())->all();
        $pages->params = ['text' => $text, 'type' => $type, 'department' => $department, 'subjectid' => $subject, 'edition' => $edition, 'yourtype' => $yourType, 'timeorder' => $timeorder, 'chapter' => $chapter];
        return array($pages, $dataResult);
    }

} 