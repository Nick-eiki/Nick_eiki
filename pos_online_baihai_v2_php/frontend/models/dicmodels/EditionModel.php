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
class EditionModel
{
    /**
     * @var array|ServiceJsonResult
     */
    private $data = array();

    /**
     * EditionModel constructor.
     */
    function __construct()
    {
        $this->data = $this->getData();
    }

    /**
     * 版本 获取全部数据
     * @return array|ServiceJsonResult
     */
    public function getData()
    {
        $cacheId = 'edition__dataV2';
        $modelList = \Yii::$app->cache->get($cacheId);

        if ($modelList === false) {
            $modelList=SeDateDictionary::find()->where(['firstCode'=>206])->select('secondCode,secondCodeValue')->all();
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
            return $v->secondCode == $id;
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

    /**
     * @return array
     */
    public function  getListData()
    {
        return ArrayHelper::map($this->data, 'secondCode', 'secondCodeValue');
    }

    /**
     * 获取版本名称
     * @param $id
     */
    public function getEditionName($id) {
        if (!is_numeric($id)) return '';
        $arr = $this->getOne($id);
        if ($arr != '') {
            return $arr->secondCodeValue;
        }
        return '';
    }

    /**
     * 获取多个ids名称
     * @param array $ids
     * @return string
     */
    public function getEditionNames(array $ids) {

        $versionArray=[];
        foreach($ids as $item){

           $name=   $this->getEditionName($item);
            if(!empty($name)){
                $versionArray[]=$name;
            }
        }
        return  implode(',',$versionArray);
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
