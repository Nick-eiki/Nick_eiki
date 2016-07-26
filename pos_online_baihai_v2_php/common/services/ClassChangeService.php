<?php
namespace common\services;
use Httpful\Mime;
use Httpful\Request;
use Yii;

/**
 * Created by PhpStorm.
 * User: aaa
 * Date: 2016/7/22
 * Time: 17:09
 */
  class ClassChangeService
  {
      private $uri = null;

      function __construct(){
          $this->uri = Yii::$app->params['keyWords'];
      }



      /**
       * 封班
       * @param  integer $schoolId 学校ID
       * @param  string $classIds 班级ID(以逗号链接的字符串)
       * @return mixed
       * @throws \Httpful\Exception\ConnectionErrorException
       */
      public  function CloseClass($schoolId,$classIds){
          $result= Request::post($this->uri."/v1/class-closes")
              ->body(http_build_query(["schoolId"=>$schoolId,"classId"=>$classIds]))
              ->sendsType(Mime::FORM)
              ->send();
          return    $result->body;
      }


      /**
       * 升级
       * @param integer $schoolId 学校ID
       * @param integer $departmentId 学段ID
       * @return mixed
       * @throws \Httpful\Exception\ConnectionErrorException
       */
      public  function SchoolUpgrade($schoolId, $departmentId){
          $result= Request::post($this->uri."/v1/school-upgrades")
              ->body(http_build_query(["schoolId"=>$schoolId,"department"=>$departmentId]))
              ->sendsType(Mime::FORM)
              ->send();
          return    $result->body;
      }



      /**
       * 修改身份
       * @param integer $userID 用户ID
       * @param integer $classID  班级ID
       * @param integer $identityID 身份ID
       * @return mixed
       * @throws \Httpful\Exception\ConnectionErrorException
       */
      public  function ChangeIdentity($userID,$classID,$identityID){
          $result= Request::post($this->uri."/v1/identity-changes")
              ->body(http_build_query(["userID" => $userID,"classID" => $classID,'identityID' => $identityID]))
              ->sendsType(Mime::FORM)
              ->send();
          return    $result->body;
      }



      /**
       * 创建班级
       * @param integer $schoolID 学校ID
       * @param integer $gradeID 年级ID
       * @param integer $department 学段ID
       * @param integer $classNumber 第几班
       * @param integer $joinYear 年份
       * @param string $className 班级名称
       * @param integer $creatorID 创建人ID
       * @return mixed
       * @throws \Httpful\Exception\ConnectionErrorException
       */
      public  function CreateClass($schoolID,$gradeID,$department,$classNumber,$joinYear,$className,$creatorID){
          $result= Request::post($this->uri."/v1/create-classes")
              ->body(http_build_query(["schoolID" => $schoolID,"gradeID" => $gradeID,'department' => $department,'classNumber' => $classNumber,
                  'joinYear' => $joinYear,'className' => $className,'creatorID' => $creatorID]))
              ->sendsType(Mime::FORM)
              ->send();
          return    $result->body;
      }



      /**
       * 老师调班
       * @param integer $userID 用户ID
       * @param integer $classID 班级ID
       * @return mixed
       * @throws \Httpful\Exception\ConnectionErrorException
       */
      public  function TeacherChangeClass($userID,$classID){
          $result= Request::post($this->uri."/v1/teacher-class-changes")
              ->body(http_build_query(["userID" => $userID,"classID" => $classID]))
              ->sendsType(Mime::FORM)
              ->send();
          return    $result->body;
      }




      /**
       * 添加老师
       * @param integer $userId 用户ID
       * @param integer $classId 班级ID
       * @return mixed
       * @throws \Httpful\Exception\ConnectionErrorException
       */
      public  function AddTeacher($userId,$classId){
          $result= Request::post($this->uri."/v1/add-teachers")
              ->body(http_build_query(["userId" => $userId,"classId" => $classId]))
              ->sendsType(Mime::FORM)
              ->send();
          return    $result->body;
      }



      /**
       * 添加学生
       * @param integer $schoolID 学校ID
       * @param integer $classID  班级ID
       * @param integer $stuID 学号
       * @param string $trueName 真实名称
       * @param string $phone 手机号
       * @param integer $sex 性别
       * @param string $phoneReg 账号
       * @param string $parentsName 家长姓名
       * @param integer $bindphone 家长手机号
       * @return mixed
       * @throws \Httpful\Exception\ConnectionErrorException
       */
      public  function AddStudent($schoolID,$classID,$stuID,$trueName,$phone,$sex,$phoneReg,$parentsName,$bindphone,$depratment){
          $result= Request::post($this->uri."/v1/add-students")
              ->body(http_build_query(["schoolID" => $schoolID,"classID" => $classID,'stuID' =>$stuID,'trueName' => $trueName,
                  'phone' => $phone,'sex' => $sex,'phoneReg' => $phoneReg,'parentsName' => $parentsName,'bindphone' => $bindphone,'department'=>$depratment]))
              ->sendsType(Mime::FORM)
              ->send();
          return    $result->body;
      }
      /**
       * 添加学生
       * @param integer $schoolID 学校ID
       * @param integer $classID  班级ID
       * @param integer $stuID  学号
       * @param string $trueName 真是名称
       * @param string $phone 手机号
       * @param integer $sex  性别
       * @param string $phoneReg 账号
       * @param string $parentsName 家长姓名
       * @param  integer $bindphone 家长手机号
       * @return mixed
       * @throws \Httpful\Exception\ConnectionErrorException
       */
      public  function ModifyStudent($schoolID,$classID,$stuID,$trueName,$phone,$sex,$phoneReg,$parentsName,$bindphone,$department){

          $result= Request::post($this->uri."/v1/modify-students")
              ->body(http_build_query(["schoolID" => $schoolID,"classID" => $classID,'stuID' =>$stuID,'trueName' => $trueName,
                  'phone' => $phone,'sex' => $sex,'phoneReg' => $phoneReg,'parentsName' => $parentsName,'bindphone' => $bindphone,'department'=>$department]))
              ->sendsType(Mime::FORM)
              ->send();
          return    $result->body;
      }




      /**
       * 学生调班
       * @param integer $schoolId 学校ID
       * @param integer $departmentId  学段ID
       * @param integer $classId 班级ID
       * @param integer $userId 用户ID
       * @return mixed
       * @throws \Httpful\Exception\ConnectionErrorException
       */
      public  function ChangeStudentClass($schoolId,$departmentId,$classId,$userId){
          $result= Request::post($this->uri."/v1/student-class-changes")
              ->body(http_build_query(["schoolId" => $schoolId,"departmentId" => $departmentId,'classId' =>$classId,'userId' => $userId]))
              ->sendsType(Mime::FORM)
              ->send();
          return    $result->body;
      }



      /**
       * 移出班级
       * @param integer $userID 用户ID
       * @param integer $classID 班级ID
       * @return mixed
       * @throws \Httpful\Exception\ConnectionErrorException
       */
      public  function DelUserClass($userID,$classID){
          $result= Request::post($this->uri."/v1/out-classes")
              ->body(http_build_query(["userID" => $userID,'classID' => $classID]))
              ->sendsType(Mime::FORM)
              ->send();
          return    $result->body;
      }



      /**
       * 移出学校
       * @param integer $userID 用户ID
       * @param integer $schoolID 学校ID
       * @return mixed
       * @throws \Httpful\Exception\ConnectionErrorException
       */
      public  function DelUserSchool($userID,$schoolID){
          $result= Request::post($this->uri."/v1/out-schools")
              ->body(http_build_query(["userID" => $userID,'schoolID' => $schoolID]))
              ->sendsType(Mime::FORM)
              ->send();
          return    $result->body;
      }


  }