<?php

namespace common\models\sanhai;

use Yii;

/**
 * This is the model class for table "sr_knowledgepoint".
 *
 * @property integer $kid
 * @property string $pid
 * @property string $kpointname
 * @property string $subject
 * @property string $grade
 * @property string $isDelete
 * @property string $remark
 */
class SrKnowledgepoint extends SanhaiActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sr_knowledgepoint';
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
            [['kid'], 'required'],
            [['kid'], 'integer'],
            [['pid', 'subject', 'grade'], 'string', 'max' => 20],
            [['kpointname'], 'string', 'max' => 300],
            [['isDelete'], 'string', 'max' => 2],
            [['remark'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kid' => 'Kid',
            'pid' => 'Pid',
            'kpointname' => 'Kpointname',
            'subject' => '科目id',
            'grade' => '学部，学段',
            'isDelete' => 'Is Delete',
            'remark' => 'Remark',
        ];
    }

    /**
     * @inheritdoc
     * @return SrKnowledgepointQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SrKnowledgepointQuery(get_called_class());
    }
}
