<?php
namespace frontend\models\dicmodels;
use common\models\sanhai\SeDateDictionary;
use yii\helpers\ArrayHelper;

/**
 * 班级职务
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-8-6
 * Time: 下午6:44
 */
class ClassDutyModel
{
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
     * 查询所有数据
     * @return array
     */
    public function getData()
    {
        $cacheKey = 'classduty_dataV2';
        $modelList = \Yii::$app->cache->get($cacheKey);
        if ($modelList === false) {
            $modelList=SeDateDictionary::find()->where(['firstCode'=>201])->select('secondCode,secondCodeValue')->all();
            if (!empty($modelList)) {
                \Yii::$app->cache->set($cacheKey, $modelList, 3600);
            }
        }

        return is_null($modelList) ? array() : $modelList;

    }

    /**
     * 获取数据列表
     * @param $id
     * @return array
     */
    public function getList()
    {
        $result = from($this->data)->where(function ($v) {
            return $v->secondCode;
        })->toArray();
        return ArrayHelper::map($result, 'secondCode', 'secondCodeValue');
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

    public function  getListFirst()
    {
        $list = $this->getList();
        if ($list) {
           return   reset($list);
        }
        return '';
    }

    /**
     * 获取名称
     * @param $id
     * @return string
     */
    public function getClassDutyName($id)
    {
        if (!is_numeric($id)) return;
        $result = $this->getOne($id);
        return isset($result) ? $result->secondCodeValue : '';
    }


}
