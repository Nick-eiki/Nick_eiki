<?php

namespace common\models\sanhai;

use common\models\pos\SeQuestionFavoriteFolderNew;
use frontend\components\helper\LetterHelper;
use frontend\components\WebDataCache;
use frontend\components\WebDataKey;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "sh_testquestion".
 *
 * @property string $id
 * @property string $guid
 * @property string $provience
 * @property string $city
 * @property string $country
 * @property string $gradeid
 * @property string $subjectid
 * @property string $versionid
 * @property string $kid
 * @property string $tqtid
 * @property string $provenance
 * @property string $year
 * @property string $school
 * @property string $complexity
 * @property string $capacity
 * @property string $Tags
 * @property string $operater
 * @property string $createTime
 * @property string $updateTime
 * @property string $tqName
 * @property string $content
 * @property string $answerOption
 * @property string $answerContent
 * @property string $analytical
 * @property string $childnum
 * @property string $mainQusId
 * @property string $textContent
 * @property string $questionPrice
 * @property string $status
 * @property string $isDelete
 * @property string $inputStatus
 * @property string $quesLevel
 * @property string $quesFrom
 * @property string $isPic
 * @property string $catid
 * @property string $chapterId
 * @property string $paperId
 * @property string $answerOptCnt
 * @property string $showType
 */
class ShTestquestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sh_testquestion';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_sanku');
    }

    /**
     * 判断是主观题还是客观题
     * @return bool
     */
    protected function isMajorQuestion(){
        $isMajor=1;
          if(WebDataCache::getShowTypeID($this->tqtid)==1||WebDataCache::getShowTypeID($this->tqtid)==2||WebDataCache::getShowTypeID($this->tqtid)==9){
              $isMajor=0;
          }
        return $isMajor;
    }

    /**
     * 判断是主观题还是客观题缓存
     * @return bool|mixed
     */
    public function isMajorQuestionCache(){
        $cache = Yii::$app->cache;
        $key = WebDataKey::IS_MAJOR_QUESTION_BY_TQTID_KEY . $this->tqtid;
        $data = $cache->get($key);
        if($data===false){
            $data=$this->isMajorQuestion();
            $cache->set($key,$data,3600);
        }
        return $data;
    }

    public function  getQuestionShowType(){
          return WebDataCache::getShowTypeID($this->tqtid);
    }



    /**
     * 判断当前题目是否被当前用户收藏了
     * @return bool
     */
    public function isCollected(){
        return $this->hasOne(SeQuestionFavoriteFolderNew::className(),['questionId'=>'id'])->where(['userId'=>user()->id,'isDelete'=>0])->exists();
    }

    /**
     * 获取大题下面的小题
     * @return \yii\db\ActiveQuery
     */
    public function getSmallQuestion(){
        return $this->hasMany(ShTestquestion::className(),['mainQusId'=>'id']);
    }
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'childnum', 'mainQusId', 'paperId'], 'integer'],
            [['content', 'answerOption', 'answerContent', 'analytical', 'textContent'], 'string'],
            [['guid'], 'string', 'max' => 60],
            [['provience', 'city', 'country', 'gradeid', 'subjectid', 'tqtid', 'provenance', 'year', 'complexity', 'operater', 'createTime', 'updateTime', 'questionPrice', 'isDelete', 'inputStatus', 'quesLevel', 'isPic','answerOptCnt'], 'string', 'max' => 20],
            [['versionid', 'kid'], 'string', 'max' => 300],
            [['school'], 'string', 'max' => 30],
            [['capacity', 'Tags', 'tqName'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 5],
            [['quesFrom'], 'string', 'max' => 800],
            [['catid', 'chapterId'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'guid' => 'Guid',
            'provience' => 'Provience',
            'city' => 'City',
            'country' => 'Country',
            'gradeid' => 'Gradeid',
            'subjectid' => 'Subjectid',
            'versionid' => 'Versionid',
            'kid' => 'Kid',
            'tqtid' => 'Tqtid',
            'provenance' => 'Provenance',
            'year' => 'Year',
            'school' => 'School',
            'complexity' => 'Complexity',
            'capacity' => 'Capacity',
            'Tags' => 'Tags',
            'operater' => 'Operater',
            'createTime' => 'Create Time',
            'updateTime' => 'Update Time',
            'tqName' => 'Tq Name',
            'content' => 'Content',
            'answerOption' => 'Answer Option',
            'answerContent' => 'Answer Content',
            'analytical' => 'Analytical',
            'childnum' => 'Childnum',
            'mainQusId' => 'Main Qus ID',
            'textContent' => 'Text Content',
            'questionPrice' => 'Question Price',
            'status' => 'Status',
            'isDelete' => 'Is Delete',
            'inputStatus' => 'Input Status',
            'quesLevel' => 'Ques Level',
            'quesFrom' => 'Ques From',
            'isPic' => 'Is Pic',
            'catid' => 'Catid',
            'chapterId' => 'Chapter ID',
            'paperId' => 'Paper ID',
        ];
    }

    /**
     * @inheritdoc
     * @return ShTestquestionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShTestquestionQuery(get_called_class());
    }

    /**
     * 获取大题下的小题内容
     * @return array|ShTestquestion[]
     */
    public  function getQuestionChild(){
        $childQuestionList = ShTestquestion::find()->where(['mainQusId'=>$this->id])->orderBy('id')->all();
        return $childQuestionList;
    }

    public  function getQuestionChildCache(){


        $cache = Yii::$app->cache;
        $key = WebDataKey::QUESTION_CHILDREN_LIST_KEY . $this->id;
        $data = $cache->get($key);
        if ($data == false) {
            $data = $this->getQuestionChild();
            if ($data != null) {
                $cache->set($key, $data, 60);
            }
        }
        return $data;
    }

    //判断题显示选项（不包含radio)
    public function getJudgeQuestionContent(){
        $content = '';
        $op_list = array(
            '0' => array('id' => '0', 'content' => '错'),
            '1' => array('id' => '1', 'content' => '对')
        );

        foreach($op_list as $op){
            $content .= LetterHelper::getLetter($op['id']) . '.' . $op['content'].'&nbsp;';
        }
        return $content;
    }

    //判断题显示选项(包含radio)
    public function getJudgeQuestionOption(){
        $content = '';
        $op_list = array(
            '0' => array('id' => '0', 'content' => '错'),
            '1' => array('id' => '1', 'content' => '对')
        );
        $content .=  Html::radioList("answer[$this->id]", '', ArrayHelper::map($op_list, 'id', 'content'),['qid' => $this->id, 'tpid' => $this->getQuestionShowType()]);

        return $content;
    }

    //判断题显示选项答案(包含radio)
    public function getJudgeQuestionOptionAnswer($objectiveAnswer){
        $content = '';
        $op_list = array(
            '0' => array('id' => '0', 'content' => '错'),
            '1' => array('id' => '1', 'content' => '对')
        );
        foreach($op_list as $op) {
            $color = '';
            $obj = $objectiveAnswer[$this->id];
            if($obj['answerOption'] == null){
                $content .= '<label>'.$op['content'].'</label>';
            }else{
                $answer = explode(',', $obj['answerOption']);
                if (in_array($op['id'], $answer)) {
                    if ($obj['correctResult'] == 1) {
                        $color = 'chkLabel_error';
                    } elseif ($obj['correctResult'] == 3) {
                        $color = 'chkLabel_ac';
                    }
                }
                $content .= '<label class="' . $color . '">' . $op['content'] . '</label>';
            }
        }

        return $content;
    }


}
