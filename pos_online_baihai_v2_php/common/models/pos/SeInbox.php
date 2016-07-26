<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_inbox".
 *
 * @property integer $inboxId
 * @property string $senderId
 * @property string $receiverId
 * @property string $receiverName
 * @property string $senderName
 * @property string $createTime
 * @property string $updateTime
 * @property string $content
 * @property string $messageStatus
 * @property string $senderDel
 * @property string $receiverDel
 * @property string $isDelete
 * @property string $isSystem
 * @property string $disabled
 */
class SeInbox extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_inbox';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_school');
    }
    public function getMessageList()
    {
        // 客户和订单通过 Order.customer_id -> id 关联建立一对多关系
        return $this->hasMany(SeInboxmessage::className(), ['inboxId' => 'inboxId']);
    }
    /**
     * @inheritdoc
     * @return SeInboxQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeInboxQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inboxId'], 'required'],
            [['inboxId'], 'integer'],
            [['senderId', 'receiverId', 'createTime', 'updateTime'], 'string', 'max' => 20],
            [['receiverName', 'senderName'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 500],
            [['messageStatus', 'senderDel', 'receiverDel', 'isDelete', 'isSystem', 'disabled'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inboxId' => '私信ID',
            'senderId' => 'FK	消息发送者ID',
            'receiverId' => 'FK	消息接受者ID',
            'receiverName' => '消息接受者名称',
            'senderName' => '消息发送者名称',
            'createTime' => '消息创建时间',
            'updateTime' => '消息更新时间',
            'content' => '最后一次的消息内容',
            'messageStatus' => '消息状态0: 没有未读回复1：有未读回复',
            'senderDel' => '发送者是否已经删除 0：未删除 1：已经删除',
            'receiverDel' => '接受者是否已经删除 0：未删除 1：已经删除',
            'isDelete' => '是否已经删除 0：未删除 1：已经删除',
            'isSystem' => '是否已经删除 0：未删除 1：已经删除',
            'disabled' => '是否禁用',
        ];
    }
}
