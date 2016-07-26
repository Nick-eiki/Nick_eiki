<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_teachDairy".
 *
 * @property integer $diaryID
 * @property string $headline
 * @property string $diaryType
 * @property string $lectureID
 * @property string $lectureTitle
 * @property string $teacherID
 * @property string $teachrName
 * @property string $courseID
 * @property string $courseName
 * @property string $diaryInfo
 * @property string $creatorID
 * @property string $createTime
 * @property string $updateTime
 * @property string $limitsOfReading
 * @property string $isDelete
 * @property string $disabled
 */
class SeTeachDairy extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_teachDairy';
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
     * @return SeTeachDairyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeTeachDairyQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['diaryID'], 'required'],
            [['diaryID'], 'integer'],
            [['diaryInfo'], 'string'],
            [['headline'], 'string', 'max' => 300],
            [['diaryType', 'lectureID', 'teacherID', 'teachrName', 'courseID', 'courseName', 'creatorID', 'createTime', 'updateTime'], 'string', 'max' => 20],
            [['lectureTitle', 'limitsOfReading'], 'string', 'max' => 100],
            [['isDelete', 'disabled'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'diaryID' => '日记id',
            'headline' => '标题',
            'diaryType' => '日记类别',
            'lectureID' => '听课计划id。',
            'lectureTitle' => '??????',
            'teacherID' => '主讲人id',
            'teachrName' => '主讲人姓名',
            'courseID' => '课题id',
            'courseName' => '课题名称',
            'diaryInfo' => '日记内容',
            'creatorID' => '日记创建人id',
            'createTime' => '日记创建时间',
            'updateTime' => '最后一次修改时间',
            'limitsOfReading' => '阅读权限0：不公开1：公开2：学校内部可见默认值：0',
            'isDelete' => '是否删除',
            'disabled' => '是否已经禁用 0：未禁用/激活/解禁/审核通过  1：已经禁用',
        ];
    }
}
