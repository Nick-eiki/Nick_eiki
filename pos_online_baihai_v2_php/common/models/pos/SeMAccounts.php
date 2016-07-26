<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_mAccounts".
 *
 * @property string $accountId
 * @property string $userId
 * @property string $phone
 * @property string $pwd
 * @property string $name
 * @property string $createTime
 * @property string $isDeleteed
 * @property string $faceIconUrl
 * @property integer $id
 * @property string $smsCode
 */
class SeMAccounts extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_mAccounts';
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
     * @return SeMAccountsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeMAccountsQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accountId', 'userId'], 'string', 'max' => 30],
            [['phone', 'smsCode'], 'string', 'max' => 20],
            [['pwd', 'name', 'createTime', 'isDeleteed', 'faceIconUrl'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'accountId' => '帐号id',
            'userId' => '关联的用户id',
            'phone' => '手机号码',
            'pwd' => '帐号密码',
            'name' => '姓和名',
            'createTime' => '帐号创建时间',
            'isDeleteed' => '是否已经删除',
            'faceIconUrl' => '头像相关',
            'id' => 'ID',
            'smsCode' => '短息验证码',
        ];
    }
}
