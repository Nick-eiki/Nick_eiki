<?php

namespace common\models\pos;

use common\helper\DateTimeHelper;
use common\models\sanhai\ShTestquestion;
use common\services\JfManageService;
use frontend\services\pos\pos_HomeWorkManageService;
use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "se_homeworkAnswerInfo".
 *
 * @property integer $homeworkAnswerID
 * @property string $homeworkId
 * @property string $getType
 * @property string $studentID
 * @property string $homeworkScore
 * @property string $uploadTime
 * @property string $isCheck
 * @property string $teacherID
 * @property string $checkTime
 * @property string $summary
 * @property string $isDelete
 * @property string $isUploadAnswer
 * @property string $otherHomeworkAnswerID
 * @property string $relId
 * @property int $correctLevel
 * @property int $correctRate
 */
class SeHomeworkAnswerInfo extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_homeworkAnswerInfo';
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
     * 根据relId 查询se_homework_teacher表信息
     */
    public function getHomeWorkTeacher()
    {
        return $this->hasOne(SeHomeworkTeacher::className(), ['id' => 'homeworkId'])
            ->viaTable('se_homework_rel', ['id' => 'relId']);

    }

    /**
     *主观题批改完成以后修改表状态
     */
    public function updateInfoStatus(){
        if($this->isCheck==0) {

            $this->isCheck = 1;
            $this->teacherID=user()->id;
            //                    批改作业增加积分
            $jfHelper=new JfManageService();
            $jfHelper->myAccount("pos-correctWork",user()->id);
        }
        $homeworkServer = new pos_HomeWorkManageService();
        $homeworkServer->autoHomeworkCorrectResult($this->homeworkAnswerID);
        $this->checkTime = DateTimeHelper::timestampX1000();
    }

    /**
     * 纸质作业
     * @return \yii\db\ActiveQuery
     */
    public function getHomeworkAnswerDetailImage()
    {
        return $this->hasMany(SeHomeworkAnswerDetailImage::className(), ['homeworkAnswerID' => 'homeworkAnswerID']);
    }

    /**
     * 电子作业
     * @return \yii\db\ActiveQuery
     */
    public function getHomeworkAnswerImage()
    {
        return $this->hasMany(SeHomeworkAnswerImage::className(), ['homeworkAnswerID' => 'homeworkAnswerID']);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHomeworkAnswerQuestionAll()
    {
        return $this->hasMany(SeHomeworkAnswerQuestionAll::className(), ['homeworkAnswerID' => 'homeworkAnswerID']);
    }

    /**
     * 获取该答题卡
     * @return \yii\db\ActiveQuery
     */
    public function getHomeworkAnswerCorrectAudio()
    {
        return $this->hasMany(SeHomeworkAnswerCorrectAudio::className(),["homeworkAnswerID" => "homeworkAnswerID"]);
    }

    /**
     * 生成作业答题卡
     * @return array
     */
    public function getHomeworkQuestion()
    {
        //作业推送到班级
        $homeworkRel = SeHomeworkRel::find()->where(['id' => $this->relId])->one();
        $homeworkID = $homeworkRel->homeworkId;

        //作业下的题目
        $homeworkQuestionList = SeHomeworkQuestion::find()->where(['homeworkId' => $homeworkID])->all();

        $questionArr = [];
        foreach ($homeworkQuestionList as $mainQuestion) {

            $childQuestionList = ShTestquestion::find()->where(['mainQusId' => $mainQuestion->questionId])->all();

            if (empty($childQuestionList)) {
                //无小题
                array_push($questionArr, $mainQuestion->questionId);
            } else {
                //有小题
                foreach ($childQuestionList as $childQuestion) {
                    array_push($questionArr, $childQuestion->id);
                }
            }

        }
        return $questionArr;
    }

    /**
     * 学生答题-创建答题卡新
     */
    public static function  createAnswerSheet($relId , $userId)
    {

        $transaction = self::getDb()->beginTransaction();
        try {
            //学生作答表
            $homeworkAnswerInfoModel = new SeHomeworkAnswerInfo();
            $homeworkAnswerInfoModel->relId = $relId;
            $homeworkAnswerInfoModel->getType = 1;
            $homeworkAnswerInfoModel->studentID = $userId;
            $homeworkAnswerInfoModel->uploadTime = DateTimeHelper::timestampX1000();
            $homeworkAnswerInfoModel->isUploadAnswer = 0;
            $homeworkAnswerInfoModel->save(false);

            $homeworkAnswerID = $homeworkAnswerInfoModel->homeworkAnswerID;

            if(SeHomeworkAnswerQuestionMain::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->exists()) {
                return false;
            }
            /** @var SeHomeworkTeacher $homeworkModel */
            $homeworkModel = $homeworkAnswerInfoModel->getHomeWorkTeacher()->one();
            if ($homeworkModel) {
                /** @var SeHomeworkQuestion[] $questionList */
                $questionList = $homeworkModel->getHomeworkQuestion()->all();
                foreach ($questionList as $mainId) {
                    $homeworkAnswerMain = SeHomeworkAnswerQuestionMain::find()->where(['questionID' => $mainId->questionId, 'homeworkAnswerID' => $homeworkAnswerID])->one();
                    if (empty($homeworkAnswerMain)) {
                        $homeworkAnswerMain = new SeHomeworkAnswerQuestionMain();
                        $homeworkAnswerMain->homeworkAnswerID = $homeworkAnswerID;
                        $homeworkAnswerMain->relId = $relId;
                        $homeworkAnswerMain->studentID = $userId;
                        $homeworkAnswerMain->questionID = $mainId->questionId;
                    }
                    if ($homeworkAnswerMain->save(false)) {
                        /**
                         * 作业回答子表（客观题）
                         */
                        $childQuestionList = ShTestquestion::find()->where(['mainQusId' => $mainId->questionId])->all();

                        if (empty($childQuestionList)) {
                            $homeworkQuestionCard = SeHomeworkAnswerQuestionAll::find()->where(['questionID' => $mainId->questionId, 'homeworkAnswerID' => $homeworkAnswerID])->one();
                            if (empty($homeworkQuestionCard)) {
                                $homeworkQuestionCard = new SeHomeworkAnswerQuestionAll();
                                $homeworkQuestionCard->homeworkAnswerID = $homeworkAnswerID;
                                $homeworkQuestionCard->relId = $relId;
                                $homeworkQuestionCard->studentID = $userId;
                                $homeworkQuestionCard->mainID = $homeworkAnswerMain->tID;
                                $homeworkQuestionCard->questionID = $mainId->questionId;
                                $homeworkQuestionCard->answerTime = DateTimeHelper::timestampX1000();
                                $homeworkQuestionCard->save(false);
                            }

                        } else {
                            //有小题
                            foreach ($childQuestionList as $childQuestion) {
                                /** @var SeHomeworkAnswerQuestionAll $homeworkQuestionCard */
                                $homeworkQuestionCard = new SeHomeworkAnswerQuestionAll();
                                $homeworkQuestionCard->homeworkAnswerID = $homeworkAnswerID;
                                $homeworkQuestionCard->relId = $relId;
                                $homeworkQuestionCard->studentID = $userId;
                                $homeworkQuestionCard->mainID = $homeworkAnswerMain->tID;
                                $homeworkQuestionCard->questionID = $childQuestion->id;
                                $homeworkQuestionCard->answerTime = DateTimeHelper::timestampX1000();
                                $homeworkQuestionCard->save(false);
                            }

                        }
                    }

                }

            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
        return true;

    }

    /**
     * 学生答题-创建答题卡
     * @param $userId
     */
    function  makeDtk()
    {
        if(SeHomeworkAnswerQuestionMain::find()->where(['homeworkAnswerID' => $this->homeworkAnswerID])->exists())
        {
            return false;
        }
        /** @var SeHomeworkTeacher $homeworkModel */
        $homeworkModel = $this->getHomeWorkTeacher()->one();
        if ($homeworkModel) {
            /** @var SeHomeworkQuestion[] $questionList */
            $questionList = $homeworkModel->getHomeworkQuestion()->all();
            foreach ($questionList as $mainId) {
                $homeworkAnswerMain = SeHomeworkAnswerQuestionMain::find()->where(['questionID' => $mainId->questionId, 'homeworkAnswerID' => $this->homeworkAnswerID])->one();
                if (empty($homeworkAnswerMain)) {
                    $homeworkAnswerMain = new SeHomeworkAnswerQuestionMain();
                    $homeworkAnswerMain->homeworkAnswerID = $this->homeworkAnswerID;
                    $homeworkAnswerMain->relId = $this->relId;
                    $homeworkAnswerMain->studentID = $this->studentID;
                    $homeworkAnswerMain->questionID = $mainId->questionId;
                 //   $homeworkAnswerMain->createTime = DateTimeHelper::timestampX1000();
                }
                if ($homeworkAnswerMain->save(false)) {
                    /**
                     * 作业回答子表（客观题）
                     */
                    $childQuestionList = ShTestquestion::find()->where(['mainQusId' => $mainId->questionId])->all();

                    if (empty($childQuestionList)) {
                        $homeworkQuestionCard = SeHomeworkAnswerQuestionAll::find()->where(['questionID' => $mainId->questionId, 'homeworkAnswerID' => $this->homeworkAnswerID])->one();
                        if (empty($homeworkQuestionCard)) {
                            $homeworkQuestionCard = new SeHomeworkAnswerQuestionAll();
                            $homeworkQuestionCard->homeworkAnswerID = $this->homeworkAnswerID;
                            $homeworkQuestionCard->relId = $this->relId;
                            $homeworkQuestionCard->studentID = $this->studentID;
                            $homeworkQuestionCard->mainID = $homeworkAnswerMain->tID;
                            $homeworkQuestionCard->questionID = $mainId->questionId;
                            $homeworkQuestionCard->answerTime = DateTimeHelper::timestampX1000();
                         //   $homeworkQuestionCard->createTime=DateTimeHelper::timestampX1000();
                            $homeworkQuestionCard->save(false);
                        }

                    } else {
                        //有小题
                        foreach ($childQuestionList as $childQuestion) {
                                /** @var SeHomeworkAnswerQuestionAll $homeworkQuestionCard */
                                $homeworkQuestionCard = new SeHomeworkAnswerQuestionAll();
                                $homeworkQuestionCard->homeworkAnswerID = $this->homeworkAnswerID;
                                $homeworkQuestionCard->relId = $this->relId;
                                $homeworkQuestionCard->studentID = $this->studentID;
                                $homeworkQuestionCard->mainID = $homeworkAnswerMain->tID;
                                $homeworkQuestionCard->questionID = $childQuestion->id;
                                $homeworkQuestionCard->answerTime = DateTimeHelper::timestampX1000();
                              //  $homeworkQuestionCard->createTime=DateTimeHelper::timestampX1000();
                                $homeworkQuestionCard->save(false);
                            }

                    }
                }

            }


        }
        return true;
    }

    /**
     * @inheritdoc
     * @return SeHomeworkAnswerInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeHomeworkAnswerInfoQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['homeworkAnswerID'], 'required'],
            [['homeworkAnswerID'], 'integer'],
            [['homeworkId', 'studentID', 'homeworkScore', 'uploadTime', 'teacherID', 'checkTime'], 'string', 'max' => 20],
            [['getType', 'isCheck', 'isDelete', 'isUploadAnswer'], 'string', 'max' => 2],
            [['correctLevel'], 'integer', 'max' => 6],
            [['summary'], 'string', 'max' => 500],
            [['otherHomeworkAnswerID'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'homeworkAnswerID' => '作业答案ID',
            'homeworkId' => '作业ID',
            'getType' => '作业类型（0上传，1组卷）',
            'studentID' => '学生ID',
            'homeworkScore' => '作业分数',
            'uploadTime' => '上传时间',
            'isCheck' => '是否批阅0未批 1已批',
            'teacherID' => '批阅教师ID',
            'checkTime' => '批阅时间',
            'summary' => '总结',
            'isDelete' => '是否已删除',
            'isUploadAnswer' => '是否有答案，有此条记录时就为1',
            'otherHomeworkAnswerID' => '他人答案Id',
            'relId' => '',
            'correctLevel' => '0未分等级，1差，2中，3良，4优'
        ];
    }

    /**
     * @param $k
     * @param $relId
     * @return array|SeHomeworkAnswerInfo|null
     * 查询学生所答的每到题的信息
     */
    public static function homeworkAnswerID($k,$relId){
        return SeHomeworkAnswerInfo::find()->select('homeworkAnswerID')->where(['studentID'=>$k,'relId'=>$relId])->answerStatus()->one();
    }

    /**
     * 获取该作业答题总数
     */
    public static function getFinishHomeworkTotalNum($relId){
        $data = SeHomeworkAnswerInfo::find()->where(['relId' => $relId ,'isCheck'=>1])->count();
        return $data;
    }

    /**
     * 获取该作业客观题回答人数
     */
    public static function getUploadHomeworkNum($relId){
        $data = SeHomeworkAnswerInfo::find()->where(['relId' => $relId ,'isUploadAnswer'=>1])->count();
        return $data;
    }

    /**
     * 获取比当前用户正确率高的人数
     */
    public static function getFinishHomeworkOverNum($relId , $nowTeamNum){
        $data = SeHomeworkAnswerInfo::find()->where(['relId' => $relId,'isCheck'=>1])->andWhere(['>','correctRate',$nowTeamNum])->count();
        return $data;
    }

}
