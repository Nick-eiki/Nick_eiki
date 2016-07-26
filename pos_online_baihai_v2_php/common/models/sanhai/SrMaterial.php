<?php

namespace common\models\sanhai;

use common\models\pos\SeFavoriteFolder;
use common\models\pos\SeFavoriteMaterial;
use common\models\pos\SeShareMaterial;
use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "sr_material".
 *
 * @property integer $id
 * @property string $name
 * @property string $matType
 * @property string $provience
 * @property string $city
 * @property string $country
 * @property string $gradeid
 * @property string $subjectid
 * @property string $versionid
 * @property string $kid
 * @property string $chapterId
 * @property string $contentType
 * @property string $school
 * @property string $tags
 * @property string $creator
 * @property string $createTime
 * @property string $updateTime
 * @property string $matDescribe
 * @property string $isDelete
 * @property string $url
 * @property string $disabled
 * @property string $readNum
 * @property string $downNum
 * @property string $chapKids
 * @property string $groupId
 * @property string $access
 * @property integer $favoriteNum
 * @property string $department
 */
class SrMaterial extends SanhaiActiveRecord
{
    const ISDELETE = 0;      //是否删除
    const DESABLED = 0;      //是否禁用
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sr_material';
    }


    public function getShareMaterial()
    {
        return $this->hasMany(SeShareMaterial::className(), ['matId' => 'id']);
    }

    //收藏的总数
    public function getCollectCount(){
        return SeFavoriteFolder::find()->where(['favoriteId'=>$this->id])->count();
    }

    //创建的总数
    public static function getCreateFileCount($userId){
        return SrMaterial::find()->where(['creator'=>$userId,'isDelete'=>0])->count();
    }

    //当前用户是否收藏过
    public function isCollect(){
        $flag = false;
        $favoriteModel = SeFavoriteFolder::find()->where(['favoriteId'=>$this->id ,'creatorID'=>user()->id,'isDelete'=>0])->one();
        if($favoriteModel){
            $flag= true;
        }
        return $flag;
    }

    //同步文件收藏数
    public function setFavoriteNum($favoriteNum){
        $this->favoriteNum = $favoriteNum;
        $this->save(false);
    }

    /**
     * 判断当前的备课文件是否是最近一周内创建的
     * @return bool
     */
    public function isNewFile(){
        $week_ago = strtotime('-1 week')*1000;
        if($this->createTime > $week_ago || $this->updateTime > $week_ago){
            return true;
        }
        return false;
    }


    /**
     * 根据资料id查询资料详情
     * @param $id
     * @return array|SrMaterial|null
     */
    public static function getMaterialInfo($id){

        $result = SrMaterial::find()->where(['id'=>$id])->one();

        return $result;
    }

    /**
     * 根据资料id列表查询资料详情列表
     * @param $id
     * @return array|SrMaterial|null
     */
    public static function getMaterialListInfo($idArr,$collectIdArr){

        $srMaterialList = SrMaterial::find()->where(['in','id',$idArr])->all();
        $result = [];
        foreach($srMaterialList as $srMaterial){
            $result[$collectIdArr[$srMaterial->id]] = $srMaterial;
        }

        return $result;
    }

    /**
     * 删除我创建的课件
     * @param $collectArray
     * @param $userId
     * @return bool
     */
    public static function delMaterail($collectArray, $userId){
        $transaction = Yii::$app->db_school->beginTransaction();
        try {
            if (!is_array($collectArray)) {
                return false;
            }
            foreach ($collectArray as $value) {
                //删除收藏表中该课件
                SeFavoriteMaterial::deleteAll(['favoriteId' => $value]);

                //删除分享表中的课件
                SeShareMaterial::updateAll(['isDelete'=>1],['matId'=>$value]);

                //删除资料库中课件
                $srMaterialModel = SrMaterial::find()->where(['id' => $value,'creator'=>$userId])->one();
                $srMaterialModel->isDelete = 1;
                $srMaterialModel->save(false);
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
        return true;
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_sanku');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['matDescribe'], 'string'],
            [['name', 'kid', 'chapterId', 'tags'], 'string', 'max' => 200],
            [['matType', 'provience', 'city', 'country', 'gradeid', 'subjectid', 'versionid', 'creator', 'createTime', 'updateTime', 'isDelete', 'readNum'], 'string', 'max' => 20],
            [['contentType'], 'string', 'max' => 50],
            [['school'], 'string', 'max' => 30],
            [['url', 'chapKids'], 'string', 'max' => 500],
            [['disabled'], 'string', 'max' => 10],
            [['downNum', 'groupId'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'name' => '名称',
            'matType' => '资料类型(1教案,2讲义 ,4 资料,5 ppt,6 素材)',
            'provience' => '省',
            'city' => '市',
            'country' => '区县',
            'gradeid' => '年级编码',
            'subjectid' => '科目编码',
            'versionid' => '版本编码',
            'kid' => '知识点之间使用逗号分隔',
            'chapterId' => '章节id,多个用逗号隔开',
            'contentType' => '资料内容类型(1知识点 2课本章节)',
            'school' => '名校',
            'tags' => '自定义标签',
            'creator' => '录入人员',
            'createTime' => '录入时间',
            'updateTime' => '最后一次修改时间',
            'matDescribe' => '描述',
            'isDelete' => '删除状态',
            'url' => '附件地址',
            'disabled' => '是否已经禁用 0：未禁用/激活/解禁/审核通过  1：已经禁用',
            'readNum' => '阅读次数',
            'downNum' => '下载次数',
            'chapKids' => '章节 知识点id',
            'groupId' => 'Group ID',
            'access' => '共享私有 （1共享 2私有）',
            'isplatform'=>'是否是平台资料（0：否，1：是）'
        ];
    }

    public function getSharedNum()
    {
        return $this->hasMany(SeShareMaterial::className(), ["matId" => "id"]);
    }

    public function getCollectNum()
    {
        return $this->hasMany(SeFavoriteFolder::className(), ["favoriteId" => "id"]);
    }

    /**
     * @inheritdoc
     * @return SrMaterialQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SrMaterialQuery(get_called_class());
    }

    /**
     * 我的创建课件的份数统计
     * @param $userId
     * @param $department
     * @param $subjectId
     * @return int|string
     */
    public static function getCreateMaterialCount($userId,$department,$subjectId)
    {
        if (intval($userId) <= 0 ) {
            return null;
        }
        $data = SrMaterial::find()->where([ 'creator' => $userId,'isDelete' => self::ISDELETE,'disabled' => self::DESABLED,'department' => $department, 'subjectid' => $subjectId])->count();
        return $data;
    }
}
