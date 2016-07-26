<?php

namespace common\models\pos;

use common\helper\DateTimeHelper;
use common\models\sanhai\SrMaterial;
use frontend\components\helper\DepartAndSubHelper;
use Yii;

/**
 * This is the model class for table "se_class".
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
 * @property string $disabled
 * @property string $logoUrl
 */
class SeClass extends PosActiveRecord
{
	/*
	 *  是否已删除，0表示未删除，1表示已删除
	 */
	const 	ISDELETE = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_class';
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
     * @return SeClassQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeClassQuery(get_called_class());
    }


    /**
     *查询本班的所有科目
     */
    public  function  getClassSubjects(){

        $subjectNumber = [];
        $subjects=DepartAndSubHelper::getTopicSubArray();
        foreach($subjects as $k=>$v){
            if($this->department == $k){
                $subjectNumber[] = $v;
            }
        }
        return $subjectNumber;

    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['classID'], 'required'],
            [['classID'], 'integer'],
            [['className', 'schoolID', 'createTime', 'updateTime', 'classNumber', 'gradeID', 'stuID', 'creatorID', 'department'], 'string', 'max' => 20],
            [['isDelete', 'ownStuList', 'disabled'], 'string', 'max' => 2],
            [['joinYear'], 'string', 'max' => 30],
            [['logoUrl'], 'string', 'max' => 200]
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
            'disabled' => '是否已经禁用 0：未禁用/激活/解禁/审核通过  1：已经禁用',
            'logoUrl' => '班级logourl',
        ];
    }

	/**
	 * 查询所有作业数
	 * @return int|string
	 */
	function getCountHomeworkMember()
	{
		$homeworkMember = SeHomeworkRel::find()->where(['classID'=>$this->classID])->active()->count();
		return $homeworkMember;
	}

	/**
	 * 已截止的作业数
	 * @return int|string
	 */

	function getCountDeadlineTimeHomeworkMember()
	{
        $deadlineTimeHomework = 0;
        $SeHomeworkRel =  SeHomeworkRel::find()->where(['classID'=>$this->classID])->active()->all();
        foreach($SeHomeworkRel as $val){
            $deadlineTime = strtotime(date('Y-m-d 23:59:59', DateTimeHelper::timestampDiv1000($val->deadlineTime)));
            if($deadlineTime < time()){
                $deadlineTimeHomework ++;
            }
        }
    	return $deadlineTimeHomework;
	}

	/**
	 * 查询作业列表~
	 * @return array|SeHomeworkRel[]
	 */
	function getHomeworkRelList()
	{
		$homeworkRel = SeHomeworkRel::find()->where(['classID'=>$this->classID])->active()->all();
		return $homeworkRel;
	}

	/**
	 *  查询所有考试总计数
	 * @return int|string
	 */
	function getExamInfoMem()
	{
		$examInfoMem = SeExaminfo::find()->where(['classID'=>$this->classID])->active()->count();
		return $examInfoMem;
	}

	/**
	 * 查询所有已完成的考试
	 * @return int|string
	 */
	function getFinishExamMem()
	{
		$finishExamMem = SeExaminfo::find()->where(['classID'=>$this->classID])->andWhere(['<','examTime',date("Y-m-d", time())])->active()->count();
		return $finishExamMem;
	}

	/**
	 * 查询所有答疑数
	 * @return int|string
	 */
	function getAnswerAllCount()
	{
		$answerAllCount = SeAnswerQuestion::find()->where(['classID'=>$this->classID])->active()->count();
		return $answerAllCount;
	}

	/**
	 * 查询已解决的答疑数
	 * @return int|string
	 */
	function getResolvedAnswer()
	{
		$answerQuestionQuery = SeAnswerQuestion::find()->where(['classID'=>$this->classID])->andWhere(['isSolved'=>1])->active();
        $resolvedAnswer = $answerQuestionQuery->count();
		return $resolvedAnswer;
	}

	/**
	 * 查询文件总数
	 * @return int|string
	 */
	function getFileCount()
	{
		$fileInfoQuery = SeShareMaterial::find()->where(['classId'=>$this->classID])->active();
		$fileCount = $fileInfoQuery->count();
		return $fileCount;
	}

	/**
	 * 查询阅读数~
	 * @return mixed
	 */

	function getReadCount()
	{
		//查询文件
		$fileMatId = SeShareMaterial::find()->where(['classId'=>$this->classID])->active()->select('matId')->column();
		//阅读数
		$readCount = SrMaterial::find()->where(['id'=>$fileMatId])->sum('readNum');
		return $readCount;
	}

	/**
	 * 查询班级教师
	 * @return array|SeClassMembers[]
	 */
	function getClassTea()
	{
		$classMemberQuery = SeClassMembers::find()->where(['classID'=>$this->classID]);
		$classMemberQuery->andWhere(['>','userID','']);
		$classTea = $classMemberQuery->andWhere(['identity'=>[20402,20401]])->all();
		return $classTea;
	}

	/**
	 * 查询班级学生
	 * @return array|SeClassMembers[]
	 */
	function getClassStu()
	{
		$classMemberQuery = SeClassMembers::find()->where(['classID'=>$this->classID]);
		$classMemberQuery->andWhere(['>','userID','']);
		$classStu = $classMemberQuery->andWhere(['identity'=>20403])->all();
		return $classStu;
	}


	/**
	 * 成员所在班级及班级名
	 * @param $userId
	 * @return array|SeClassMembers[]
	 */
	public static function getClasses($userId)
	{
		return  SeClass::findBySql("select class.className,class.classID
                               from se_class class
                               INNER JOIN se_classMembers classMembers
                               ON class.classID=classMembers.classID

                                WHERE classMembers.userID=:userId
                                AND classMembers.isDelete= 0",[":userId"=>$userId])->all();

	}


    /**
     * 判断是否创建过该班级
     * @param $schoolID
     * @param $departmentId
     * @param $gradeId
     * @param $classNumber
     * @return array|SeClass|null
     */
    public static function isCreateClass($schoolID,$departmentId,$gradeId,$classNumber){

        $classData = SeClass::find()->where(['schoolID'=>$schoolID,'department'=>$departmentId,'gradeID'=>$gradeId,'classNumber'=>$classNumber,'status'=>0,'isDelete' => self::ISDELETE])->one();
        return $classData;
    }


}
