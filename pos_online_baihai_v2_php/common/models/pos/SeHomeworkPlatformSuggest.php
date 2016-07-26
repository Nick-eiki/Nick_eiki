<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_homework_platform_suggest".
 *
 * @property integer $suggestId
 * @property integer $id
 * @property string $comment
 * @property string $createTime
 * @property string $userID
 */
class SeHomeworkPlatformSuggest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_homework_platform_suggest';
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
     */
    public function rules()
    {
        return [
            [['id', 'createTime', 'userID'], 'integer'],
            [['comment'], 'string', 'max' => 600]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'suggestId' => 'Suggest ID',
            'id' => 'ID',
            'comment' => 'Comment',
            'createTime' => 'Create Time',
            'userID' => 'User ID',
        ];
    }

    /**
     * @inheritdoc
     * @return SeHomeworkPlatformSuggestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeHomeworkPlatformSuggestQuery(get_called_class());
    }
}
