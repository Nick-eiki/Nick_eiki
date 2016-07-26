<?php

namespace frontend\components;

use common\models\pos\SeUserinfo;
use frontend\services\pos\pos_MessageSentService;
use stdClass;
use Yii;
use yii\base\NotSupportedException;

class User extends SeUserinfo implements \yii\web\IdentityInterface
{

    const STATUS_DEFAULT = 0;
    const STATUS_ACTIVE = 10;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['userID' => $id, 'type' => [0, 1]]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['phoneReg' => $username, 'type' => [0, 1]]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return "";
    }


    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        // return $this->getAuthKey() === $authKey;
        return true;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        // return Yii::$app->security->validatePassword($password, $this->password_hash);
        return strtoupper(md5($password)) == strtoupper($this->passWd);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    function  getEmail()
    {

        return $this->email;
    }

    /**
     *  获取学校ID
     * @return string
     */
    function  getSchoolId()
    {
        return $this->schoolID;
    }


    /**
     * Get the Model from the session of Current User
     * @return SeUserinfo
     */
    public function getModel($cache = true)
    {

        return $this;
    }


    /** 获取指定用户信息
     * @param $id
     * @return \common\models\pos\SeUserinfo
     */
    public function getUserModel($id)
    {
        return $this->getUserInfo($id);
    }


    /**
     *  获取学校
     * @return array
     */
    function   getSchoolInfo()
    {

        $user = $this->getModel();
        if ($user != null) {
            $result = new stdClass();
            $result->schoolID = $user->schoolID;
            $result->schoolName = $user->getSchoolName();
            return $result;
        }
        return null;
    }


    /**
     * 用户是否该学校中
     * @param $schoolId
     * @return null|UserClass
     */
    function  getIsSchool($schoolId)
    {
        $id = $this->getSchoolId();
        if (empty($id)) {
            return false;
        };
        return $id == $schoolId;
    }

    /**
     * 教师是否是该学校
     * @param $schoolId
     * @return bool
     */
    function  getTeacherInSchool($schoolId)
    {
        return $this->isTeacher() && $this->getIsSchool($schoolId);
    }

    /**
     * 学生是否该学校
     * @param $schoolId
     * @return bool
     */
    function  getStudentInSchool($schoolId)
    {
        return $this->isStudent() && $this->getIsSchool($schoolId);
    }


    /**
     *  获取用户的
     * @return int
     */
    public function  getMessageCount()
    {
        $data = new pos_MessageSentService();
        return $data->unReadNum(user()->id);
    }

    /** 获取用户信息
     * @param $userId
     * @return \common\models\pos\SeUserinfo
     */
    public function  getUserInfo($userId)
    {
        return SeUserinfo::findOne($userId);
    }

    /**
     * 获取用户信息 cache
     * @param $userId
     * @return \common\models\pos\SeUserinfo
     */
    public function  getUserInfoCache($userId)
    {
        return $this->getUserInfo($userId);
    }

    /**
     * 获取登陆账号
     * @return string
     */
    public function  getPhoneReg()
    {
        $user = $this->getModel();
        if ($user != null) {
            return $user->phoneReg;
        }
        return '';
    }

    /**
     * 获取绑定手机号
     * @return string
     */
    public function getBindphone()
    {
        $user = $this->getModel();
        if ($user != null) {
            return $user->bindphone;
        }
        return '';
    }
}
