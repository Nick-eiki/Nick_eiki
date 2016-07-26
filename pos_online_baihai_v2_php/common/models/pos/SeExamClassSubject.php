<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_exam_classSubject".
 *
 * @property integer $examSubTeaId
 * @property integer $examSubId
 * @property integer $classExamId
 * @property integer $teacherId
 * @property integer $createTime
 * @property integer $updateTime
 * @property integer $schoolExamId
 */
class SeExamClassSubject extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_exam_classSubject';
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
            [['examSubId', 'classExamId', 'teacherId', 'createTime','schoolExamId'], 'required'],
            [['examSubId', 'classExamId', 'teacherId', 'createTime', 'updateTime','schoolExamId'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'examSubTeaId' => '考试科目教师表主键',
            'examSubId' => '考试科目主键id',
            'classExamId' => '班级考试id',
            'teacherId' => '教师id',
            'createTime' => '创建时间',
            'updateTime' => '更新时间',
            'schoolExamId' => '学校考试id',
        ];
    }
    public function getClassExam(){
        return $this->hasOne(SeExamClass::className(),['classExamId'=>'classExamId']);
    }

    /**
     * @inheritdoc
     * @return SeExamClassSubjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeExamClassSubjectQuery(get_called_class());
    }
}
