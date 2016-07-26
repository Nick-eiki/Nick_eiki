<?php

namespace common\models\pos;

use common\helper\DateTimeHelper;
use common\services\KeyWordsService;
use frontend\components\WebDataCache;
use Yii;

/**
 * This is the model class for table "se_answerQuestion".
 *
 * @property integer $aqID
 * @property string $creatorID
 * @property string $aqName
 * @property string $aqDetail
 * @property string $createTime
 * @property string $subjectID
 * @property string $classID
 * @property string $sameQueNumber
 * @property string $sendToWorld
 * @property string $isDelete
 * @property string $imgUri
 * @property string $schoolID
 * @property string $isSolved
 * @property string $answerResultNum
 * @property string $country
 * @property string $creatorName
 */
class SeAnswerQuestion extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_answerQuestion';
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
     * @return SeAnswerQuestionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeAnswerQuestionQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aqID'], 'integer'],
            [['aqDetail'], 'string'],
            [['aqName'], 'string', 'max' => 500],
            [['imgUri'], 'string', 'max' => 400]
        ];
    }

    /**
     * @param $studentId
     * @return bool
     */
    public function ifSelfAsked($studentId){
        $ifSelfAsked=SeAnswerQuestion::find()->where(['aqID'=>$this->aqID])->andWhere(['creatorID'=>$studentId])->exists();
        return $ifSelfAsked;
    }

    /**
     * @param $studentId
     * @return mixed
     */
    public function ifAccepted($studentId){
            return SeQuestionResult::find()->where(['rel_aqID'=>$this->aqID])->max('isUse');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aqID' => '问题ID',
            'creatorID' => '创建人ID',
            'aqName' => '问题名称',
            'aqDetail' => '问题详情',
            'createTime' => '创建时间',
            'subjectID' => '科目ID',
            'classID' => '班级ID',
            'sameQueNumber' => '同问数',
            'sendToWorld' => '抛向宇宙',
            'isDelete' => '是否删除，0已产生，1未删除',
            'imgUri' => 'Img Uri',
	        'schoolID' => '学校ID',
	        'creatorName' => '创建人名称',
            'isSolved' => '解决状态',
            'country' => '地区'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionResult()
	{
		return $this->hasMany(SeQuestionResult::className(),['rel_aqID'=>'aqID']);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSameQuestionResult()
    {
        return $this->hasMany(SeSameQuestion::className(),['aqID'=>'aqID']);
    }



    /**
     *
     * 当前用户提问问题的总数
     * @param $userId
     * @return $this
     */
    public static  function getUserAskQuestion($userId){
        return  self::find()->active()->where(['creatorID' => $userId])->count();
    }



    /**
     * 同步答疑回答数
     * @param $AnswerNum
     */
    public function setAnswerResultNum($answerResultNum){
        $this->answerResultNum = $answerResultNum;
        $this->save(false);
    }

    /**
     * 查询当天用户提问数
     * @param $startTime
     * @param $endTime
     * @param $userId
     * @return int|string
     */
    public function checkAnswerNum($userId)
    {
        $startTime = strtotime(date("Y-m-d 00:00:00", time())) * 1000;
        $endTime = strtotime(date("Y-m-d 23:59:59", time())) * 1000;
        return self::find()->andWhere(['between', 'createTime', $startTime, $endTime])->andWhere(['creatorID' => $userId])->count();
    }


    /**
     * 新建答疑
     * @param $schoolInfo
     * @param $classID
     * @param $schoolID
     * @param $dataBag
     * @param $subjectID
     * @param $moreIdea
     * @param $picurls
     * @param $userid
     * @return bool
     */
    public function addAnswer($schoolInfo,$classID,$schoolID,$dataBag,$subjectID,$moreIdea,$picurls,$userid){
        if (!empty($schoolInfo)) {
            $country = $schoolInfo->country;
            $this->country = $country;
        }
        $this->classID = $classID;
        $this->schoolID = $schoolID;
        $this->aqDetail = KeyWordsService::ReplaceKeyWord($dataBag->detail);
        $this->aqName = KeyWordsService::ReplaceKeyWord($dataBag->title);
        $this->subjectID = $subjectID;
        $this->sendToWorld = $moreIdea;
        $this->imgUri = $picurls;
        $this->creatorName = WebDataCache::getTrueName($userid);
        $this->createTime = DateTimeHelper::timestampX1000();
        $this->creatorID = $userid;
        if(self::save(false)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 查询单条答疑信息
     * @param $aqId
     * @return array|SeAnswerQuestion|null
     */
    public function selectAnswerOne($aqId)
    {
        $questionDetail = self::find()->active()->where(['aqID'=>$aqId])->one();
        return $questionDetail;
    }

    /**
     * 设置最佳答案 修改答疑 为解决状态
     * @param $aqId
     * @return bool
     */
    public function updateAnswerQuestionsSolve($aqId)
    {
        $answerQuestionsSolve = self::updateAll(['isSolved'=>'1'],'aqID=:aqId',[":aqId"=>$aqId]);
        if($answerQuestionsSolve == 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 查询单条班级答疑
     * @param $classId
     * @return array|SeAnswerQuestion|null
     */
    public function selectOneClassAnswer($classId)
    {
        return self::find()->where(['classID' => $classId])->active()->orderBy('createTime desc')->one();
    }

}
