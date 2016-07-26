<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "test_TEST".
 *
 * @property integer $classID
 * @property string $className
 * @property string $schoolID
 * @property string $createTime
 * @property string $updateTime
 * @property string $isDelete
 * @property string $ownStuList
 * @property string $joinYear
 * @property string $classNumber
 * @property string $gradeID
 * @property string $stuID
 * @property string $creatorID
 * @property string $department
 */
class TestTEST extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_TEST';
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
     * @return TestTESTQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TestTESTQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['classID'], 'required'],
            [['classID'], 'integer'],
            [['className', 'schoolID', 'classNumber', 'gradeID', 'stuID', 'creatorID', 'department'], 'string', 'max' => 20],
            [['createTime', 'updateTime', 'ownStuList'], 'string', 'max' => 100],
            [['isDelete'], 'string', 'max' => 2],
            [['joinYear'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'classID' => '班级id',
            'className' => '班级别名',
            'schoolID' => '学校id',
            'createTime' => '创建时间',
            'updateTime' => '最后一次修改时间',
            'isDelete' => '是否已删除，0表示未删除，1表示已删除，默认0',
            'ownStuList' => '是否存在学生名单，0表示不存在，1表示存在，默认0',
            'joinYear' => '入学年份',
            'classNumber' => '第几班',
            'gradeID' => '年级id',
            'stuID' => '学生学号',
            'creatorID' => '创建人id',
            'department' => '学部，学段',
        ];
    }
}
