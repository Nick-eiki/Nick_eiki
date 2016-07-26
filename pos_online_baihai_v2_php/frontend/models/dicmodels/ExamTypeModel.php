<?php
namespace frontend\models\dicmodels;
use common\models\sanhai\SeDateDictionary;
use yii\helpers\ArrayHelper;

/**
 * 获取题目难度信息
 * Created by PhpStorm.
 * User: liquan
 * Date: 2014/11/13
 * Time: 16:39
 */
class ExamTypeModel
{
    private $data = array();

    function __construct()
    {
        $this->data = $this->getData();
    }

    /**
     * 获取全部数据
     * @return array|ServiceJsonResult
     */
    public function getData()
    {
        $cacheId = 'examtype__dataV2';
        $modelList = \Yii::$app->cache->get($cacheId);

        if ($modelList === false) {
            $modelList=SeDateDictionary::find()->where(['firstCode'=>219])->select('secondCode,secondCodeValue')->all();
            if (!empty($modelList)) {
                \Yii::$app->cache->set($cacheId, $modelList, 3600);
            }
        }
        return is_null($modelList) ? array() : $modelList;

    }


    /**
     *  获取单条数据
     * @param $id
     * @return array
     */
    public function getOne($id)
    {
        return from($this->data)->firstOrDefault(null, function ($v) use ($id) {
            return $v->secondCode == $id;
        });
    }

    /**
     * 获取数据列表
     * @param $id
     * @return array
     */
    public function getList()
    {
        return from($this->data)->where(function ($v) {
            return true;
        })->toList();
    }

    public function  getListData()
    {
        return ArrayHelper::map($this->data, 'secondCode', 'secondCodeValue');
    }

    public function  getSubListData()
    {
        $typeArray = array(
            "21906" => "随堂测验",
            "21907" => "一周测验",
            "21908" => "单元测验",
        );
        return $typeArray;

    }

    /**
     * 是否是大考
     * @param $id
     * @return bool
     */
    public function  isBigExam($id)
    {

        $m = from($this->data)->firstOrDefault(null, function ($v) use ($id) {
            return $v->secondCode == $id;
        });
        if ($m != null) {
            return $m->reserveTwo == 0;
        }
        return false;
    }


    /**
     * 静态方法获取数据
     * @return ExamTypeModel
     */
    public static function model()
    {
        $staticModel = new self();
        return $staticModel;
    }

    /**
     * 获取手动创建考试类型
     * @return array|ServiceJsonResult
     */
    public function getManualType(){
      $array=array("21901","21902","21904","21905","21906");
        $manualArray=array();
       foreach($this->data as $v){
           if(!in_array($v->secondCode,$array)){
               array_push($manualArray,$v);
           }
       }
        return $manualArray;
    }
}