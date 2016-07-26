<?php
namespace frontend\models;

use yii\base\Model;

/**
 * This is the model class for table "{{tearcher}}".
 *
 * The followings are the available columns in table '{{tearcher}}':
 * @property integer $teacherID
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
 * @property string $trueName
 * @property string $invitationCode
 * @property string $jobTitle
 * @property string $teachingPerformance
 * @property string $awards
 *
 * The followings are the available model relations:
 * @property Bullyclass[] $bullyclasses
 * @property Classmaintain[] $classmaintains
 * @property Schoolbindteacher[] $schoolbindteachers
 * @property Teachinggrade[] $teachinggrades
 */
class TeacherForm extends Model
{
    public $awards;
    public $teacherID;
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
    public $trueName;
    public $invitationCode;
    public $jobTitle;
    public $teachingPerformance;
    public $goodAtGrade;
    public $teachCourse;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{tearcher}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['phone'], 'match', 'pattern' => '/^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/', 'message' => '手机号格式有误'],
            [['status'], 'numerical', 'integerOnly' => true],
            [['userName', 'nickName', 'passwd', 'salt', 'provience', 'city', 'county', 'bankCardNo', 'zhifubaoID'], 'length', 'max' => 50],
            [['sex'], 'length', 'max' => 10],
            [['email'], 'length', 'max' => 30],
            [['phone', 'trueName', 'invitationCode', 'jobTitle'], 'length', 'max' => 20],
            [['faceIcon', 'initBankName', 'teachingPerformance', 'awards'], 'length', 'max' => 200],
            [['brief'], 'length', 'max' => 500],
            [['teachCourse', 'goodAtGrade', 'birthday', 'lastLoginTime', 'createTime', 'updateTime'], 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            [['teacherID', 'userName', 'nickName', 'sex', 'birthday', 'passwd', 'salt', 'provience', 'city', 'county', 'email', 'phone', 'lastLoginTime', 'createTime', 'updateTime', 'status', 'faceIcon', 'bankCardNo', 'initBankName', 'zhifubaoID', 'brief', 'trueName', 'invitationCode', 'jobTitle', 'teachingPerformance', 'awards'], 'safe', 'on' => 'search'],
        ];
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'teacherID' => 'TeacherForm',
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
            'trueName' => 'True Name',
            'invitationCode' => 'Invitation Code',
            'jobTitle' => 'Job Title',
            'teachingPerformance' => 'Teaching Performance',
            'awards' => 'Awards',
        );
    }
}
