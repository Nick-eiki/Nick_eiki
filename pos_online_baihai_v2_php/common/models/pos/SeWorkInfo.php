<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_workInfo".
 *
 * @property integer $homeWorkID
 * @property string $workName
 * @property string $provience
 * @property string $city
 * @property string $gradeID
 * @property string $subjectID
 * @property string $version
 * @property string $deadlineTime
 * @property string $knowledegID
 * @property string $brief
 * @property string $url
 * @property string $createTime
 * @property string $creatorID
 * @property string $name
 * @property string $status
 * @property string $isDelete
 * @property string $country
 */
class SeWorkInfo extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_workInfo';
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
     * @return SeWorkInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeWorkInfoQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['homeWorkID'], 'required'],
            [['homeWorkID'], 'integer'],
            [['brief'], 'string'],
            [['workName', 'url'], 'string', 'max' => 200],
            [['provience', 'city', 'gradeID'], 'string', 'max' => 50],
            [['subjectID', 'version', 'deadlineTime', 'createTime', 'creatorID', 'name', 'status', 'country'], 'string', 'max' => 20],
            [['knowledegID'], 'string', 'max' => 300],
            [['isDelete'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'homeWorkID' => '作业id',
            'workName' => '作业名称',
            'provience' => '地区.省',
            'city' => '地区.市',
            'gradeID' => '年级id',
            'subjectID' => '科目id',
            'version' => '教材版本',
            'deadlineTime' => '交作业截止时间',
            'knowledegID' => '涉及知识点',
            'brief' => '作业介绍',
            'url' => '附件文件数量',
            'createTime' => '信息创建时间',
            'creatorID' => '信息创建人',
            'name' => '创建人姓名',
            'status' => '状态',
            'isDelete' => '是否删除',
            'country' => '地区.县',
        ];
    }
}
