<?php

namespace common\models\pos;

use common\helper\DateTimeHelper;
use Yii;

/**
 * This is the model class for table "se_shareMaterial".
 *
 * @property integer $id
 * @property integer $matId
 * @property integer $shareUserId
 * @property integer $classId
 * @property integer $groupId
 * @property integer $isDelete
 * @property integer $createTime
 */
class SeShareMaterial extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_shareMaterial';
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
            [['matId', 'shareUserId', 'classId', 'groupId', 'isDelete', 'createTime'], 'integer'],
            [['shareUserId'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'matId' => 'Mat ID',
            'shareUserId' => 'Share User ID',
            'classId' => 'Class ID',
            'groupId' => 'Group ID',
            'isDelete' => 'Is Delete',
            'createTime' => '分享时间',
        ];
    }

    /**
     * @inheritdoc
     * @return SeShareMaterialQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeShareMaterialQuery(get_called_class());
    }

    public static  function  getShareMaterialInfo($id)
    {
        return SeShareMaterial::find()->where(['matId'=>$id])->one();
    }

    /**
     * 分享到班级
     * @param $classId
     * @param $userId
     * @param $matId
     * @return bool
     */
    public static function shareToClass($classId, $userId, $matId)
    {
        if(!empty($classId)){
            $arrClassId = explode(',',$classId );
            foreach($arrClassId as $valClassId){
                $classFileModel = SeShareMaterial::find()->where(['classId'=>$valClassId,'matId'=>$matId])->one();
                if($classFileModel){
                    $classFileModel->shareUserId = $userId;
                    $classFileModel->createTime = DateTimeHelper::timestampX1000();
                }else{
                    $classFileModel = new SeShareMaterial();
                    $classFileModel->shareUserId = $userId;
                    $classFileModel ->matId = $matId;
                    $classFileModel ->classId = $valClassId;
                    $classFileModel->createTime = DateTimeHelper::timestampX1000();
                }
                if(!$classFileModel->save(false)){
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 分享的教研组
     * @param $userId
     * @param $matId
     * @param $groupId
     * @return bool
     */
    public static function shareToGroup($groupId, $userId, $matId)
    {
        if(!empty($groupId)){
            $arrGroupId = explode( ',',$groupId );
            foreach($arrGroupId as $valGroupId){
                $groupFileModel = SeShareMaterial::find()->where(['groupId'=>$valGroupId,'matId'=>$matId])->one();
                if($groupFileModel){
                    $groupFileModel->shareUserId = $userId;
                    $groupFileModel->createTime = DateTimeHelper::timestampX1000();
                }else{
                    $groupFileModel = new SeShareMaterial();
                    $groupFileModel->shareUserId = $userId;
                    $groupFileModel ->matId = $matId;
                    $groupFileModel ->groupId = $valGroupId;
                    $groupFileModel->createTime = DateTimeHelper::timestampX1000();
                }
                if(!$groupFileModel->save(false)){
                    return false;
                }
            }
        }
        return true;
    }
}
