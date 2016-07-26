<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_meetingOfParents".
 *
 * @property integer $ID
 * @property string $classID
 * @property string $meetinName
 * @property string $questionOfMeeting
 * @property string $beginTime
 * @property string $finsihTIme
 * @property string $numberOfparticipants
 * @property string $Status
 * @property string $creatorID
 * @property string $createTime
 * @property string $isDelete
 */
class SeMeetingOfParents extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_meetingOfParents';
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
     * @return SeMeetingOfParentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeMeetingOfParentsQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID'], 'required'],
            [['ID'], 'integer'],
            [['classID', 'beginTime', 'finsihTIme', 'Status', 'creatorID', 'createTime'], 'string', 'max' => 20],
            [['meetinName'], 'string', 'max' => 200],
            [['questionOfMeeting'], 'string', 'max' => 500],
            [['numberOfparticipants'], 'string', 'max' => 50],
            [['isDelete'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'id',
            'classID' => '班级id',
            'meetinName' => '会议名称',
            'questionOfMeeting' => '会议议题',
            'beginTime' => '会议开始时间',
            'finsihTIme' => '会议结束时间',
            'numberOfparticipants' => '参会人数',
            'Status' => '状态',
            'creatorID' => '信息创建人',
            'createTime' => '信息创建时间',
            'isDelete' => '是否删除',
        ];
    }
}
