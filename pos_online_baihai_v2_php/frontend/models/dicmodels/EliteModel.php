<?php
namespace frontend\models\dicmodels;
use common\models\sanhai\SeDateDictionary;
use yii\helpers\ArrayHelper;

/**
 * 名校数据
 * Created by PhpStorm.
 * User: liquan
 * Date: 2014/11/13
 * Time: 16:54
 */
class EliteModel
{
    private $data = array();

    function __construct()
    {
        $this->data = $this->getData();
    }

    /**
     * 名校数据 获取全部数据
     * @return array|ServiceJsonResult
     */
    public function getData()
    {
        $cacheId = 'elite__dataV2';
        $modelList = \Yii::$app->cache->get($cacheId);

        if ($modelList === false) {
            $modelList=SeDateDictionary::find()->where(['firstCode'=>208])->select('secondCode,secondCodeValue')->all();
            if (!empty($modelList)) {
                \Yii::$app->cache->set($cacheId, $modelList, 3600);
            }
        }
        return is_null($modelList) ? array() : $modelList;

    }


    /**
     * 、 获取单条数据
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
     * 获取名校名称
     * @param $id
     */
    public function getEliteName($id) {
        if (!is_numeric($id)) return;
        $arr = $this->getOne($id);
        if ($arr != '') {
            return $arr->secondCodeValue;
        }
        return false;
    }


    /**
     * 静态方法获取数据
     * @return EditionModel
     */
    public static function model()
    {
        $staticModel = new self();
        return $staticModel;
    }
}