<?php

namespace common\models\pos;

use common\helper\DateTimeHelper;
use common\services\KeyWordsService;
use frontend\components\WebDataCache;
use Yii;

/**
 * This is the model class for table "se_questionResult".
 *
 * @property integer $resultID
 * @property string $creatorID
 * @property string $rel_aqID
 * @property string $resultDetail
 * @property string $createTime
 * @property string $isUse
 * @property string $useTime
 * @property string $isDelete
 * @property string $imgUri
 * @property string $creatorName
 */
class SeQuestionResult extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_questionResult';
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
     * @return SeQuestionResultQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeQuestionResultQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['resultID'], 'integer'],
            [['resultDetail'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'resultID' => '回答ID',
            'creatorID' => '回答人',
            'rel_aqID' => '关联问题表的ID',
            'resultDetail' => '回答内容',
            'createTime' => '创建时间',
            'isUse' => '是否采用0：未采用，1采用',
            'useTime' => '采用时间',
            'isDelete' => '是否删除，0未删除，1已删除',
            'imgUri' => '图片地址',
	        'creatorName' => '回答人名'
        ];
    }

    /**
     * 回答答疑
     * @param $userId
     * @param $aqid
     * @param $answerContent
     * @return bool
     */
    public function addresultquestion($userId, $aqid, $answerContent, $imgPath)
    {
        $this->creatorID = $userId;
        $this->rel_aqID = $aqid;
        $this->resultDetail = KeyWordsService::ReplaceKeyWord($answerContent);
        $this->createTime = DateTimeHelper::timestampX1000();
        $this->imgUri = $imgPath;
        $this->creatorName = WebDataCache::getTrueName($userId);
        if(self::save(false)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 查询答疑回答列表是否有 最佳答案 存在
     * @param $aqId
     * @return bool
     */
    public function checkQuestionResult($aqId)
    {
        return self::find()->where(['rel_aqID'=>$aqId,'isUse'=>1])->exists();
    }

    /**
     * 设置最佳答案
     * @param $resultid
     * @return bool
     */
    public function updateUseAnswer($resultid)
    {
        $useAnswer = self::updateAll(['isUse' => '1', 'useTime' => DateTimeHelper::timestampX1000()], 'resultID=:resultid', [':resultid' => $resultid]);
        if($useAnswer == 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 查询答案列表
     * @param $aqId
     * @return array|SeQuestionResult[]
     */
    public function selectQuestionResultList($aqId)
    {
        return self::find()->where(['rel_aqID'=>$aqId])->orderBy('createTime desc')->all();
    }


    /**
     *
     * 当前用户回答问题被采纳的总数
     * @param $userId
     * @return $this
     */
    public static  function getUserRelyQuestion($userId){
        return  self::find()->active()->where(['creatorID' => $userId, 'isUse' => 1])->count();
    }

    /**
     *
     * 当前用户回答问题的总数
     * @param $userId
     * @return $this
     */
    public static  function getUserAnswerQuestion($userId){
        return  self::find()->active()->where(['creatorID' => $userId])->count();
    }

    /**
     * 根据问题id和回答id查询单条回答内容
     * @param $aqId
     * @param $resultId
     * @return array|SeQuestionResult|null
     */
    public function selectOneQuestionResult($aqId,$resultId)
    {
        return self::find()->where(['resultID'=>$resultId, 'rel_aqID'=>$aqId])->active()->one();
    }

}
