<?php
namespace frontend\modules\teacher\controllers;

use common\models\JsonMessage;
use common\models\pos\SeFavoriteFolder;
use common\models\pos\SeShareMaterial;
use common\models\sanhai\ShResourceError;
use common\models\sanhai\SrMaterial;
use frontend\components\helper\TreeHelper;
use frontend\components\helper\VersionHelper;
use frontend\components\TeacherBaseController;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\dicmodels\LoadGradeModel;
use frontend\models\dicmodels\LoadTextbookVersionModel;
use frontend\models\dicmodels\SubjectModel;
use frontend\services\apollo\Apollo_MaterialService;
use frontend\services\BaseService;
use frontend\services\pos\pos_FavoriteFolderService;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-23
 * Time: 下午3:08
 */
class PrepareController extends TeacherBaseController
{
    public $layout = "lay_prepare";

    public function actionIndex()
    {

        return $this->actionSearchList();
    }

    /**
     *上传文件
     */
    public function actionUploadFiles()
    {
//        获取当前人的信息
        $user = loginUser()->getModel();
        $versionID = $user->textbookVersion;
        $classInfo = $user->getUserInfoInClass()[0];
        $subjectID = $classInfo['subjectNumber'];
        $gradeID = $classInfo['gradeID'];
        $department = loginUser()->getModel()->department;
        $gradeModel = GradeModel::model()->getData($department, "");
        $gradeArray = array();
        foreach ($gradeModel as $v) {
            $gradeArray[$v['gradeId']] = $v['gradeName'];
        }
        $subjectModel = SubjectModel::model()->getSubjectByDepartment($department, 1);
        $subjectArray = array();
        foreach ($subjectModel as $v) {
            $subjectArray[$v->secondCode] = $v->secondCodeValue;
        }
        $versionArray = LoadTextbookVersionModel::model($subjectID, $gradeID)->getListData();
        return $this->render("uploadFiles", array("gradeArray" => $gradeArray,
            "subjectArray" => $subjectArray,
            "versionArray" => $versionArray,
            "versionID" => $versionID,
            "subjectID" => $subjectID,
            "gradeID" => $gradeID
        ));
    }

    /**
     *获取知识树
     */
    public function actionGetKnowTree()
    {
        $version = app()->request->getParam("version");
        $subject = app()->request->getParam("subject");
        $department = loginUser()->getModel()->department;
        $type = app()->request->getParam("type");
        if ($type == 1) {
            $obj = KnowledgePointModel::searchAllKnowledgePoint($subject, $department);
        } else {
            $obj = ChapterInfoModel::searchChapterPointToTree($subject, $department, $version, "", "");
        }
        $treeData = TreeHelper::streefun($obj, "", "tree pointTree");
        return $this->renderPartial("_know_tree", array("treeData" => $treeData));
    }

    /**
     *AJAX上传文件
     */
    public function actionAjaxUpload()
    {
        $jsonResult = new JsonMessage();
        $server = new Apollo_MaterialService();
        $gradeID = app()->request->getParam("gradeID");
        $subjectID = app()->request->getParam("subjectID");
        $versionID = app()->request->getParam("versionID");
        $matType = app()->request->getParam("matType");
        $contentType = app()->request->getParam("contentType");
        $name = app()->request->getParam("name");
        $department = loginUser()->getModel()->department;
        $url = app()->request->getParam("url");
        $access = app()->request->getParam("access");
        $chapKids = app()->request->getParam("chapKids");
        $result = $server->uploadFiles($name, $matType, $department, $gradeID, $subjectID, $versionID, $contentType, $chapKids, $url, user()->id, $access);
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        }
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *推荐数据
     */
    public function actionRecommend()
    {
        $dataval = app()->request->getParam('dataval', '');
        $arrval = array();
        if (!$dataval) {
            $arrval = explode(',', $dataval);
        }
        $text = app()->request->getParam('text', '');
        $department = app()->request->getParam('department', '');
        $subject = app()->request->getParam('subject');
        $type = app()->request->getParam('type', '');
        $userId = user()->id;//判断收藏人
        $dataQuery = SrMaterial::find()->where(['isplatform' => 1]);
        if ($type) {
            $dataQuery->andWhere(['matType' => $type]);
        }
        if ($text) {
            $dataQuery->andWhere(['name' => $text]);
        }
        if ($subject) {
            $dataQuery->andWhere(['subjectid' => $subject]);
        }
        if ($department) {
            $dataQuery->andWhere(['department' => $department]);
        }
        $result = $dataQuery->limit(20)->all();
        $arr = [];
        if (!empty($result)) {
            foreach ($result as $item) {
                if (!in_array($item->id, $arrval)) {
                    array_push($arr, $item);
                }
            }
            $arr = array_slice($arr, 1, 4);
        }
        return $this->renderPartial('_recommend_list_view', ['result' => $arr]);
    }


