<?php
namespace frontend\models;
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-8-6
 * Time: 下午6:31
 */
class SchoolLengthModel
{
    private $data = array();

    function __construct()
    {
        $this->data = $this->getDate();
    }

    /**
     * 查询学制数据
     * @return array
     */
    public function getDate()
    {

        $modelList = \Yii::$app->cache->get('schoolLength_data');
        if ($modelList === false) {
            $result = new Apollo_BaseInformationService();
            $modelList = $result->baseSchoolLength();
            if (!empty($modelList)) {
                \Yii::$app->cache->set('schoolLength', $modelList, 120);
            }
        }
        return is_null($modelList) ? array() : $modelList;

//        $result = [
//            ["lid" => 1, "name" => '学制', "isDelete" => 0, "pid" => 0],
//            ["lid" => 1, "name" => '六三', "isDelete" => 0, "pid" => 1],
//            ["lid" => 2, "name" => '五四', "isDelete" => 0, "pid" => 1],
//            ["lid" => 3, "name" => '五三', "isDelete" => 0, "pid" => 1],
//        ];
//        return $result;

    }

    public static function  model()
    {
        $staticModel = new self();
        return $staticModel;
    }

    /**
     * 查询学制单条数据
     * @param $id
     * @return \YaLinqo\Enumerable
     */
    public function getOne($id)
    {
        return from($this->data)->firstOrDefault(null, function ($v) use ($id) {
            return $v["id"] == $id;
        });
    }

    /**
     * 调用 此方法 使用
     * @return SchoolLengthModel
     */
    public function getSchoolLengthList()
    {
        return $this->getList(1);
    }

    /*
     * 静态方法
     */

    /**获取学制列表数据
     * @return SchoolLengthModel
     */
    public function getList($id)
    {
        return from($this->data)->where(function ($v) use ($id) {
            return $v["pId"] == $id;
        })->toList();
    }
}
