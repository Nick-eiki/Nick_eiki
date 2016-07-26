<?php
namespace frontend\models\dicmodels;

use common\models\sanhai\SeDateDictionary;
use common\models\sanhai\SeSchoolGrade;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-8
 * Time: 下午4:47
 */
class LoadTextbookVersionModel
{
	private $data = array();

	function __construct($subject, $grade = null, $department = null)
	{
		$this->data = $this->getData($subject, $grade, $department);
	}


	/**
	 * 根据学科查询版本
	 * @param $subject
	 * @return array|ServiceJsonResult
	 */
	public function getData($subject = null, $grade = null, $department = null)
	{
		$cacheId = 'loadTextbookVersionV2_data_' . $subject . 'grade' . $grade . 'department' . $department;
		$modelList = \Yii::$app->cache->get($cacheId);
		if ($modelList === false) {
			if (empty($subject)) {
				return [];
			}
			if (empty($department)) {
				if (empty($grade)) {
					$modelList = SeDateDictionary::find()->where(['firstCode' => 206])->andFilterWhere(['like', 'reserve1', $subject])->select('secondCode,secondCodeValue')->all();
				} else {
					$departmentId = SeSchoolGrade::find()->where(['gradeId' => $grade])->select('schoolDepartment')->one();
					if ($departmentId['schoolDepartment'] == 20201) {
						$modelList = SeDateDictionary::find()->where(['firstCode' => 206])->andFilterWhere(['like', 'reserve1', $subject])->select('secondCode,secondCodeValue')->all();
					} elseif ($departmentId['schoolDepartment'] == 20202) {
						$modelList = SeDateDictionary::find()->where(['firstCode' => 206])->andFilterWhere(['like', 'reserve2', $subject])->select('secondCode,secondCodeValue')->all();
					} elseif ($departmentId['schoolDepartment'] == 20203) {
						$modelList = SeDateDictionary::find()->where(['firstCode' => 206])->andFilterWhere(['like', 'reserve3', $subject])->select('secondCode,secondCodeValue')->all();
					} else {
						return [];
					}
				}
			} else {
				if ($department == 20201) {
					$modelList = SeDateDictionary::find()->where(['firstCode' => 206])->andFilterWhere(['like', 'reserve1', $subject])->select('secondCode,secondCodeValue')->all();
				} elseif ($department == 20202) {
					$modelList = SeDateDictionary::find()->where(['firstCode' => 206])->andFilterWhere(['like', 'reserve2', $subject])->select('secondCode,secondCodeValue')->all();
				} elseif ($department == 20203) {
					$modelList = SeDateDictionary::find()->where(['firstCode' => 206])->andFilterWhere(['like', 'reserve3', $subject])->select('secondCode,secondCodeValue')->all();
				} else {
					return [];
				}
			}
			if (!empty($modelList)) {
				\Yii::$app->cache->set($cacheId, $modelList, 3600);
			}
		}
		return is_null($modelList) ? array() : $modelList;
	}

	/**
	 * 查询版本数据
	 * @return array
	 */
	public function getList()
	{
		return from($this->data)->where(function ($v) {
			return true;
		})->toList();
	}

	/**
	 * 查询一条版本数据
	 * @param $id
	 * @return mixed
	 */
	public function getOne($id)
	{
		return from($this->data)->firstOrDefault(null, function ($v) use ($id) {
			return $v->secondCode == $id;
		});
	}

	public function  getListData()
	{

		return ArrayHelper::map($this->data, 'secondCode', 'secondCodeValue');
	}


	/**
	 * 调用静态方法
	 * @param null $subject
	 * @return LoadTextbookVersionModel
	 */
	public static function model($subject = null, $grade = null, $department = null)
	{
		$staticModel = new self($subject, $grade, $department);
		return $staticModel;
	}

}