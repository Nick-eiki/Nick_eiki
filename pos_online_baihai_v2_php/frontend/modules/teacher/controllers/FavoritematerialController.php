<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 2016/1/19
 * Time: 11:25
 */

namespace frontend\modules\teacher\controllers;

use common\models\JsonMessage;
use common\models\pos\SeFavoriteMaterial;
use common\models\pos\SeFavoriteMaterialGroup;
use common\models\pos\SeShareMaterial;
use common\models\sanhai\SrMaterial;
use frontend\components\TeacherBaseController;
use Yii;
use yii\data\Pagination;

/**
 * Class FavoritematerialController
 * @package frontend\modules\teacher\controllers
 */
class FavoritematerialController extends TeacherBaseController
{
    /**
     *  学段——小学
     */
    const DEPARTMENT = 20201;

    /**
     * 学科——语文
     */
    const SUBJECT = 10010;

    /**
     *是否删除
     */
    const ISDELETE = 0;

    /**
     *是否禁用
     */
    const DESABLED = 0;

    /**
     *0:我的收藏 1：自定义分组  2：我的创建
     */
    const GROUPTYPE_DEFAULT = 0;

    /**
     *0:我的收藏 1：自定义分组  2：我的创建
     */
    const GROUPTYPE_FAVORITE = 1;

    /**
     *0:我的收藏 1：自定义分组  2：我的创建
     */
    const GROUPTYPE_CREATE = 2;

    /**
     *最多40自定义分组
     */
    const MAXGROUPNUM = 40;

    public $layout = "lay_user_new";


