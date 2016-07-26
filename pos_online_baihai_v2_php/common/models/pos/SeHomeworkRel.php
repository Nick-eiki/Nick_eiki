<?php

namespace common\models\pos;

use frontend\components\WebDataKey;
use Yii;

/**
 * This is the model class for table "se_homework_rel".
 *
 * @property string $id
 * @property string $createTime
 * @property integer $isDelete
 * @property string $creator
 * @property string $deadlineTime
 * @property string $classID
 * @property string $homeworkId
 * @property string $memberTotal
 * @property string $isSendMsgStudent
 * @property string $audioUrl
 */
class SeHomeworkRel extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_homework_rel';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_school');
    }


	/**
	 * @return \yii\db\ActiveQuery
	 * 根据homeworkId 查询se_homework_teacher表信息
	 */
	public function getHomeWorkTeacher()
	{
		return $this->hasOne(SeHomeworkTeacher::className(), ['id' => 'homeworkId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 * 查询答案数
	 */

	public function getHomeworkAnswerInfo()
	{
		return $this->hasOne(SeHomeworkAnswerInfo::className(), ['relId' => 'id']);
	}

    /**
     * 获取已答学生数
     * @return int|mixed|string
     */
    public function  homeworkAnswerInfoCountCache(){
        $cache = Yii::$app->cache;
        $key = WebDataKey::HOMEWORK_ANSWER_INFO_COUNT_KEY . $this->id;
        $data = $cache->get($key);
        if($data===false){
            $data=SeHomeworkAnswerInfo::find()->where(['relId'=>$this->id, 'isUploadAnswer'=>'1'])->count();
            $cache->set($key,$data,300);
        }
        return $data;
    }

    /**
     * 获取已批改学生数
     * @return int|mixed|string
     */
    public function isCheckedStudentCountCache(){
        $cache = Yii::$app->cache;
        $key = WebDataKey::IS_CHECKED_STUDENT_COUNT_KEY . $this->id;
        $data = $cache->get($key);
        if($data===false){
            $data=SeHomeworkAnswerInfo::find()->where(['relId'=>$this->id, 'isCheck'=>'1', 'isUploadAnswer'=>'1'])->count();
            $cache->set($key,$data,300);
        }
        return $data;
    }

    /**
     * @param $homeworkId
     * @return array|SeHomeworkRel[]
     * 通过作业id获取到relId
     */
    public static function getRelData($homeworkId){
        return SeHomeworkRel::find()->where(['homeworkId' => $homeworkId])->all();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createTime', 'isDelete', 'creator', 'deadlineTime', 'classID', 'homeworkId'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'createTime' => '上传时间',
            'isDelete' => '是否删除0：否1：是默认0',
            'creator' => '创建人',
            'deadlineTime' => '交作业截至时间',
            'classID' => '班级id',
            'homeworkId' => ' 作业表，关联教师作业库',
            'memberTotal'=>'当前班级学生数',
            'isSendMsgStudent'=>'0:未催作业 1：已催作业',
            'audioUrl' => '语音地址'
        ];
    }

    /**
     * @inheritdoc
     * @return SeHomeworkRelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeHomeworkRelQuery(get_called_class());
    }

    /**
     * 查询单条班级作业
     * @param $classId
     * @return array|SeHomeworkRel|null
     */
    public function selectOneClassHomework($classId)
    {
        return self::find()->where(['classID' => $classId])->active()->orderBy('createTime desc')->one();
    }
}
