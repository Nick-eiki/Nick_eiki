<?php
namespace frontend\models\dicmodels;
use common\models\pos\SePaperQuesTypeRlts;
use common\models\sanhai\SeDateDictionary;
use common\models\sanhai\SeSchoolGrade;

/**
 * 题目题型
 * Created by PhpStorm.
 * User: liquan
 * Date: 2014/11/13
 * Time: 16:30
 */
class ItemTypeModel
{
    private $data = array();

    function __construct()
    {
        $this->data = $this->getData();
    }


    /**
     * 题型
     * @return array
     */
    public function getData()
    {
        $cacheId = 'ItemType__dataV2';
        $modelList = \Yii::$app->cache->get($cacheId);

        if ($modelList === false) {
            $modelList=SeDateDictionary::find()->where(['firstCode'=>209])->select('secondCode,secondCodeValue')->all();
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

    public function  getListData($grade='',$subject='')
    {
        if ($grade == ''|| $subject == '') {
            return array();
        }else{
            $gradeid=SeSchoolGrade::find()->where(['gradeId'=>$grade])->select('schoolDepartment')->one();
            $data= SePaperQuesTypeRlts::find()->where(['schoolLevelId'=>$gradeid->schoolDepartment,'subjectId'=>$subject])->select('paperQuesTypeId,paperQuesType')->all();
            return $data;
        }

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
    public function getItemTypeName($id) {
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
