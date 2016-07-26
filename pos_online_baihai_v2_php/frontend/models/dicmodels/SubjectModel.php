<?php
namespace frontend\models\dicmodels;
use common\models\sanhai\SeDateDictionary;
use common\models\sanhai\SeSchoolGrade;
use frontend\components\WebDataKey;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-8-6
 * Time: 下午6:44
 */
class SubjectModel
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
	 * 查询所有科目数据
	 * @return array
	 */
	public function getData()
	{
        $cacheKey = 'subject_dataV2';
		$modelList = \Yii::$app->cache->get($cacheKey);
		if ($modelList === false) {
            $modelList=SeDateDictionary::find()->where(['firstCode'=>100])->select('secondCode,secondCodeValue')->all();
			if (!empty($modelList)) {
				\Yii::$app->cache->set($cacheKey, $modelList, 3600);
			}
		}

		return is_null($modelList) ? array() : $modelList;

	}

	/**
	 * 获取科目数据列表
	 * @param $id
	 * @return array
	 */
	public function getList()
	{
		$result = from($this->data)->where(function ($v) {
			return $v->secondCode;
		})->toArray();
		return ArrayHelper::map($result, 'secondCode', 'secondCodeValue');
	}

	public function getListData()
	{
		return ArrayHelper::map($this->data, 'secondCode', 'secondCodeValue');
	}


	/**
	 * 查询科目单条数据
	 * @param $id
	 * @return \YaLinqo\Enumerable
	 */
	public function getOne($id)
	{
		return from($this->data)->firstOrDefault(null, function ($v) use ($id) {
			return $v->secondCode == $id;
		});
	}

	/**
	 * 获取科目名称
	 * @param $id
	 * @return string
	 */
	public function getSubjectName($id)
	{
		if (!is_numeric($id))
        {
            return '';
        }
		$result = $this->getOne($id);
		return isset($result) ? $result->secondCodeValue : '';
	}

	/*
	 * 通过年级获取科目
	 */
	static public function getSubByGrade($id = '', $notHasComp = '')
	{
        $department=SeSchoolGrade::find()->where(['gradeId'=>$id])->select('schoolDepartment')->one();
        if(empty($notHasComp)){
           if(empty($department)){
               $data=SeDateDictionary::find()->where(['firstCode'=>100])->select('secondCode,secondCodeValue')->all();
           }else{
               $data=SeDateDictionary::find()->where(['firstCode'=>100])->andFilterWhere(['like','reserve1',$department->schoolDepartment])->select('secondCode,secondCodeValue')->all();
           }
        }else{
            if(empty($department)){
                $data=SeDateDictionary::find()->where(['firstCode'=>100])->andFilterWhere(['and','secondCode!=10027','secondCode!=10028'])->select('secondCode,secondCodeValue')->all();
            }else{
                $data=SeDateDictionary::find()->where(['firstCode'=>100])->andFilterWhere(['like','reserve1',$department->schoolDepartment])->andFilterWhere(['and','secondCode!=10027','secondCode!=10028'])->select('secondCode,secondCodeValue')->all();
            }
        }
        return $data;
	}

	/**
	 * 根据学部获取科目
	 * @param $department
	 * @return array
	 */
	static public function getSubjectByDepartment($department, $notHasComp = '')
	{
		if ($department == null) {
			return array();
		} else {
            if(empty($notHasComp)){
                $result=SeDateDictionary::find()->where(['firstCode'=>100])->andFilterWhere(['like','reserve1',$department])->select('secondCode,secondCodeValue')->all();
            }else{
                $result=SeDateDictionary::find()->where(['firstCode'=>100])->andFilterWhere(['like','reserve1',$department])->andFilterWhere(['and','secondCode!=10027','secondCode!=10028'])->select('secondCode,secondCodeValue')->all();
            }
			return $result;
		}
	}

    /**
     * 根据学部获取科目缓存
     * @param $department
     * @param string $notHasComp
     * @return array
     */
    static public function getSubjectByDepartmentCache($department,$notHasComp = ''){
        $cache = Yii::$app->cache;
           $key=WebDataKey::SUBJECT_DATA_BY_DEPARTMENT_KEY.$department.'__'.$notHasComp;
        $data = $cache->get($key);
        if($data==false){
            $data=self::getSubjectByDepartment($department,$notHasComp);
            if($data!=null){
                $cache->set($key, $data, 6000);
            }

        }
        return $data;

    }

	static public function getSubjectByDepartmentListData($department, $notHasComp = '')
	{
		$list = self::getSubjectByDepartment($department, $notHasComp);
		return ArrayHelper::map($list, 'secondCode', 'secondCodeValue');
	}

    /**
     * 根据科目名字查找对应的科目id
     */
    public static function getIdBySubjectName($subjectName){

        if(empty($subjectName)){
            return 0;
        }
        $data = SeDateDictionary::find()->where(['secondCodeValue'=>$subjectName])->one();
        return isset($data) ? $data->secondCode : 0;
    }


}
