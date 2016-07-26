<?php

namespace common\models\sanhai;

use Yii;

/**
 * This is the model class for table "se_dateDictionary".
 *
 * @property integer $ID
 * @property string $firstCode
 * @property string $firstCodeValue
 * @property string $secondCode
 * @property string $secondCodeValue
 * @property string $status
 * @property string $reserve1
 * @property string $reserve2
 * @property string $reserve3
 * @property string $scorea
 * @property string $scoreb
 * @property string $scorec
 */
class SeDateDictionary extends SanhaiActiveRecord
{
    public $reserveTwo;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_dateDictionary';
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
            [['firstCode', 'firstCodeValue', 'secondCode', 'secondCodeValue', 'status', 'scorea', 'scoreb', 'scorec'], 'string', 'max' => 20],
            [['reserve1', 'reserve2', 'reserve3'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'firstCode' => '一级代码',
            'firstCodeValue' => '一级代码',
            'secondCode' => '二级代码',
            'secondCodeValue' => '二级代码',
            'status' => '是否启用 1：启用（默认）0未启用',
            'reserve1' => '预留字段1',
            'reserve2' => '预留字段2',
            'reserve3' => '预留字段3',
            'scorea' => '科目小学分数',
            'scoreb' => '科目初中分数',
            'scorec' => '科目高中分数',
        ];
    }

    /**
     * @inheritdoc
     * @return SeDateDictionaryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeDateDictionaryQuery(get_called_class());
    }
}
