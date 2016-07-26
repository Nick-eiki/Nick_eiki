<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_exam_reportStudentStructure".
 *
 * @property integer $reportStructureId
 * @property integer $schoolExamId
 * @property integer $classId
 * @property integer $AplusNum
 * @property integer $ANum
 * @property integer $BplusNum
 * @property integer $BNum
 * @property integer $CplusNum
 * @property integer $CNum
 * @property integer $createTime
 * @property integer $updateTime
 */
class SeExamReportStudentStructure extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_exam_reportStudentStructure';
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
            [['schoolExamId', 'createTime'], 'required'],
            [['schoolExamId', 'classId', 'AplusNum', 'ANum', 'BplusNum', 'BNum', 'CplusNum', 'CNum', 'createTime', 'updateTime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reportStructureId' => '记录id',
            'schoolExamId' => '考试id（学校）',
            'classId' => '班级id',
            'AplusNum' => 'A+Num',
            'ANum' => 'ANum',
            'BplusNum' => 'B+Num',
            'BNum' => 'BNum',
            'CplusNum' => 'C+Num',
            'CNum' => 'CNum',
            'createTime' => '创建时间',
            'updateTime' => '更新时间',
        ];
    }

    /**
     * @inheritdoc
     * @return SeExamReportStudentStructureQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeExamReportStudentStructureQuery(get_called_class());
    }
}
