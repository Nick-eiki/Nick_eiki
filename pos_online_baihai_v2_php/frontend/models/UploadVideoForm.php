<?php
namespace frontend\models;

use yii\base\Model;

/**
 * Created by unizk.
 * User: ysd
 * Date: 14-11-5
 * Time: 上午10:11
 */
class UploadVideoForm extends Model
{
    public $type;       //资料类型
    public $provience;
    public $city;
    public $country;
    public $gradeID; //适用年级
    public $subjectID;  //科目
    public $version;  //版本
    public $courseName;  //课程视频名称
    public $teacherID;    //授课教师
    public $isCharge;   //是否收费 0表示不收费 1表示收费
    public $price;      //价格
    public $schoolProportion;//学校比例
    public $teacherProportion;//教师比例
    public $isAgreement;//是否达成协议 0未达成 1已达成
    public $introduce;  //课程简介
    public $imgUrl;     //广告图片（可为空）
    public $school;


    public function rules()
    {
        return [
            [['type', 'courseName', 'provience', 'city', 'country', 'gradeID', 'subjectID', 'version', 'teacherID', 'introduce', 'price', 'isCharge', 'isAgreement', 'schoolProportion', 'teacherProportion'], "required"],
            [['type', 'courseName', 'provience', 'city', 'country', 'gradeID', 'subjectID', 'version', 'teacherID', 'introduce', 'price', 'isCharge', 'isAgreement', 'schoolProportion', 'teacherProportion'], "safe"],
            //array("type,videoName,provience,city,county, gradeID, subjectID, versionID,introduce,price,teacher,isCharge,sProportion,isAgreement,isShare","safe","on"=>"search"),
        ];
    }

    public function attributeLabels()
    {
        return array(
            "type" => "type",
            "provience" => "provience",
            "city" => "city",
            "country" => "country",
            "subjectID" => "subjectID",
            "gradeID" => "gradeID",
            "version" => "version",
            "courseName" => "courseName",
            "introduce" => "introduce",
            "teacherID" => "teacherID",
            "isCharge" => "isCharge",
            "price" => "price",
            "schoolProportion" => "schoolProportion",
            "teacherProportion" => "teacherProportion",
            "isAgreement" => "isAgreement",
            "school" => "school",


        );
    }


}