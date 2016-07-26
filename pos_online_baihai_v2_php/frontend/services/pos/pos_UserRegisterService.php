<?php

namespace frontend\services\pos;
use ArrayObject;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-8-26
 * Time: 下午4:17
 */
class pos_UserRegisterService extends BaseService
{
    /**
     * @return ServiceJsonResult
     */
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/schUserRegister?wsdl");
    }


    /**
     * 3.2.2.注册账号（修改0408）
     * 接口地址    http://主机地址:端口号/ schoolService / userRegister?wsdl
     *   方法名    registerAccountPhone
     * @param $phoneReg     注册用号码
     * @param $passWd       密码
     * @param $type         账号类型 0：学生 1：老师
     * @param $token        安全保护措施
     * @return ServiceJsonResult
     * 返回的JSON示例：
     * {
     * "data": {}
     * "resCode": "000000",
     * "resMsg": "成功"
     * }
     *
     */
    public function registerAccountPhone($phoneReg, $passWd, $type, $trueName, $phone, $token)
    {
        $soapResult = $this->_soapClient->registerAccountPhone(array("phoneReg" => $phoneReg, 'passWd' => $passWd, 'type' => $type, 'trueName' => $trueName, 'phone' => $phone, 'token' => $token));
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.2.2.1.获取手机注册激活码（修改0408）
     * 接口地址    http://主机地址:端口号/ schoolService / userRegister?wsdl
     *   方法名    getActiviteTolkenPhone
     * @param $phoneReg     注册用号码
     * @param $token        安全保护措施
     * @return ServiceJsonResult
     * 返回的JSON示例：
     * 返回的JSONB：
        {
        "data":{
        "activiteToken":"XXXXXXXXXXXXXXXXXXXXX"
        },
        "resCode":"000000",
        "resMsg":"activiteToken[修改返回说明]激活邮箱代码已经返回"
        }
        返回的JSON示例：
        {
        "data": {}
        "resCode": "000000",
        "resMsg": "激活码已经获取"
        }
     *
     */

    public function getActiviteTolkenPhone($phoneReg)
    {
        $soapResult = $this->_soapClient->getActiviteTolkenPhone(array("phoneReg" => $phoneReg));
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.2.3.激活手机帐号（修改0408）
     * 接口地址    http://主机地址:端口号/ schoolService / userRegister?wsdl
     *   方法名    activatePhone
     * @param $phoneReg     注册用号码
     * @param $activiteToken   验证邮件中的激活代码
     * @param $token        安全保护措施
     * @return ServiceJsonResult
     * 返回的JSON示例：
     * 返回的JSONB：
            {
            "data":{},
            "resCode":"000001",
            "resMsg":"查询失败"
            }

            返回的JSON示例：
            {
            "data": {}
            "resCode": "000000",
            "resMsg": "激活码已经获取"
            }
     *
     */
    public function activatePhone($phoneReg,$activiteToken)
    {
        $soapResult = $this->_soapClient->activatePhone(array("phoneReg" => $phoneReg,'activiteToken'=>$activiteToken));
        return $this->soapResultToJsonResult($soapResult);
    }



    /**
     * 3.2.3.激活手机帐号（修改0408）
     * 接口地址    http://主机地址:端口号/ schoolService / userRegister?wsdl
     *   方法名    checkResetPasswdTolkenPhone
     * @param $phoneReg     注册用号码
     * @param $resetPasswdTolken   验证邮件中的激活代码
     * @param $token        安全保护措施
     * @return ServiceJsonResult
     * 返回的JSON示例：
     * 返回的JSONB：
     * 返回的JSONB：
     * {
     * "data":{
     * " phoneReg":""
     * },
     * "resCode":"000000",
     * "resMsg":""
     * }
     * 返回的JSON示例：
     * {
     * "data": {}
     * "resCode": "",
     * "resMsg": ""
     *
     * // 10001验证码错误！
     * // 10002验证码过期！
     * }
     *
     */
    public function checkResetPasswdTolkenPhone($phoneReg, $resetPasswdTolken)
    {
        $soapResult = $this->_soapClient->checkResetPasswdTolkenPhone(array("phoneReg" => $phoneReg, 'resetPasswdTolken' => $resetPasswdTolken));
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.3.1.获取重置密码验证码（修改）
     * 接口地址    http://主机地址:端口号/ schoolService / userRegister?wsdl
     *   方法名    getResetPasswdTolkenPhone
     * @param $phoneReg     注册用号码
     * @param $token        安全保护措施
     * @return ServiceJsonResult
     * 返回的JSON示例：
     * 返回的JSONB：
     * 返回的JSONB：
     * {
     * "data":{
     * " resetPasswdTolken":"XXXXXXXXXXXXXXXXXXXXX"
     * },
     * "resCode":"000000",
     * "resMsg":" resetPasswdTolken手机代码已经返回"
     * }
     * 返回的JSON示例：
     * {
     * "data": {}
     * "resCode": "",
     * "resMsg": ""
     * }
     *
     */
    public function getResetPasswdTolkenPhone($phoneReg)
    {
        $soapResult = $this->_soapClient->getResetPasswdTolkenPhone(array("phoneReg" => $phoneReg));
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $phoneReg             注册手机号码
     * @param $resetPasswdTolken    验证码
     * @param $newPassWd            新密码
     * 3.3.3.重置登录密码
     * 函数名resetPassWordPhone
     */
    public function   resetPassWordPhone($phoneReg, $resetPasswdTolken, $newPassWd)
    {
        $soapResult = $this->_soapClient->resetPassWordPhone(array("phoneReg" => $phoneReg, "resetPasswdTolken" => $resetPasswdTolken, "newPassWd" => $newPassWd));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        return $this->mapperJsonResult($json);
    }

    /**
     *  修改用户信息 通过模型
     * @param $model UserForm
     */
    public function  editTeacherUserInfoByModel($model, $teacherClassList = array(), $teacherGroupList = array())
    {
        return $this->editUserInfo($model->trueName, $model->phone, null, null, null, $model->schoolId, $model->department, $model->classId, $model->job,
            $model->stuID, $teacherClassList, $teacherGroupList, null, $model->userID, $model->textbookVersion, $model->subjectID,$model->classMemID);
    }

    /**
     *  修改用户信息 通过模型
     * @param $model UserForm
     */
    public function  editStudentUserInfoByModel($model, $teacherClassList = array(), $teacherGroupList = array())
    {
        return $this->editUserInfo($model->trueName, $model->phone, null, null, null, $model->schoolId, $model->department, $model->classId, $model->job,
            $model->stuID, $teacherClassList, $teacherGroupList, null, $model->userID, $model->textbookVersion, null,$model->classMemID);
    }

    /**
     * @param $userID             用户id
     * @param $phoneReg         注册手机
     * 3.2.11.修改注册手机号
     * 函数名editRegistPhone
     */
    public function   editRegistPhone($userID,$phoneReg)
    {
        $soapResult = $this->_soapClient->editRegistPhone(array("userID" => $userID,"phoneReg" => $phoneReg));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        return  $this->mapperJsonResult($json);

    }

    /** 完善用户信息
     * 请求参数    provience    所属地区 省
     * city    所属地区 地区
     * county    所属地区 区县
     * schoolID    学校id
     * department    学段
     * 20201    小学部
     * 20202    初中部
     * 20203    高中部
     * classID    班级（老师和学生）
     * identity    班内职务编码（学生用户）
     * 20101    班长
     * 20102    学习委员
     * 20103    其他
     * ￥    班内职务名称（学生用户）
     * stuID    学号 （学生用户）
     * teacherClassJSON    任教班级（老师用户）
     *subjectID 教师教授科目
     * JSON格式：
     * {
     * "data":[
     * {
     * "classID":"",//班级id
     * "identity":"",//角色 0：班主任 1：班级学生 2：授课老师
     * "subjectNumber":"",//教授科目，允许为空
     * }
     * ]
     * }
     * teachingGroupJSON    教研组信息（老师用户）
     * {
     * "data":[
     * {
     * "groupID":"",//教研组id
     * "identity":""//教研组职务
     * "identityName":""//教研组职务名称
     * }
     * ]
     * }
     * schoolIdentity    学校职务（老师用户）
     * userID    当前用户id
     * token
     * 应答    失败    返回的JSONB：
     * {
     * "data":{},
     * "resCode":"000001",
     * "resMsg":""
     * }
     *
     * 成功    返回的JSON示例：
     * {
     * "data": {}
     * "resCode": "000000",
     * "resMsg": "”
     * }
     * @param $provience
     * @param $city
     * @param $county
     * @param $schoolID
     * @param $department
     * @param $classID
     * @param $identity
     * @param $stuID
     * @param $teacherClassArray
     * @param $teachingGroupArray
     * @param $schoolIdentity
     * @param $userID
     * @param $subjectID
     */
    public function editUserInfo($trueName, $phone, $provience, $city, $county, $schoolID, $department, $classID, $identity, $stuID, $teacherClassArray, $teachingGroupArray, $schoolIdentity, $userID, $textbookVersion,$subjectID,$classMemID)
    {
        $param = [
            'trueName' => $trueName,
            'phone' => $phone,
            'schoolID' => $schoolID,
            'department' => $department,
            'classID' => $classID,
            'identity' => $identity,
            'stuID' => $stuID,
            'teacherClassJSON' => empty($teacherClassArray) ? '' : json_encode(['data' => array_values($teacherClassArray)]),
            'teachingGroupJSON' => empty($teachingGroupArray) ? '' : json_encode(['data' => [$teachingGroupArray]]),
            'userID' => $userID,
            'textbookVersion' => $textbookVersion,
            'subjectID'=>$subjectID,
            'classMemID'=>$classMemID
        ];


        $soapResult = $this->_soapClient->editUserInfo($param);
        return $this->soapResultToJsonResult($soapResult);

    }

    /**
     * @param $registerForm
     * @return ServiceJsonResult
     * 注册用户
     * //     */
//    public function registerAccount($registerForm){
//        if(!empty($registerForm->studentName)){
//            $array=array(
//                "email"=>$registerForm->email,
//                "passWd"=>$registerForm->passwd,
//                "type"=>$registerForm->type,
//                "phone"=>$registerForm->parentMobile,
//                "trueName"=>$registerForm->studentName,
//            );
//        }
//        else{
//            $array=array(
//                "email"=>$registerForm->email,
//                "passWd"=>$registerForm->passwd,
//                "type"=>$registerForm->type,
//                "phone"=>$registerForm->teacherMobile,
//                "trueName"=>$registerForm->teacherName,
//            );
//        }
//        $soapResult = $this->_soapClient->registerAccount($array);
//        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
//        $json = json_decode($jsonStr);
//        return $this->mapperJsonResult($json);
//    }

    /**
     * 高 2014.8.26
     * 2.1.3.获取邮箱注册激活码
     *  接口地址    http://主机地址:端口号/ schoolService / userRegister?wsdl
     * 方法名    getActiviteTolken
     * @param $email        邮箱地址
     * @param $token            安全保护措施
     * @return ServiceJsonResult
     * 返回的JSON示例：
     * {
     * "data": {}
     * "resCode": "000000",
     * "resMsg": "激活码已经获取”
     * }
     */
    public function getActiviteTolken($email)
    {
        $soapResult = $this->_soapClient->getActiviteTolken(array("email" => $email));
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $email
     * @return bool
     * 判断邮箱是否被注册
     */
    public function   emailIsExist($email)
    {
        $soapResult = $this->_soapClient->emailIsExist(array("email" => $email));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        if ($this->mapperJsonResult($json)->resCode == "000000") {

            return false;
        }
        return true;
    }

    /**
     * @param $phoneReg
     * @return bool
     * 判断手机号是否被注册
     */
    public function   phoneIsExist($phoneReg)
    {
        $soapResult = $this->_soapClient->phoneIsExist(array("phoneReg" => $phoneReg));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        if ($this->mapperJsonResult($json)->resCode == "000000") {
            return false;
        }
        return true;
    }


    /**
     * @param $email
     * @param $afterEmail
     * @return bool
     * 修改注册邮箱
     */
    function updateEmail($email, $afterEmail, $passWd)
    {
        $soapResult = $this->_soapClient->updateEmail(array("email" => $email, "afterEmail" => $afterEmail, "passWd" => $passWd));
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 激活邮箱
     * @param $email
     * @return mixed
     */
    public function   activateMail($email, $activiteToken)
    {
        $soapResult = $this->_soapClient->activateMail(array("email" => $email, 'activiteToken' => $activiteToken));
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $email
     * @param $pssWd
     * @param $afterPssWd
     * @return ServiceJsonResult
     * 修改密码（已被删除？）
     */
    public function updatePassWord($email, $pssWd, $afterPssWd)
    {
        $soapResult = $this->_soapClient->updatePassWord(array("email" => $email, 'pssWd' => $pssWd, "afterPssWd" => $afterPssWd));
        return $this->soapResultToJsonResult($soapResult);
    }

	/**
	 * 新修改密码
	 * @param $phoneReg
	 * @param $pssWd
	 * @param $afterPssWd
	 * @return ServiceJsonResult
	 *
	 */
	public function updatePassWordPhone($phoneReg, $pssWd, $afterPssWd)
	{
		$soapResult = $this->_soapClient->updatePassWordPhone(array("phoneReg" => $phoneReg, 'pssWd' => $pssWd, "afterPssWd" => $afterPssWd));

		return $this->soapResultToJsonResult($soapResult);
	}

    /**
     * 3.3.1.获取重置密码验证码
     * @param $email
     * @return string
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  getResetPasswdTolken($email)
    {
        $soapResult = $this->_soapClient->getResetPasswdTolken(array("email" => $email));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data->resetPasswdTolken;
        }
        return false;
    }

    /**
     * 3.3.2.检查验证码
     * @param $email
     * @return string
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  checkResetPasswdTolken($resetPasswdTolken)
    {
        $soapResult = $this->_soapClient->checkResetPasswdTolken(array("resetPasswdTolken" => $resetPasswdTolken));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data->email;
        }
        return '';
    }

    /**
     * 3.3.3.重置登录密码
     * resetPasswdTolken    验证码
     * newPassWd    新密码
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  resetPassWord($resetPasswdTolken, $newPassWd)
    {
        $soapResult = $this->_soapClient->resetPassWord(array("resetPasswdTolken" => $resetPasswdTolken, 'newPassWd' => $newPassWd));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return true;
        }
        return false;
    }

    public function  getXmppPasswd($userId)
    {
        $soapResult = $this->_soapClient->getXmppPasswd(array("userID" => $userId));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            if($result->data==null){
                return false;
            }
            $arr = ['pw' => $result->data->xmppPasswd, 'id' => $result->data->userId];
            return $arr;
        }
        return false;
    }


}