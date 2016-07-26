<?php
/**
 * Created by PhpStorm.
 * User: yangjie
 * Date: 15/8/17
 * Time: 下午1:55
 */

namespace common\models\search;
use common\elasticsearch\es_ActiveRecord;
use common\models\pos\SeQuestionFavoriteFolderNew;
use common\models\sanhai\ShTestquestion;
use frontend\components\helper\LetterHelper;
use frontend\components\WebDataCache;
use frontend\components\WebDataKey;
use Yii;
use yii\elasticsearch\Query;

/**
 * This is the model class for table "testQuestion".
 *
 * @property integer $id
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
 * @property integer $childnum
 * @property string $mainQusId
 * @property string $questionPrice
 * @property string $status
 * @property string $isDelete
 * @property string $inputStatus
 * @property string $quesLevel
 * @property string $quesFrom
 * @property string $textContent
 * @property array     $items
 **/
class Es_testQuestion extends  es_ActiveRecord
{


    /**
     * @return string
     */
    public static function index()
    {
        return 'test-questions';
    }

    /**
     * @return string the name of the type of this record.
     */
    public static function type()
    {
        return 'test-question';

    }
    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        // path mapping for '_id' is setup to field 'id'
        return [
            'id' ,
            'provience',
            'city',
            'country',
            'gradeid',
            'subjectid',
            'versionid',
            'kid',
            'tqtid',
            'provenance',
            'year',
            'school',
            'complexity',
            'capacity',
            'Tags',
            'operater',
            'createTime',
            'updateTime',
            'tqName',
            'content',
            'answerOption',
            'answerContent',
            'analytical',
            'childnum',
            'mainQusId',
            'questionPrice',
            'status',
            'isDelete',
            'inputStatus',
            'quesLevel',
            'quesFrom',
            'isPic',
            'catid',
            'chapterId',
            'noNum',
            'showType',
            'backendOperater',
            'paperId',
            'answerOptCnt',
            'answerOptRitCnt'

        ];
    }

    /**
     * @return array
     */
    public static function primaryKey()
    {
        return ['id'];
    }
    /**
     * 判断当前题目是否被当前用户收藏了
     * @return bool
     */
    public function isCollected(){
        return $this->hasOne(SeQuestionFavoriteFolderNew::className(),['questionId'=>'id'])->where(['userId'=>user()->id,'isDelete'=>0])->exists();
    }

    /**
     * 判断当前题目是否是最近一周内创建的
     * @return bool
     */
    public function isNewQuestion(){
        $week_ago = strtotime('-1 week')*1000;
        if($this->createTime > $week_ago || $this->updateTime > $week_ago){
            return true;
        }
        return false;
    }

    /**
     * 获取大题下的小题内容
     * @return array|ShTestquestion[]
     */
    public  function getQuestionChild(){
        $childQuestionList = Es_testQuestion::find()->where(['mainQusId'=>$this->id])->orderBy('id')->all();
        return $childQuestionList;
    }

    /**
     * @return array|\common\models\sanhai\ShTestquestion[]|mixed
     */
    public  function getQuestionChildCache(){


        $cache = Yii::$app->cache;
        $key = WebDataKey::SEARCH_QUESTION_CHILDREN_LIST_KEY . $this->id;
        $data = $cache->get($key);
        if ($data === false) {
            $data = $this->getQuestionChild();
            $cache->set($key, $data, 600);

        }
        return $data;
    }

    /**
     * @return string
     */
    public function  getQuestionShowType(){
        return WebDataCache::getShowTypeID($this->tqtid);
    }
    //判断题显示选项（不包含radio)
    /**
     * @return string
     */
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

    /**
     * Defines a scope that modifies the `$query` to return only active(status = 1) customers
     */
    public static function active($query)
    {
        $query->andWhere(['status' => 1]);
    }


    /**
     * 前台搜索
     * @return Query
     */
    public static  function  forFrondSearch(){
      return   self::find()->andWhere(['operater' => 0,'mainQusId'=>0,'isDelete'=>0,'status'=>1]);
    }



}
