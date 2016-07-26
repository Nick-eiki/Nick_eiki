<?php
namespace frontend\models\dicmodels;
use common\models\sanhai\SeDateDictionary;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-10
 * Time: 下午5:57
 */
class LengthSchoolModel{
    private $data = array();

    function __construct()
    {
        $this->data = $this->getData();
    }

    public static function  model()
    {
        $staticModel = new self();
        return $staticModel;
    }

    /**
     * 查询学制数据源
     * @return array
     */
    public function getData()
    {
        $cacheKey = 'lengthSchool_dataV2';
        $modelList = \Yii::$app->cache->get($cacheKey);
        if ($modelList === false) {
            $modelList=SeDateDictionary::find()->where(['firstCode'=>205])->select('secondCode,secondCodeValue')->all();
            if (!empty($modelList)) {
                \Yii::$app->cache->set($cacheKey, $modelList, 3600);
            }
        }

        return is_null($modelList) ? array() : $modelList;

    }

    /**
     * 查询学制列表
     * @return array
     */
    public function getList()
    {
      return from($this->data)->where(function ($v) {
          return true;
        })->toArray();

    }

    /**
     * 去掉listData
     * @return array
     */
    public function getListData()
    {
        return ArrayHelper::map($this->data, 'secondCode', 'secondCodeValue');
    }

    /**
     * 查询单条数据
     * @param $id
     * @return mixed
     */
    public function getOne($id)
    {
        return from($this->data)->firstOrDefault(null, function ($v) use ($id) {
            return $v->secondCode == $id;
        });
    }

    /**
     * 获取学制名称
     * @param $id
     * @return string
     */
    public function getLengthSchoolName($id)
    {
        if (!is_numeric($id)) return;
        $result = $this->getOne($id);
        return isset($result) ? $result->secondCodeValue : '';
    }
}