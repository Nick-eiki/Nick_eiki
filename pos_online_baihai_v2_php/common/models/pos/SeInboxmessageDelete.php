<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_inboxmessage_delete".
 *
 * @property integer $inboxID
 * @property string $receiverID
 * @property string $receiverName
 * @property string $senderID
 * @property string $sendeName
 * @property string $content
 * @property string $sendTime
 * @property string $messageStuatus
 * @property string $isDelete
 * @property string $disabled
 */
class SeInboxmessageDelete extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_inboxmessage_delete';
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
     * @return SeInboxmessageDeleteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeInboxmessageDeleteQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inboxID'], 'required'],
            [['inboxID'], 'integer'],
            [['content'], 'string'],
            [['receiverID', 'senderID', 'sendTime'], 'string', 'max' => 20],
            [['receiverName', 'sendeName', 'messageStuatus'], 'string', 'max' => 50],
            [['isDelete', 'disabled'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inboxID' => '私信ID',
            'receiverID' => '收信人id',
            'receiverName' => '收信人姓名',
            'senderID' => '发信人id',
            'sendeName' => '发信人id',
            'content' => '私信内容',
            'sendTime' => '私信发送时间',
            'messageStuatus' => '回复条数',
            'isDelete' => '是否已经删除',
            'disabled' => '是否已经禁用 0：未禁用/激活/解禁/审核通过  1：已经禁用',
        ];
    }
}
