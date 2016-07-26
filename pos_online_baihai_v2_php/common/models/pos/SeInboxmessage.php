<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_inboxmessage".
 *
 * @property string $inboxId
 * @property integer $inboxMessageID
 * @property string $senderId
 * @property string $receiverId
 * @property string $receiverName
 * @property string $senderName
 * @property string $content
 * @property string $createTime
 * @property string $updateTime
 * @property string $messageStatus
 * @property string $senderDel
 * @property string $receiverDel
 * @property string $isDelete
 * @property string $isSystem
 * @property string $receiverRead
 */
class SeInboxmessage extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_inboxmessage';
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
     * @return SeInboxmessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeInboxmessageQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inboxMessageID'], 'required'],
            [['inboxMessageID'], 'integer'],
            [['inboxId', 'senderId', 'receiverId', 'createTime', 'updateTime'], 'string', 'max' => 20],
            [['receiverName', 'senderName'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 500],
            [['messageStatus', 'senderDel', 'receiverDel', 'isDelete', 'isSystem', 'receiverRead'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inboxId' => 'FK私信ID',
            'inboxMessageID' => 'PK	私信消息ID',
            'senderId' => 'FK	消息发送者ID',
            'receiverId' => 'FK	消息接受者ID',
            'receiverName' => '消息接受者名称',
            'senderName' => '消息发送者名称',
            'content' => '消息内容',
            'createTime' => '消息创建时间',
            'updateTime' => '消息更新时间',
            'messageStatus' => '消息状态0：未读，1：已读',
            'senderDel' => '发送者是否已经删除0：未删除，1：已经删除',
            'receiverDel' => '接收者是否已经删除0：未删除，1：已经删除',
            'isDelete' => '是否已删除0：未删除，1：已经删除',
            'isSystem' => '是否由系统发送 0：否 ，1：是',
            'receiverRead' => '接受人是否以读 0 未读 1已读',
        ];
    }
}
