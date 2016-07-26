<?php
namespace frontend\models;
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-8-6
 * Time: 下午6:26
 */
class StudySectionModel
{
    private $data = array();

    function __construct()
    {
        $this->data = $this->getDate();
    }

    /**
     * 查询学段数据
     * @return array
     */
    public function getDate()
    {
//        $result =new BaseInformationService();
//        $modelList =$result->baseSchoolLevel();
//        if($modelList !== null){
//            return $modelList;
//        }
//        return array();
        $result = \Yii::$app->cache->get('studySection_data');
        if ($result == null) {
            $result = [
                ["cid" => 1, "pid" => 0, "subject" => 1, "grade" => 1, "version" => 1, "chaptername" => 'diyizhang', "isDelete" => 0],
                ["cid" => 2, "pid" => 0, "subject" => 1, "grade" => 1, "version" => 1, "chaptername" => 'diyizhang1', "isDelete" => 0],
                ["cid" => 3, "pid" => 1, "subject" => 1, "grade" => 1, "version" => 1, "chaptername" => 'diyizhang2', "isDelete" => 0],
                ["cid" => 4, "pid" => 1, "subject" => 1, "grade" => 1, "version" => 1, "chaptername" => 'diyizhang3', "isDelete" => 0],
                ["cid" => 4, "pid" => 1, "subject" => 1, "grade" => 1, "version" => 1, "chaptername" => 'diyizhang4', "isDelete" => 0],
            ];
            \Yii::$app->cache->set('studySection_data', $result, 120);
        }

        return $result;

    }

    public static function model()
    {
        $t = new self();
        return $t;
    }

    /**
     * 查询单条数据
     * @param $id
     * @return \YaLinqo\Enumerable
     */
    public function getOne($id)
    {
        return from($this->data)->firstOrDefault(null, function ($v) use ($id) {
            return $v["id"] == $id;
        });
    }

    public function getList($id)
    {
        return from($this->data)->where(function ($v) use ($id) {
            return $v["id"] == $id;
        })->toArray();
    }
}
