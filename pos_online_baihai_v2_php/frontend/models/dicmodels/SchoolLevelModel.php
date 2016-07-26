<?php
namespace frontend\models\dicmodels;
use common\models\sanhai\SeDateDictionary;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-8-18
 * Time: 下午2:32
 */
class SchoolLevelModel
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
     * 查询学段 数据
     * @return array
     */
    public function getDataList()
    {
        $cacheKey = 'schoolLevel_data-new';
        $modelList = \Yii::$app->cache->get($cacheKey);
        if ($modelList === false) {
            $modelList=SeDateDictionary::find()->where(['firstCode'=>202])->select('secondCode,secondCodeValue')->all();
            if (!empty($modelList)) {
                \Yii::$app->cache->set($cacheKey, $modelList, 3600);
            }
        }

        return  $modelList;

    }

    /**
     * 查询学段列表
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
        return ArrayHelper::map($this->getDataList(), 'secondCode', 'secondCodeValue');

    }

    /**
     * 根据学校学部查询下拉列表
     * @return array
     */
    public function getListInData($arr = array())
    {
        $listData = $this->getListData();
        if (empty($arr)) {
            return $listData;
        }
        $list = array();

        foreach ($arr as $v) {
            foreach ($listData as $key => $val) {
                if ($v == $key) {
                    $list[$key] = $val;
                }
            }
        }
        return $list;
    }

    /**
     * 查询学段单条数据
     * @param $id
     * @return \YaLinqo\Enumerable
     */
    public function getOne($id)
    {
       return  from($this->data)->firstOrDefault(null, function ($v) use ($id) {
            return $v->secondCode == $id;
        });
    }

    /**
     * 获取学段名称
     * @param string $id
     * @return mixed
     */
    public function getSchoolLevelhName($id)
    {
       if (!is_numeric($id)) return '';

        $arr = $this->getOne($id);
        return isset($arr) ? $arr->secondCodeValue : '';
    }

    /**
     *  学校用部
     * @param $ids
     * @return array
     */
    public function  departmentNameArr($ids)
    {
        $arr = explode(',', $ids);

        $resultArr = [];

        foreach ($arr as $item) {
            $name = $this->getSchoolLevelhName($item);
            if ($name) {
                $resultArr[] = $name;
            }
        }

        return $resultArr;

    }

    public function schoolLevelArr($arr)
    {

    }

}