<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_sameQuestion".
 *
 * @property integer $sameQid
 * @property string $aqID
 * @property string $sameQueUserId
 */
class SeSameQuestion extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_sameQuestion';
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
     * @return SeSameQuestionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeSameQuestionQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sameQid'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sameQid' => '同问主键',
            'aqID' => '问题ID',
            'sameQueUserId' => '同问人Id',
        ];
    }


    /**
     * 查询该用户是否同问过该答疑
     * @param $aqid
     * @param $userId
     * @return bool
     */
    public function checkSame($aqid, $userId)
    {
        return self::find()->where(['aqID'=>$aqid, 'sameQueUserId'=>$userId])->exists();
    }

    /**
     * 添加同问
     * @param $aqid
     * @param $userId
     * @return bool
     */
    public function addSame($aqid, $userId)
    {
        $this->aqID = $aqid;
        $this->sameQueUserId = $userId;
        if(self::save(false)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 查询所有同问
     * @param $aqId
     * @return array|SeAnswerQuestion[]
     */
    public function selectSameQuestionAll($aqId)
    {
        $sameAll = self::find()->where(['aqID'=>$aqId])->all();
        return $sameAll;
    }
}
