<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/3/3
 * Time: 13:20
 */
namespace frontend\models\dicmodels;

use common\models\pos\SeClass;
use common\models\pos\SeClassMembers;
use frontend\components\WebDataCache;
use yii\helpers\ArrayHelper;

class ClassListModel
{
    private $data = array();


    function __construct($school, $department, $grade)
    {
        $this->data = $this->getData($school, $department, $grade);
    }

    /**
     * 获取班级人员数据
     * @param $school
     * @param null $grade
     * @param null $department
     * @return array|\common\models\pos\SeClass[]|mixed
     */
    public function getData($school, $grade = null, $department = null)
    {
        if (!isset($school)) {
            $school = "";
        }
        if (!isset($department)) {
            $department = "";
        }
        if (!isset($grade)) {
            $grade = "";
        }
        $classQuery = SeClass::find()->where(["schoolID" => $school, 'isDelete' => 0, 'status' => 0])->select("classID,className");

        if (!empty($department)) {
            $classQuery->andWhere(["department" => $department]);
        }
        if (!empty($grade)) {
            $classQuery->andWhere(["gradeID" => $grade]);
        }
        $modelList = $classQuery->all();

        return $modelList;
    }

    /**
     * 获取班级列表数据
     * @return \YaLinqo\Enumerable
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
    public function getListData()
    {
        return ArrayHelper::map($this->data, 'classID', 'className');
    }

    /**
     * 调用静态方法
     * @return ClassListModel
     */
    public static function model($school = null, $grade = null, $department = null)
    {
        $staticModel = new self($school, $grade, $department);
        return $staticModel;
    }


    /**
     * 获取班级管理列表数据
     * @param $school
     * @param null $grade
     * @param null $department
     * @return array|\common\models\pos\SeClass[]|mixed
     */
    public static function getClassList($school, $grade = null, $classId = null, $department = null, $status)
    {

        if (!isset($school)) {
            $school = "";
        }
        if (!isset($department)) {
            $department = "";
        }
        if (!isset($grade)) {
            $grade = "";
        }
        $classQuery = SeClass::find()->where(["schoolID" => $school, 'isDelete' => 0])->select("classID,className,joinYear,gradeID,status");

        if (!empty($department)) {
            $classQuery->andWhere(["department" => $department]);
        }
        if (!empty($grade)) {
            $classQuery->andWhere(["gradeID" => $grade]);
        }
        if (!empty($classId)) {
            $classQuery->andWhere(["classID" => $classId]);
        }
        if (!empty($status)) {
            $classQuery->andWhere(["status" => $status - 1]);
        }
        $modelList = $classQuery->orderBy('gradeID asc,classID asc')->all();

        $classMembersModel = new SeClassMembers();
        $classArr = [];
        if ($modelList) {
            foreach ($modelList as $k => $v) {
                $classArr[$k]['joinYear'] = $v['joinYear'];
                $classArr[$k]['gradeID'] = $v['gradeID'];
                $classArr[$k]['classID'] = $v['classID'];
                $classArr[$k]['className'] = $v['className'];
                $classArr[$k]['teacherNum'] = SeClassMembers::getClassNumByClassId($v['classID'], [20401, 20402]);
                $classArr[$k]['studentNum'] = SeClassMembers::getClassNumByClassId($v['classID'], 20403);
                $classAdviser = $classMembersModel->selectClassAdviser($v['classID']);
                $classArr[$k]['classAdviser'] = !empty($classAdviser->userID) ? WebDataCache::getTrueName($classAdviser->userID) : '--';
                $classArr[$k]['status'] = $v['status'];
            }
        }

        return $classArr;
    }

}