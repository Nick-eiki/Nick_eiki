<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_exam_school".
 *
 * @property integer $schoolExamId
 * @property integer $examId
 * @property integer $schoolId
 * @property string $examName
 * @property integer $examType
 * @property integer $departmentId
 * @property integer $gradeId
 * @property string $schoolYear
 * @property integer $semester
 * @property integer $examMonth
 * @property integer $subjectType
 * @property integer $createrId
 * @property integer $createTime
 * @property integer $updateTime
 * @property integer $inputStatus
 * @property integer $reportStatus
 */
class SeExamSchool extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_exam_school';
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
            [['examId', 'schoolId', 'examType', 'departmentId', 'gradeId', 'semester', 'examMonth', 'subjectType', 'createrId', 'createTime', 'updateTime', 'inputStatus'], 'integer'],
            [['examName'], 'string', 'max' => 50],
            [['schoolYear'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'schoolExamId' => '学校考试id',
            'examId' => '考试id',
            'schoolId' => '学校id',
            'examName' => '考试名称',
            'examType' => '考试类型，21901,期末 21902,期中 21903,月考 21904,一模 21905,二模 21910,随堂测验 21911,一周测验 21912,单元测验 21906,会考',
            'departmentId' => '学段',
            'gradeId' => '年级',
            'schoolYear' => '学年',
            'semester' => '学期',
            'examMonth' => '月份',
            'subjectType' => '文/理',
            'createrId' => '创建人',
            'createTime' => '创建时间',
            'updateTime' => '更新时间',
            'inputStatus' => '成绩录入状态，0未录入，1录入中，2录入完成',
            'reportStatus' => '报表生成状态，0初始状态，1处理中，2报表生成完成',
        ];
    }

    public function getQuestionResult()
    {
        return $this->hasMany(SeQuestionResult::className(), ['rel_aqID' => 'aqID']);
    }


    public function getSeExamClass()
    {
        return $this->hasMany(SeExamClass::className(), ['schoolExamId' => 'schoolExamId']);
    }
    /**
     * 获取考试下的班级
     */
    public function getClasses()
    {

      $classesArray= $this->getSeExamClass()->select('classId')->column();
        //班级和科目
        $classes = SeClass::find()->where(['schoolID' => $this->schoolId, 'gradeID' => $this->gradeId,'classID'=>$classesArray])->all();
        return $classes;
    }

    /**
     * 查询考试的科目
     * @return \yii\db\ActiveQuery
     */
    public function getExamSubject()
    {
        return $this->hasMany(SeExamSubject::className(), ['schoolExamId' => 'schoolExamId']);
    }

    public function getSubjectScoreById($subjectId)
    {

        $model = $this->getExamSubject()->where(['subjectId' => $subjectId])->one();
        if ($model) {
            return isset($model->fullScore) ? $model->fullScore : 0;
        }
        return 0;

    }

    /**
     * 获取所有学科总分
     * @return integer
     */
    public function getTotalScore()
    {

        $fullScore= $this->getExamSubject()->sum('fullScore');

        return  isset($fullScore)?$fullScore:0;

    }


    /**
     * @inheritdoc
     * @return SeExamSchoolQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeExamSchoolQuery(get_called_class());
    }
}
