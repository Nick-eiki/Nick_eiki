<?php
namespace frontend\models;

use yii\base\Model;

/**
 * This is the model class for table "{{Student}}".
 *
 * The followings are the available columns in table '{{Student}}':
 * @property integer $userID
 * @property integer $gradeID
 * @property string $userName
 * @property string $nickName
 * @property string $sex
 * @property string $birthday
 * @property string $passwd
 * @property string $salt
 * @property string $provience
 * @property string $city
 * @property string $county
 * @property string $email
 * @property string $phone
 * @property string $lastLoginTime
 * @property string $createTime
 * @property string $updateTime
 * @property integer $status
 * @property string $faceIcon
 * @property string $bankCardNo
 * @property string $initBankName
 * @property string $zhifubaoID
 * @property string $brief
 * @property string $parentsName
 * @property string $parentsTel
 * @property string $strongPoint
 * @property string $interest
 * @property string $invitationCode
 * @property string $trueName
 * @property string $goodAtCourse
 * @property string $hobby
 */
class StudentForm extends Model
{

    public $userID;
    public $gradeID;
    public $userName;
    public $nickName;
    public $sex;
    public $birthday;
    public $passwd;
    public $salt;
    public $provience;
    public $city;
    public $county;
    public $email;
    public $phone;
    public $lastLoginTime;
    public $createTime;
    public $updateTime;
    public $status;
    public $faceIcon;
    public $bankCardNo;
    public $initBankName;
    public $zhifubaoID;
    public $brief;
    public $parentsName;
    public $parentsTel;
    public $schoolName;
    public $strongPoint;
    public $interest;
    public $invitationCode;
    public $trueName;
    public $goodAtCourse;
    public $weakAtCourse;
    public $hobby;
    public $studySection;
    public $position;
    public $studentID;
    public $class;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['phone'], 'match', 'pattern' => '/^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/', 'message' => '手机号格式有误'],
            [['gradeID', 'status'], 'numerical', 'integerOnly' => true],
            [['schoolName', 'userName', 'nickName', 'passwd', 'salt', 'provience', 'city', 'county', 'bankCardNo', 'zhifubaoID', 'weakAtCourse', 'goodAtCourse'], 'length', 'max' => 50],
            [['sex'], 'length', 'max' => 10],
            [['email'], 'length', 'max' => 30],
            [['phone', 'parentsName', 'parentsTel', 'invitationCode', 'trueName'], 'length', 'max' => 20],
            [['faceIcon', 'initBankName'], 'length', 'max' => 200],
            [['brief', 'strongPoint', 'interest'], 'length', 'max' => 500],
            [['hobby'], 'length', 'max' => 100],
            [['birthday', 'lastLoginTime', 'createTime', 'updateTime'], 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            [['userID', 'gradeID', 'userName', 'nickName', 'sex', 'birthday', 'passwd', 'salt', 'provience', 'city', 'county', 'email', 'phone', 'lastLoginTime', 'createTime', 'updateTime', 'status', 'faceIcon', 'bankCardNo', 'initBankName', 'zhifubaoID', 'brief', 'parentsName', 'parentsTel', 'strongPoint', 'interest', 'invitationCode', 'trueName', 'goodAtCourse', 'hobby'], 'safe', 'on' => 'search'],
        ];
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'userID' => 'User',
            'gradeID' => 'Grade',
            'userName' => 'User Name',
            'nickName' => 'Nick Name',
            'sex' => 'Sex',
            'birthday' => 'Birthday',
            'passwd' => '密码',
            'salt' => 'Salt',
            'provience' => 'Provience',
            'city' => 'City',
            'county' => 'County',
            'email' => '邮箱',
            'phone' => 'Phone',
            'lastLoginTime' => 'Last Login Time',
            'createTime' => 'Create Time',
            'updateTime' => 'Update Time',
            'status' => 'Status',
            'faceIcon' => 'Face Icon',
            'bankCardNo' => 'Bank Card No',
            'initBankName' => 'Init Bank Name',
            'zhifubaoID' => 'Zhifubao',
            'brief' => 'Brief',
            'parentsName' => 'Parents Name',
            'parentsTel' => 'Parents Tel',
            'strongPoint' => 'Strong Point',
            'interest' => 'Interest',
            'invitationCode' => 'Invitation Code',
            'trueName' => 'True Name',
            'goodAtCourse' => 'Good At Course',
            'hobby' => 'Hobby',
        );
    }


}