    /**
     * 我的资源——我的收藏
     * @return string
     */
    public function actionIndex()
    {
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $userId = user()->id;

        $department = app()->request->get('department', self::DEPARTMENT);   //学部
        $subjectId = app()->request->get('subjectId', self::SUBJECT);    //学科
        $matType = app()->request->get('matType', ''); //课件类型
        $groupType = 0;  //我的收藏0 我的创建2
        $groupId = app()->request->get('groupId', '');      //分组id

        $defaultGroupId = '';
        $groupDefault = SeFavoriteMaterialGroup::favoriteGroup($userId, $subjectId, $department); //分组列表（我的收藏）
        if($groupDefault){
            $defaultGroupId = $groupDefault->groupId;
        }
        $collectGroupList = SeFavoriteMaterialGroup::favoriteGroupList($userId, $subjectId, $department); //分组列表（自定义）

        //$groupId为空的时候为默认（第一次进入页面）显示
        if (empty($groupId)) {
            $collectGroupQuery = SeFavoriteMaterialGroup::find()->where(['userId' => $userId, 'subjectId' => $subjectId, 'department' => $department]);
            $collectGroup = $collectGroupQuery->andWhere(['groupType' => $groupType])->select('groupId')->one();    //默认显示我的收藏
            if (empty($collectGroup)) {
                $favoriteMaterialList = [];
                return $this->render('index', [
                    'collectGroupList' => $collectGroupList,
                    'favoriteMaterialList' => $favoriteMaterialList,
                    'pages' => $pages,
                    "department" => $department,
                    "subjectId" => $subjectId,
                    'groupType' => $groupType,
                    'matType' => $matType,
                    'groupId' => $groupId,
                    'defaultGroupId'=>$defaultGroupId
                ]);
            }
            $groupId = $collectGroup->groupId;
        }

        $favoriteMaterial = SeFavoriteMaterial::find()->where(['isDelete' => self::ISDELETE, 'disabled' => self::DESABLED, 'groupId' => $groupId]);

        //我的资源——我的创建
        if (!empty($matType)) {
            $favoriteMaterial->andWhere(['matType' => $matType]);
        }

        $favoriteMaterialList = $favoriteMaterial->orderBy("createTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();
        $pages->totalCount = $favoriteMaterial->count();

        $pages->params = ["department" => $department,
            "subjectId" => $subjectId,
            'matType' => $matType,
            'groupType' => $groupType,
            'groupId' => $groupId,
            'defaultGroupId'=>$defaultGroupId
        ];

        $arr = ['favoriteMaterialList' => $favoriteMaterialList,
            'collectGroupList' => $collectGroupList,
            'groupType' => $groupType,
            'matType' => $matType,
            "department" => $department,
            "subjectId" => $subjectId,
            'pages' => $pages,
            'groupId' => $groupId,
            'defaultGroupId'=>$defaultGroupId
        ];

        if (app()->request->isAjax) {
            return $this->renderPartial('_index_list_favorite', $arr);
        }

        return $this->render('index', $arr);
    }

    /**
     * 我的创建
     * @return string
     */
    public function actionIndexCreate()
    {
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $userId = user()->id;

        $department = app()->request->get('department', self::DEPARTMENT);   //学部
        $subjectId = app()->request->get('subjectId', self::SUBJECT);    //学科
        $matType = app()->request->get('matType', ''); //课件类型
        $groupType = 2;  //我的收藏0 我的创建2
        $groupId = app()->request->get('groupId', '');      //分组id

        $collectGroupList = [];      //分组列表（我的创建）
        $materialQuery = SrMaterial::find()->where(['subjectId' => $subjectId, 'department' => $department, 'creator' => $userId, 'isDelete' => self::ISDELETE]);   //课件列表(我的创建)

        //课件类型
        if (!empty($matType)) {
            $materialQuery->andWhere(['matType' => $matType]);
        }

        $materialList = $materialQuery->orderBy("createTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();
        $pages->totalCount = $materialQuery->count();

        $pages->params = ["department" => $department,
            "subjectId" => $subjectId,
            'matType' => $matType,
            'groupType' => $groupType,
            'groupId' => $groupId
        ];

        $arr = ['favoriteMaterialList' => $materialList,
            'collectGroupList' => $collectGroupList,
            'groupType' => $groupType,
            'matType' => $matType,
            "department" => $department,
            "subjectId" => $subjectId,
            'pages' => $pages,
            'groupId' => $groupId];

        if (app()->request->isAjax) {
            return $this->renderPartial('_index_list_create', $arr);
        }

        return $this->render('index_create', $arr);
    }

    /**
     * 用于加载自定义分组的片段
     */
    public function actionGroupList()
    {
        $department = app()->request->get("department");
        $subjectId = app()->request->get("subjectId");
        $groupId = app()->request->get('groupId');
        $groupType = app()->request->get('groupType');
        $defaultGroupId = app()->request->get('defaultGroupId');

        $userId = user()->id;
        $collectGroupList = SeFavoriteMaterialGroup::favoriteGroupList($userId, $subjectId, $department);

        return $this->renderPartial("_group_list", ["department" => $department, "subjectId" => $subjectId, 'groupType' => $groupType,
            'collectGroupList' => $collectGroupList, 'groupId' => $groupId, 'defaultGroupId'=>$defaultGroupId]);

    }

    /**
     * 移动课件到其他分组
     * @return string
     */
    public function actionMoveGroup()
    {
        $jsonResult = new JsonMessage();
        $collectArray = app()->request->getBodyParam('collectArray');
        $groupId = app()->request->getBodyParam('groupId');
        $result = SeFavoriteMaterial::moveGroup(user()->id, $collectArray, $groupId);
        if ($result == true) {
            $jsonResult->success = true;
            $jsonResult->message = "课件移动成功";
        } else {
            $jsonResult->message = "课件移动失败";
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * 删除课件
     * @return string
     */
    public function actionDeleteMaterial()
    {
        $jsonResult = new JsonMessage();
        $collectArray = app()->request->getBodyParam('collectArray');
        $groupType = app()->request->post('groupType');

        if ($groupType == self::GROUPTYPE_DEFAULT) {
            $result = SeFavoriteMaterial::delFavMaterial($collectArray, user()->id);
        } else {
            $result = SrMaterial::delMaterail($collectArray, user()->id);
        }

        if ($result == false) {
            $jsonResult->message = "课件删除失败";
            return $this->renderJSON($jsonResult);
        }

        $jsonResult->success = true;
        $jsonResult->message = "课件删除成功";

        return $this->renderJSON($jsonResult);
    }

    /**
     * 创建组
     * @return string
     */
    public function actionAddGroup()
    {
        $jsonResult = new JsonMessage();
        $department = app()->request->post('department');
        $subjectId = app()->request->post('subjectId');
        $groupName = app()->request->post('groupName');
        $userId = Yii::$app->getUser()->id;

        $groupNum = SeFavoriteMaterialGroup::getGroupNum($userId);
        if ($groupNum >= 40) {
            $jsonResult->message = "自定义分组限制40个";
            return $this->renderJSON($jsonResult);
        }

        $groupList = SeFavoriteMaterialGroup::favoriteGroupList($userId, $subjectId, $department);
        foreach ($groupList as $group) {
            if ($groupName == $group->groupName) {
                $jsonResult->message = "已经有该名字";
                return $this->renderJSON($jsonResult);
            }
        }

        $result = SeFavoriteMaterialGroup::addGroup($userId, $department, $subjectId, $groupName);

        if ($result) {
            $jsonResult->success = true;
            $jsonResult->message = "创建成功";
        } else {
            $jsonResult->message = "创建失败";
        }

        return $this->renderJSON($jsonResult);
    }

    /**
     * 修改组
     * @return string
     */
    public function actionUpdateGroupName()
    {
        $jsonResult = new JsonMessage();
        $department = app()->request->post('department');
        $subjectId = app()->request->post('subjectId');
        $groupId = app()->request->post('groupId');
        $groupName = app()->request->post('groupName');
        $userId = user()->id;

        $groupInfo = SeFavoriteMaterialGroup::getGroupInfo($groupId, $userId);
        if (empty($groupInfo)) {
            $jsonResult->message = "修改失败";
            return $this->renderJSON($jsonResult);
        }
        if ($groupInfo->groupType != self::GROUPTYPE_FAVORITE) {
            $jsonResult->message = "不能修改系统默认分组";
            return $this->renderJSON($jsonResult);
        }

        $groupList = SeFavoriteMaterialGroup::favoriteGroupList($userId, $subjectId, $department);
        foreach ($groupList as $group) {
            if ($groupName == $group->groupName) {
                $jsonResult->message = "已经有该名字";
                return $this->renderJSON($jsonResult);
            }
        }

        $result = $groupInfo->updateGroupName($groupName);
        if ($result) {
            $jsonResult->success = true;
            $jsonResult->message = "修改成功";
        } else {
            $jsonResult->message = "修改失败";
        }

        return $this->renderJSON($jsonResult);

    }

    /**
     * 删除组
     * @return string
     */
    public function actionDeleteGroup()
    {
        $jsonResult = new JsonMessage();
        $groupId = app()->request->post('groupId');
        $userId = Yii::$app->getUser()->id;

        $groupInfo = SeFavoriteMaterialGroup::getGroupInfo($groupId, $userId);

        if (empty($groupInfo)) {
            $jsonResult->message = "删除失败";
            return $this->renderJSON($jsonResult);
        }

        if ($groupInfo->groupType != self::GROUPTYPE_FAVORITE) {
            $jsonResult->message = "不能删除系统默认分组";
            return $this->renderJSON($jsonResult);
        }

        $result = $groupInfo->deleteGroup();
        if ($result == false) {
            $jsonResult->message = '删除失败';
            return $this->renderJSON($jsonResult);
        }

        $jsonResult->success = true;
        $jsonResult->message = '删除成功';

        return $this->renderJSON($jsonResult);
    }

    /**
     *分享
     */
    public function actionSharedMaterial()
    {
        $jsonResult = new JsonMessage();
        $matId = app()->request->getParam('shareId', '');
        $classId = app()->request->getParam('classId', '');
        $groupId = app()->request->getParam('groupId', '');
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
}