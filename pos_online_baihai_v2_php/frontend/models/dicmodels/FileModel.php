<?php
namespace frontend\models\dicmodels;
use common\models\sanhai\SeDateDictionary;


/**
 * 文件类型
 * Created by unizk.
 * User: ysd
 * Date: 14-8-6
 * Time: 下午6:44
 */
class FileModel
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
        $cacheKey = 'file_dataV2';
        $modelList = \Yii::$app->cache->get($cacheKey);
        if ($modelList === false) {
            $modelList=SeDateDictionary::find()->where(['firstCode'=>502,'status'=>'1'])->select('secondCode,secondCodeValue')->all();
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
        return from($this->data)->where(function ($v)  {
            return true;
        })->toList();
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
