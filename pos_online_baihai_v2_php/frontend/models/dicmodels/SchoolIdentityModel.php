<?php
namespace frontend\models\dicmodels;
use common\models\sanhai\SeDateDictionary;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-8-6
 * Time: 下午1:38
 */
class SchoolIdentityModel
{
    private $data = array();

    function __construct()
    {
        $this->data = $this->getDate();
    }

    /**
     * 版本 获取全部数据
     * @return array|ServiceJsonResult
     */
    public function getDate()
    {
        $cacheId = 'schoolIdentity__dataV2';
        $modelList = \Yii::$app->cache->get($cacheId);

        if ($modelList === false) {
            $modeList=SeDateDictionary::find()->where(['firstCode'=>207])->select('secondCode,secondCodeValue')->all();
            if (!empty($modelList)) {
                \Yii::$app->cache->set($cacheId, $modelList, 3600);
            }
        }
        return is_null($modelList) ? array() : $modelList;

    }


    /**
     * 版本 获取单条数据
     * @param $id
     * @return array
     */
    public function getOne($id)
    {
        return from($this->data)->firstOrDefault(null, function ($v) use ($id) {
            return $v["id"] == $id;
        });
    }

    /**
     * 获取版本数据列表
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
     * 静态方法获取数据
     * @return EditionModel
     */
    public static function model()
    {
        $staticModel = new self();
        return $staticModel;
    }
}
