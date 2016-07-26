<?php

namespace common\models\pos;

use common\models\sanhai\ShTestquestion;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "se_homeworkAnswerQuestionAll".
 *
 * @property string $aid
 * @property string $mainID
 * @property string $questionID
 * @property string $answerOption
 * @property string $answerTime
 * @property string  $createTime
 * @property string $isDelete
 * @property string $homeworkId
 * @property string $answerRight
 * @property string $ischecked
 * @property string $studentID
 * @property string $homeworkAnswerID
 * @property string $getScore
 * @property string $relId
 * @property integer $correctResult

 *
 */
class SeHomeworkAnswerQuestionAll extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_homeworkAnswerQuestionAll';
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
	 */
	public function getShTestquestion()
	{
		return $this->hasMany(ShTestquestion::className(),['id'=>'questionID']);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeHomeworkAnswerInfo()
    {
        return $this->hasOne(SeHomeworkAnswerInfo::className(),['homeworkAnswerID'=>'homeworkAnswerID']);
    }

    public function  isAnswer(){
        return $this->getSeHomeworkAnswerInfo()->andWhere(['isUploadAnswer'=>1]);
    }

	/**
	 * 查询图片题
	 * @return \yii\db\ActiveQuery
	 */

	public function getSeHomeworkAnswerQuestionPic()
	{
		return $this->hasOne(SeHomeworkAnswerQuestionPic::className(),['relId'=>'relId']);
	}

	/**
	 * 查询图片题
	 * @return \yii\db\ActiveQuery
	 */

	public function getSeHomeworkAnswerImage()
	{
		return $this->hasOne(SeHomeworkAnswerImage::className(),['homeworkAnswerID'=>'homeworkAnswerID']);
	}

    /**
     * 查询这次作业所有人的题及正确数
     * @param $relId
     * @return array|\yii\db\ActiveRecord[]
     *
     */
    public static function getQuestionRightNum($relId){

        return  self::findBySql('SELECT questionID,COUNT(*) AS num FROM `se_homeworkAnswerQuestionAll` WHERE relId=:relId AND correctResult = 3  GROUP BY questionID ', [":relId" => $relId])->asArray()->all();

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['aid'], 'required'],
            [['aid', 'relId', 'correctResult'], 'integer'],
            [['getScore'], 'string', 'max' => 20],
            [['answerOption'], 'string', 'max' => 300],
            [['answerRight'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aid' => 'Aid',
            'mainID' => 'Main ID',
            'questionID' => 'Question ID',
            'answerOption' => 'Answer Option',
            'answerTime' => 'Answer Time',
            'isDelete' => 'Is Delete',
            'homeworkId' => 'Homework ID',
            'answerRight' => 'Answer Right',
            'ischecked' => 'Ischecked',
            'studentID' => 'Student ID',
            'homeworkAnswerID' => 'Homework Answer ID',
            'getScore' => 'Get Score',
            'relId' => 'Rel ID',
            'correctResult' => 'Correct Result',
        ];
    }

    public function  updateMain(){

        $shTestquestion=  ShTestquestion::find()->where(['id' => $this->questionID])->one();
        if($shTestquestion->mainQusId>0)
        {
                $mainQusId=$shTestquestion->mainQusId;
            //            根据大题ID查询所有小题ID
            $testResult = ShTestquestion::find()->where(['mainQusId' => $mainQusId])->select('id')->asArray()->all();
            $questionArray = ArrayHelper::getColumn($testResult, 'id');
            $questionAllResult = SeHomeworkAnswerQuestionAll::find()->where(['questionID' => $questionArray, 'homeworkAnswerID' => $this->homeworkAnswerID])->andWhere(['>','correctResult','0'])->min('correctResult');
            $mainCorrectResult = $questionAllResult==null?'1':$questionAllResult;
        }else {
            //            题目本身就是大题
            $mainQusId = $this->questionID;
            $mainCorrectResult = $this->correctResult;
        }

         SeHomeworkAnswerQuestionMain::updateAll(['correctResult'=>$mainCorrectResult],['questionID'=>$mainQusId,'homeworkAnswerID'=>$this->homeworkAnswerID]);

    }

    /**
     * @inheritdoc
     * @return SeHomeworkAnswerQuestionAllQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeHomeworkAnswerQuestionAllQuery(get_called_class());
    }

    /**
     * @param $homeworkAnswerID
     * @param $questionID
     * @return array|SeHomeworkAnswerQuestionAll|null
     * 根据题的题号查询
     */
    public static function answerAllResult($homeworkAnswerID,$questionID){
        return SeHomeworkAnswerQuestionAll::find()->where(['homeworkAnswerID' => $homeworkAnswerID, 'questionID' => $questionID])->one();
    }
}
