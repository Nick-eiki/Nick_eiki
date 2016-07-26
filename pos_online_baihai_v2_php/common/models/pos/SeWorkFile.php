<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_workFile".
 *
 * @property integer $urlID
 * @property string $homeWorkID
 * @property string $type
 * @property string $url
 * @property string $isDelete
 */
class SeWorkFile extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_workFile';
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
     * @return SeWorkFileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeWorkFileQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['urlID'], 'integer'],
            [['homeWorkID', 'type'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 200],
            [['isDelete'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'urlID' => '附件id',
            'homeWorkID' => '作业id',
            'type' => '附件类型',
            'url' => '附件url',
            'isDelete' => '是否已删除',
        ];
    }
}