    /**
     * 阅读文件
     * @param $id
     */
    public function actionViewDoc($id)
    {
        $model = new Apollo_MaterialService();
        $result = $model->getMaterialById($id, '', '');
        return $this->render('viewdoc', ['result' => $result, 'id' => $id]);
    }

    /**
     *添加阅读次数
     */
    public function actionGetReadNum()
    {
        $id = app()->request->getParam('id', 0);
        $readNum = new Apollo_MaterialService();
        $model = $readNum->increaseReadNum($id, '');
        $jsonResult = new JsonMessage();
        if ($model->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->data = $model->data->readNum;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "添加失败！";
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     *收藏和取消收藏
     */
    public function actionAddMaterial()
    {
        $add = new pos_FavoriteFolderService();
        $id = app()->request->getParam('id', 0);
        $userId = user()->id;
        $favoriteType = app()->request->getParam('type', '');
        $action = app()->request->getParam('action', '');
        if ($action == 1) {
            $model = $add->addFavoriteFolder($id, $favoriteType, $userId);
        } else {
            $model = $add->delFavoriteFolderByDtl($id, $favoriteType, $userId);
        }
        $jsonResult = new JsonMessage();
        if ($model->resCode === BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '失败！';
            return $this->renderJSON($jsonResult);
        }
    }


    /**
     *我的分享弹窗
     */
    public function actionGetShareBox()
    {
        $id = app()->request->getParam('id', '');
        $arr = explode(',', $id);
        $model = new Apollo_MaterialService();
        $result = [];
        foreach ($arr as $val) {
            $result[] = $model->getMaterialById($val, user()->id, '');
        }
        return $this->renderPartial('_getShareBox_view', ['result' => $result, 'id' => $id]);
    }

    /**
     *收藏分享
     */
    public function actionGetMaterialBox()
    {
        $id = app()->request->getParam('id', '');
        $arr = explode(',', $id);
        $model = new Apollo_MaterialService();
        $result = [];
        foreach ($arr as $val) {
            $result[] = $model->getMaterialById($val, '', user()->id);
        }
        return $this->renderPartial('_getShareBox_view', ['result' => $result, 'id' => $id]);
    }


    /**
     *分享课件 单个/批量
     */
    public function actionSharedMaterial()
    {
        $jsonResult = new JsonMessage();
        $matId = app()->request->getParam('shareid', '');
        $classId = app()->request->getParam('classid', '');
        $groupId = app()->request->getParam('groupid', '');
        $userId = user()->id;

        $shareToClass = SeShareMaterial::shareToClass($classId, $userId, $matId);
        if ($shareToClass == false) {
            $jsonResult->message = '共享失败';
            return $this->renderJSON($jsonResult);
        }
        $shareToGroup = SeShareMaterial::shareToGroup($groupId, $userId, $matId);
        if ($shareToGroup == false) {
            $jsonResult->message = '共享失败';
            return $this->renderJSON($jsonResult);
        }

        $jsonResult->success = true;
        $jsonResult->message = "共享成功";

        return $this->renderJSON($jsonResult);
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

    /**
     * @param $text
     * @param $type
     * @param $yourType
     * @param $timeorder
     * @param $hotorder
     * @param $userId
     * @return array
     */
    private function yourMaterial($text, $type, $yourType, $timeorder, $userId, $department, $subject, $edition, $grade, $chapter)
    {
        $pages = new Pagination();
        $pages->pageSize = 10;
        $data = SrMaterial::find()->where(["creator" => $userId,'isDelete'=>0]);
        if ($text != null) {
            $data->andWhere(['like', "name", $text]);
        }
        if ($type != null) {
            $data->andWhere(["matType" => $type]);
        }
        if ($department != null) {
            $data->andWhere(["department" => $department]);
        }
        if ($subject != null) {
            $data->andWhere(["subjectid" => $subject]);
        }
        if ($chapter != null) {
            $data->andWhere(['like', "chapterId", $chapter]);
        }
        $pages->totalCount = $data->count();
        $dataResult = $data->orderBy("createTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();
        $pages->params = ['text' => $text, 'type' => $type, 'department' => $department, 'subjectid' => $subject, 'edition' => $edition, 'yourtype' => $yourType, 'chapter' => $chapter];
        return array($pages, $dataResult);
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
    private function collectionMaterial($text, $type, $yourType, $timeorder, $userId, $department, $subject, $edition, $grade)
    {
        $collectionPages = new Pagination();
        $collectionPages->validatePage = false;
        $collectionPages->pageSize = 10;
        //查询收藏数据
        $seFavoriteFolderList = SeFavoriteFolder::find()->where(['creatorID'=>$userId,'isDelete'=>0])->select('favoriteId')->all();
        $arr = ArrayHelper::getColumn($seFavoriteFolderList,'favoriteId');
        $srMaterialQuery=SrMaterial::find()->where(['id'=>$arr])->andWhere(['isDelete'=>0]);
        if($type!=null){
            $srMaterialQuery->andWhere(['matType'=>$type]);
        }
        $favorites=$srMaterialQuery->offset($collectionPages->getOffset())->limit($collectionPages->getLimit())->all();
        $collectionPages->totalCount = intval($srMaterialQuery->count());
        $collectionPages->params = ['text' => $text, 'type' => $type, 'yourtype' => $yourType, 'timeorder' => $timeorder, 'department' => $department, 'subjectid' => $subject, 'edition' => $edition, 'grade' => $grade];
        return array($collectionPages, $favorites);
    }

    /**
     *
     * @return array
     */
    private function searchType()
    {
        $userInfo = loginUser()->getModel();

        $department = app()->request->getParam('department', $userInfo->department);
        if ($department == null) {
            $department = $userInfo->department;
        }
        $subject = app()->request->getParam('subjectid', $userInfo->subjectID);
        if ($subject == null) {
            $subject = $userInfo->subjectID;
        }
        $edition = app()->request->getParam('edition', $userInfo->textbookVersion);
        if ($edition == null) {
            $edition = $userInfo->textbookVersion;
        }
        $versions = VersionHelper::getVersionArr($department, $subject, LoadTextbookVersionModel::model($subject, '', $department)->getListData());
        if(!array_key_exists($edition,$versions)){
            $edition= key($versions);
        }

        $text = app()->request->getParam('text', '');
        $tome = app()->request->getQueryParam('tome', '');
        $type = app()->request->getParam('type', '');
        $yourType = app()->request->getParam('yourtype', 1);

        $gradeId = empty(LoadGradeModel::model()->getData($userInfo->schoolID, $department)[0]->gradeId) ? '' : LoadGradeModel::model()->getData($userInfo->schoolID, $department)[0]->gradeId;
        $grade = app()->request->getParam('grade', '');
        $timeorder = app()->request->getParam('timeorder', '2');
        $chapter = app()->request->getParam("chapter");

        $userId = user()->id;
        return array($userInfo, $text, $type, $yourType, $timeorder, $userId, $department, $subject, $edition, $grade, $tome, $chapter,$versions);
    }

    //资源纠错
    public function actionResourceError()
    {
        $jsonResult = new JsonMessage();
        $materialId = app()->request->post('materialId', '');
        $material = SrMaterial::find()->where(['id' => $materialId])->one();

        if ($material && !empty($_POST)) {
            $resourceError = ShResourceError::find()->where(['resourceId' => $materialId])->one();
            if ($resourceError) {
                $jsonResult->success = true;
            } else {
                $errorType = app()->request->post('errorType', '');
                $resourceErrorModel = new ShResourceError();
                $resourceErrorModel->resourceId = $materialId;
                $resourceErrorModel->errorType = $errorType;
                $resourceErrorModel->userName = \frontend\components\WebDataCache::getTrueName(user()->id);
                $resourceErrorModel->brief = $_POST['brief'];
                $resourceErrorModel->userId = user()->id;
                $resourceErrorModel->createTime = times();
                if ($resourceErrorModel->save()) {
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