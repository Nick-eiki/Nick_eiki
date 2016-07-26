<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_homework_platform_videos".
 *
 * @property integer $id
 * @property string $questionId
 */
class SeHomeworkPlatformVideos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_homework_platform_videos';
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
            [['id', 'videoId'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'videoId' => 'video ID',
        ];
    }

    /**
     * @inheritdoc
     * @return SeHomeworkPlatformVideosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeHomeworkPlatformVideosQuery(get_called_class());
    }
}
