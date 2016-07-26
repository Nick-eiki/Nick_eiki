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
class DegreeModel
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
        $cacheId = 'degree__dataV2';
        $modelList = \Yii::$app->cache->get($cacheId);

        if ($modelList === false) {
            $modelList = SeDateDictionary::find()->where(['firstCode' => 211])->select('secondCode,secondCodeValue')->all();
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

    /**
     * 获取题目难度名称
     * @param $id
     */
    public function getDegreeName($id)
    {
        if (!is_numeric($id)) return '';
        $arr = $this->getOne($id);
        if ($arr != '') {
            return $arr->secondCodeValue;
        }
        return '';
    }

    /**
     * 根据难度id获取难度图标
     * @param $id
     * @return mixed
     */
    public function getIcon($id)
    {
        $iconArray = [
            '21101' => 'dif_easy',
            '21102' => 'dif_easy_v',
            '21103' => 'dif_mid',
            '21104' => 'dif_hard',
            '21105' => 'dif_hard_v',
        ];
        if ($id != null && array_key_exists($id, $iconArray)) {
            return $iconArray[$id];
        }
        return '';
    }


    /**
     * 静态方法获取数据
     * @return DegreeModel
     */
    public static function model()
    {
        $staticModel = new self();
        return $staticModel;
    }
}