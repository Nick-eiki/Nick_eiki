<?php
namespace frontend\models\dicmodels;
use common\models\sanhai\SeDateDictionary;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: liquan
 * Date: 2014/12/5
 * Time: 11:02
 */
class QuesLevelModel
{
    private $data = array();

    function __construct()
    {
        $this->data = $this->getDataList();
    }

    public static function  model()
    {
        $staticModel = new self();
        return $staticModel;
    }

    /**
     * 查询题目等级 数据
     * @return array
     */
    public function getDataList()
    {
        $cacheKey = 'quesLevel_dataV2';
        $modelList = \Yii::$app->cache->get($cacheKey);
        if ($modelList === false) {
            $modelList=SeDateDictionary::find()->where(['firstCode'=>216])->select('secondCode,secondCodeValue')->all();
            if (!empty($modelList)) {
                \Yii::$app->cache->set($cacheKey, $modelList, 3600);
            }
        }

        return is_null($modelList) ? array() : $modelList;

    }

    /**
     * 查询题目等级列表
     * @param $id
     * @return array
     */
    public function getList()
    {
        return $this->getDataList();
    }

    /**
     * 下拉列表
     * @return array
     */
    public function getListData()
    {
        return ArrayHelper::map($this->getDataList(),'secondCode','secondCodeValue');

    }

    /**
     * 根据等级查询下拉列表
     * @return array
     */
    public function getListInData($arr=array())
    {
        $listData=  $this->getListData();
        if (empty($arr)){
            return $listData;
        }
        $list=array();

        foreach($arr as $v){
            foreach($listData as $key=>$val){
                if ($v==$key){
                    $list[$key]=$val;
                }
            }
        }
        return $list;
    }

    /**
     * 查询单条数据
     * @param $id
     * @return \YaLinqo\Enumerable
     */
    public function getOne($id)
    {
        return from($this->data)->firstOrDefault(null, function ($v) use ($id) {
            return $v->secondCode == $id;
        });
    }

    /**
     * 获取名称
     * @param string $id
     * @return mixed
     */
    public function getSchoolLevelhName($id)
    {
        if (!is_numeric($id)) return;

        $arr = $this->getOne($id);
        return isset($arr)?$arr->secondCodeValue:'';
    }

}