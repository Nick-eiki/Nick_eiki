<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_exam_subject".
 *
 * @property integer $examSubId
 * @property integer $schoolExamId
 * @property integer $subjectId
 * @property integer $fullScore
 * @property integer $borderlineOne
 * @property integer $borderlineTwo
 * @property integer $borderlineThree
 * @property integer $createTime
 * @property integer $updateTime
 */
class SeExamSubject extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_exam_subject';
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
            [['schoolExamId', 'subjectId',  'borderlineTwo', 'borderlineThree', 'createTime', 'updateTime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'examSubId' => '考试科目主键id',
            'schoolExamId' => '学校考试id',
            'subjectId' => '科目id',
            'fullScore' => '满分',
            'borderlineOne' => '分数线1',
            'borderlineTwo' => '分数线2',
            'borderlineThree' => '分数线3',
            'createTime' => '创建时间',
            'updateTime' => '更新时间',
        ];
    }

    /**
     * @inheritdoc
     * @return SeExamSubjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeExamSubjectQuery(get_called_class());
    }
}
