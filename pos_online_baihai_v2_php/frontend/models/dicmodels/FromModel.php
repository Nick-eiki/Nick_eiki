<?php
namespace frontend\models\dicmodels;
use common\models\sanhai\SeDateDictionary;
use yii\helpers\ArrayHelper;

/**
 * 题目出处
 * Created by PhpStorm.
 * User: liquan
 * Date: 14-11-13
 * Time: 下午16:18
 */
class FromModel
{
    private $data = array();

    function __construct()
    {
        $this->data = $this->getData();
    }


    /**
     * 题目出处
     * @return array
     */
    public function getData()
    {
        $cacheId = 'From__dataV2';
        $modelList = \Yii::$app->cache->get($cacheId);

        if ($modelList === false) {
            $modelList=SeDateDictionary::find()->where(['firstCode'=>210])->select('secondCode,secondCodeValue')->all();
            if (!empty($modelList)) {
                \Yii::$app->cache->set($cacheId, $modelList, 3600);
            }
        }
        return is_null($modelList) ? array() : $modelList;

    }


    /**
     * 获取列表数据
     * @return \YaLinqo\Enumerable
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
     * 获取一条数据
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
     * 获取名称
     * @param $id
     */
    public function getFromName($id) {
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
