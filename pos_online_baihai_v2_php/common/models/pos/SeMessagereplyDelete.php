<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_messagereply_delete".
 *
 * @property integer $returnID
 * @property string $inboxID
 * @property string $receiverID
 * @property string $receiverName
 * @property string $returnTime
 * @property string $isDelete
 * @property string $disabled
 */
class SeMessagereplyDelete extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_messagereply_delete';
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
     * @return SeMessagereplyDeleteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeMessagereplyDeleteQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['returnID'], 'required'],
            [['returnID'], 'integer'],
            [['inboxID', 'receiverID', 'returnTime'], 'string', 'max' => 20],
            [['receiverName'], 'string', 'max' => 50],
            [['isDelete', 'disabled'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'returnID' => '回复ID',
            'inboxID' => '私信id',
            'receiverID' => '回复人id',
            'receiverName' => '回复人姓名',
            'returnTime' => '回复时间',
            'isDelete' => '是否已经删除',
            'disabled' => '是否已经禁用 0：未禁用/激活/解禁/审核通过  1：已经禁用',
        ];
    }
}
