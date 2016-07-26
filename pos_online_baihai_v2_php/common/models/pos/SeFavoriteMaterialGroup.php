<?php

namespace common\models\pos;

use common\helper\DateTimeHelper;
use common\models\sanhai\SrMaterial;
use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "se_favoriteMaterial_group".
 *
 * @property integer $groupId
 * @property integer $userId
 * @property integer $subjectId
 * @property integer $department
 * @property string $groupName
 * @property integer $groupType
 * @property integer $updateTime
 * @property integer $createTime
 */
class SeFavoriteMaterialGroup extends PosActiveRecord
{
    const GROUPTYPE = 1;      //0:我的收藏 1：自定义分组  2：我的创建

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_favoriteMaterial_group';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_school');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'subjectId', 'department', 'groupName', 'updateTime', 'createTime'], 'required'],
            [['userId', 'subjectId', 'department', 'groupType', 'updateTime', 'createTime'], 'integer'],
            [['groupName'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'groupId' => '收藏资料分组id',
            'userId' => '用户id',
            'subjectId' => '科目id',
            'department' => '学段id',
            'groupName' => '分组名',
            'groupType' => '分组类型：0我的收藏1自定义分组',
            'updateTime' => '更新时间',
            'createTime' => '创建时间',
        ];
    }

    /**
     * @inheritdoc
     * @return SeFavoriteMaterialGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeFavoriteMaterialGroupQuery(get_called_class());
    }

    /**
     * 分组列表（自定义）
     * @param $userId
     * @param $subjectId
     * @param $department
     * @return array|\common\models\pos\SeFavoriteMaterialGroup[]
     */
    public static function favoriteGroupList($userId, $subjectId, $department)
    {
        $collectGroupQuery = SeFavoriteMaterialGroup::find()->where(['userId' => $userId, 'subjectId' => $subjectId, 'department' => $department]);
        $collectGroupList = $collectGroupQuery->andWhere(['groupType'=>1])->orderBy('groupType')->orderBy('createTime')->all();
        return $collectGroupList;
    }

    /**
     * 分组列表（我的收藏）
     * @param $userId
     * @param $subjectId
     * @param $department
     * @return array|\common\models\pos\SeFavoriteMaterialGroup[]
     */
    public static function favoriteGroup($userId, $subjectId, $department)
    {
        $collectGroupQuery = SeFavoriteMaterialGroup::find()->where(['userId' => $userId, 'subjectId' => $subjectId, 'department' => $department]);
        $collectGroup = $collectGroupQuery->andWhere(['groupType'=>0])->orderBy('groupType')->orderBy('createTime')->one();
        return $collectGroup;
    }

    /**
     * 统计当前用户的自定义分组的数量
     * @param $userId
     * @return int|string
     */
    public static function getGroupNum($userId)
    {
        return SeFavoriteMaterialGroup::find()->where(['userId' => $userId, 'groupType' => 1])->count();
    }

    /**
     * 获取组菜单信息
     * @param $groupId
     * @param $userId
     * @return array|SeFavoriteMaterialGroup|null
     */
    public static function getGroupInfo($groupId, $userId)
    {
        return SeFavoriteMaterialGroup::find()->where(['groupId' => $groupId, 'userId' => $userId])->one();
    }

    /**
     * 创建组
     * @param $userId
     * @param $department
     * @param $subjectId
     * @param $groupName
     * @return bool
     */
    public static function addGroup($userId, $department, $subjectId, $groupName)
    {
        $groupModel = new SeFavoriteMaterialGroup();
        $groupModel->department = $department;
        $groupModel->subjectId = $subjectId;
        $groupModel->groupName = $groupName;
        $groupModel->userId = $userId;
        $groupModel->groupType = self::GROUPTYPE;
        $groupModel->createTime = DateTimeHelper::timestampX1000();

        if (!$groupModel->save(false)) {
            return false;
        }
        return true;
    }

    /**
     * 修改组名称
     * @param $groupInfo
     * @param $groupName
     * @return bool
     */
    public function updateGroupName($groupName)
    {
        $this->groupName = $groupName;
        $this->updateTime = DateTimeHelper::timestampX1000();
        if (!$this->save(false)) {
            return false;
        }
        return true;
    }

    /**
     * 删除分组
     * @param $userId
     * @param $groupId
     * @return bool
     * @throws \Exception
     */
    public function  deleteGroup()
    {
        $transaction = Yii::$app->db_school->beginTransaction();
        try {
            $favoriteMaterialArray = SeFavoriteMaterial::find()->where(['groupId' => $this->groupId, 'userId' => $this->userId])->all();
            foreach ($favoriteMaterialArray as $value) {

                //文件收藏总数减1
                $srMaterialModel = SrMaterial::find()->where(['id' => $value->favoriteId])->one();
                if ($srMaterialModel->favoriteNum <= 0) {
                    $srMaterialModel->favoriteNum = 0;
                } else {
                    $srMaterialModel->favoriteNum = $srMaterialModel->favoriteNum - 1;
                }
                $srMaterialModel->updateTime = DateTimeHelper::timestampX1000();
                $srMaterialModel->save(false);  //文件收藏总数减1

                $value->delete();   //删除当前组下的所有收藏的文件
            }

            $this->delete();    //删除组
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }

        return true;
    }


}
