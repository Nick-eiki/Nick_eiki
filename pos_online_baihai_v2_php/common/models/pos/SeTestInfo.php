<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_testInfo".
 *
 * @property integer $id
 * @property string $testName
 * @property string $testTime
 * @property string $provience
 * @property string $city
 * @property string $country
 * @property string $gradeId
 * @property string $subjectId
 * @property string $version
 * @property string $knowledgeId
 * @property string $paperId
 * @property string $paperDescribe
 * @property string $paperName
 * @property string $userId
 * @property string $classId
 * @property string $isDelete
 * @property string $kid
 * @property string $learningPlan
 * @property string $summaryTeacherID
 * @property string $isHaveCrossCheck
 */
class SeTestInfo extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_testInfo';
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
     * @return SeTestInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeTestInfoQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['testName', 'knowledgeId', 'kid'], 'string', 'max' => 300],
            [['testTime', 'userId', 'classId'], 'string', 'max' => 20],
            [['provience', 'city', 'country'], 'string', 'max' => 50],
            [['gradeId', 'subjectId', 'version', 'paperId'], 'string', 'max' => 30],
            [['paperDescribe', 'learningPlan'], 'string', 'max' => 500],
            [['paperName'], 'string', 'max' => 200],
            [['isDelete', 'isHaveCrossCheck'], 'string', 'max' => 2],
            [['summaryTeacherID'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '测验id',
            'testName' => '测验名称',
            'testTime' => '测验时间',
            'provience' => '省',
            'city' => '市',
            'country' => '区县',
            'gradeId' => '年级id',
            'subjectId' => '科目id',
            'version' => '版本',
            'knowledgeId' => '知识点',
            'paperId' => '试卷id',
            'paperDescribe' => '试卷简介',
            'paperName' => '试卷名称',
            'userId' => '创建人id',
            'classId' => '班级id',
            'isDelete' => '是否删除0：否1：是默认0',
            'kid' => '总评知识难点',
            'learningPlan' => '学习计划',
            'summaryTeacherID' => '填写总评教师',
            'isHaveCrossCheck' => '是否进行学生交换批改',
        ];
    }
}
