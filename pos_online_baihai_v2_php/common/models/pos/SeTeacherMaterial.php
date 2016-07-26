<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_teacherMaterial".
 *
 * @property integer $ID
 * @property string $materialType
 * @property string $teacherID
 * @property string $Name
 * @property string $stuLimit
 * @property string $groupMemberLimit
 * @property string $departmentMemLimit
 * @property string $createTime
 * @property string $isDelete
 * @property string $disabled
 */
class SeTeacherMaterial extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_teacherMaterial';
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
     * @return SeTeacherMaterialQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeTeacherMaterialQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID'], 'required'],
            [['ID'], 'integer'],
            [['materialType', 'teacherID', 'createTime'], 'string', 'max' => 20],
            [['Name'], 'string', 'max' => 300],
            [['stuLimit', 'groupMemberLimit', 'departmentMemLimit', 'isDelete', 'disabled'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => '资料袋/讲义/公文袋id',
            'materialType' => '类型（1资料袋 2公文包）',
            'teacherID' => '教师id',
            'Name' => '名称',
            'stuLimit' => '权限.本班学生可见0：不可见1：可见',
            'groupMemberLimit' => '权限.教研组同事可见0：不可见1：可见',
            'departmentMemLimit' => '权限.部门同事可见权限.部门同事可见',
            'createTime' => '创建时间',
            'isDelete' => '是否已经删除',
            'disabled' => '是否已经禁用 0：未禁用/激活/解禁/审核通过  1：已经禁用',
        ];
    }
}
