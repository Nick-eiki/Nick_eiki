<?php
namespace frontend\models;

use frontend\services\pos\pos_UserRegisterService;
use yii\base\Model;

class RegisterForm extends Model
{

    /**
     * @var
     */
    public $passwd;
    /**
     * @var
     */
    public $username;
    /**
     * @var
     */
    public $repasswd;
    /**
     * 默认老师
     * @var int
     */
    public $type = 1;
    /**
     * @var
     */
    public $oldpasswd;

    /**
     * @var
     */
    public $trueName;
    /**
     * @var
     */
    public $mobile;
    /**
     * @var
     */
    public $afterEmail;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [

            [['username', 'passwd', 'repasswd', 'type', 'trueName'], 'required', 'message' => '此处不能为空'],

            [['passwd'], 'string', 'min' => 6, 'max' => 20, 'message' => '请输入6-20位字母和数字'],

            [['mobile'], 'checkusername'],

            [['type'], 'checkType'],

            [['repasswd'], 'compare', 'compareAttribute' => 'passwd', 'message' => '两次密码输入不相同'],
            [['passwd', 'type', 'username', 'mobile', 'trueName', 'oldpasswd', 'afterEmail'], "safe"]
        ];
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'passwd' => '密码',
            'repasswd' => '确认密码',
            'username' => '邮箱',
            'type' => '身份',
            'trueName' => '姓名',
            'mobile' => '手机号',


        );
    }

    /**
     * 验证注册手机号是否已经被使用
     * @param $attribute
     * @param $params
     */
    public function checkusername($attribute, $params)
    {
        $username = $this->username;
        $student = new pos_UserRegisterService();
        if ($student->phoneIsExist($username)) {
            $this->addError($attribute, '此手机号已被其他人使用!');
        }
    }

    /**
     * 验证家长手机号（只有学生用户时 家长手机号不能为空）
     */
    public function checkMobile($attribute, $params)
    {
        $mobile = $this->mobile;
        $type = $this->type;
        if ($type == '0' && empty($mobile)) {
            $this->addError($attribute, '家长手机号不能为空!');
        }
    }

    /*
     *
     */
    public function checkType($attribute, $params)
    {
        $type = $this->type;
        if ($type != '0' && $type != '1') {
            $this->addError($attribute, '请选择身份!');
        }
    }


}
