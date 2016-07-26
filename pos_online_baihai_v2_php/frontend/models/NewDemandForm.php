<?php
namespace frontend\models;

use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/24
 * Time: 15:59
 */
class NewDemandForm extends Model
{
    public $type;            //课程类型  0：精品课程1：每周一课
    public $gradeID;        //年级ID
    public $subjectID;      //科目id
    public $version;        //版本
    public $courseName;     //课程名称
    public $courseBrif;        //课程简介
    public $teacherID;      //老师id。
    public $cost;            //是否收费（可为空）
    public $provience;    //地区：省
    public $city;            //地区：市/地区
    public $country;        //地区：县城
    public $creatorID;        //创建者ID
    public $schoolProportion;   //学校比例（可为空）
    public $teacherProportion;    //教师比例（可为空）
    public $price;            //价格（可为空）
    public $classID;        //班级
    public $stuLimit;       //权限（本班学生）0：不可见1：可见 (可为空)
    public $groupMemberLimit;    //权限（教研组同事）0：不可见1：可见 (可为空)
    public $allMemLimit;        //权限（所有）0：不可见1：可见 (可为空)
    public $isAgreement;
    public $isShare;        //是否共享

    /**
     * 教授科目
     * @var
     */
    public $subjectNumber;


    public function rules()
    {
        return [
            [['gradeID', 'version', 'courseName', 'teacherID', 'stuLimit', 'groupMemberLimit', 'allMemLimit', 'provience', 'city', 'country', 'isShare'], "required"],
            [['type', 'gradeID', 'subjectID', 'version', 'courseName', 'courseBrif', 'teacherID', 'classID', 'stuLimit', 'groupMemberLimit', 'allMemLimit', 'cost', 'provience', 'city', 'country', 'creatorID', 'schoolProportion', 'teacherProportion', 'price', 'isAgreement', 'isShare'], "safe"],

        ];
    }

    public function attributeLabels()
    {
        return array(
            'type' => 'type',
            'gradeID' => 'gradeID',
            'subjectID' => 'subjectID',
            'version' => 'version',
            'courseName' => 'courseName',
            'courseBrief' => 'courseBrief',
            'teacherID' => 'teacherID',
            'classID' => 'classID',
            'stuLimit' => 'stuLimit',
            'groupMemberLimit' => 'groupMemberLimit',
            'allMemLimit' => 'allMemLimit',
            'cost' => 0,
            'provience' => 'provience',
            'city' => 'city',
            'country' => 'country',
            'creatorID' => 'creatorID',
            'schoolProportion' => 0,
            'teacherProportion' => 0,
            'price' => 0,
            'isAgreement' => 0,
            'isShare' => 'isShare',
        );
    }

    /**
     * @param $u UserInfo
     */
    public function  parseUserInfo($u)
    {
        $this->city = $u->city;
        $this->country = $u->country;
        $this->provience = $u->provience;
        $this->gradeID = $u->gradeID;
        $this->classID = $u->userClass;
        $this->version = $u->textbookVersion;
    }

}