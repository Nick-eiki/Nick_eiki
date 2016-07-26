<?php

namespace common\models\pos;

use common\helper\DateTimeHelper;
use frontend\components\UserClass;
use frontend\components\UserGroup;
use frontend\components\WebDataKey;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "se_userinfo".
 *
 * @property integer $userID
 * @property string $phoneReg
 * @property string $email
 * @property string $passWd
 * @property string $trueName
 * @property string $parentsName
 * @property string $phone
 * @property string $schoolID
 * @property string $status1
 * @property string $status2
 * @property string $createTime
 * @property string $updateTime
 * @property string $isDelete
 * @property string $type
 * @property string $provience
 * @property string $city
 * @property string $country
 * @property string $introduce
 * @property string $schoolidenName
 * @property string $department
 * @property string $weakAtCourse
 * @property string $manifesto
 * @property string $strongPoint
 * @property string $honours
 * @property string $headImgUrl
 * @property string $identityOfTrainingScholl
 * @property string $trainingSchoolID
 * @property string $schooliden
 * @property string $textbookVersion
 * @property string $disabled
 * @property string $resetPasswdToken
 * @property string $resetPasswdTm
 * @property string $subjectID
 * @property string $bindphone
 * @property string $sex
 */
class SeUserinfo extends PosActiveRecord
{
    /**
     * 班主任
     */
    const  TEACHER_HEAD='20401';
    /**
     * 任课老师
     */
    const  TEACHER_COURSE='20402';
    /**
     * 学生
     */
    const  STUDENT = '20403';
    /**
     * 班海学校
     */
    const  SCHOOL_ID = 1000;
    /**
     * 是否删除  0 未删除  1 已删除
     */
    const  IS_DELETE = 0;
    public static function tableName()
    {
        return 'se_userinfo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_school');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'phoneReg'], 'required'],
            [['userID'], 'integer'],
            [['phoneReg', 'email'], 'string', 'max' => 200],
            [['passWd'], 'string', 'max' => 36],
            [['trueName', 'parentsName', 'provience', 'city', 'country', 'department'], 'string', 'max' => 50],
            [['phone', 'schoolID', 'createTime', 'updateTime', 'identityOfTrainingScholl', 'trainingSchoolID', 'schooliden', 'resetPasswdTm', 'subjectID'], 'string', 'max' => 20],
            [['status1', 'status2', 'isDelete', 'disabled'], 'string', 'max' => 2],
            [['type'], 'string', 'max' => 10],
            [['introduce', 'weakAtCourse', 'manifesto', 'strongPoint', 'honours', 'headImgUrl', 'resetPasswdToken'], 'string', 'max' => 500],
            [['schoolidenName'], 'string', 'max' => 30],
            [['textbookVersion'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userID' => '用户id',
            'phoneReg' => 'Phone Reg',
            'email' => '邮箱',
            'passWd' => '密码',
            'trueName' => '真实姓名',
            'parentsName' => '父母姓名',
            'phone' => '手机',
            'schoolID' => '学校id',
            'status1' => '是否填写完注册信息，0表示未写完，1表示已写完，默认：0',
            'status2' => '是否初步完成个人信息，0表示未写完，1表示已写完，默认：0',
            'createTime' => '创建时间',
            'updateTime' => '信息最后一次修改时间',
            'isDelete' => '是否已删除，0表示未删除，1表示已删除，默认：0',
            'type' => '登录人信息，0表示学生，1表示老师',
            'provience' => '省，直辖市',
            'city' => '城市',
            'country' => '区县',
            'introduce' => '个人简介',
            'schoolidenName' => '老师在学校的职务名称',
            'department' => '学段/学部(见数据字典表)',
            'weakAtCourse' => '薄弱科目。薄弱科目编码，科目间使用逗号分隔',
            'manifesto' => '宣言，宣言描述',
            'strongPoint' => '特长描述',
            'honours' => '我的荣誉，使用逗号分隔',
            'headImgUrl' => '个人头像url',
            'identityOfTrainingScholl' => '教培机构内身份，0：表示学生，1：表示老师',
            'trainingSchoolID' => '培训学校id',
            'schooliden' => '学校职工身份/学校职务,见学校职务编码',
            'textbookVersion' => '教材版本 来自数据字典',
            'disabled' => '是否已经禁用 0：未禁用/激活/解禁/审核通过  1：已经禁用',
            'resetPasswdToken' => '重置密码验证',
            'resetPasswdTm' => 'Reset Passwd Tm',
            'subjectID' => '教师教授科目',
        ];
    }

    /**
     * /todo 老方法移值
     * 获取教研组
     */
    public function  getGroupInfo()
    {
        $groupMemberList = SeGroupMembers::find()->active()->where(['teacherID' => $this->userID])->all();
        $items = array();
        $groupArray= ArrayHelper::getColumn($groupMemberList,'groupID');
        $seTeachingGroupList= SeTeachingGroup::find()->where(['ID' => $groupArray])->all();
        $findOneTeachingGroupFunction=function($seTeachingGroupLst, $groupId){
            foreach($seTeachingGroupLst as $i){
                if($i->ID==$groupId){
                    return $i;
                }
            }
            return null;
        };
        foreach ($groupMemberList as $key => $item) {
            $seTeachingGroupModel=  $findOneTeachingGroupFunction($seTeachingGroupList,$item->groupID);
            if($seTeachingGroupModel!=null){
                $group = new  UserGroup();
                $group->groupID = $item->groupID;
                $group->groupName = $seTeachingGroupModel->groupName;
                $group->identity = $item->identity;
                $items[] = $group;
            }
        }
        return $items;

    }


    /**
     *  /todo 老方法移值
     *  所在班级
     */
    function  getClassInfo()
    {

        $seClassMembers = SeClassMembers::find()->active()->where(['userID' => $this->userID])->all();
        $items = array();
        foreach ($seClassMembers as $key => $item) {
            $teachClass = new  UserClass();
            $teachClass->classID = $item->classID;
            $teachClass->identity = $item->identity;

            $seClassSubject = SeClassSubject::find()->where(['teacherID' => $this->userID, 'classID' => $item->classID])->one();
            $seClassModel = SeClass::find()->where(['classID' => $item->classID])->one();


            $teachClass->subjectNumber = ArrayHelper::getValue($seClassSubject, 'subjectNumber');
            $teachClass->className = $seClassModel->className;
            $teachClass->joinYear = $seClassModel->joinYear;
            $teachClass->classNumber = $seClassModel->classNumber;
            $items[] = $teachClass;
        }

        return $items;
    }

    /**
     * 获取用户所在班级缓存
     * @return array|mixed
     */
    function  getClassInfoCache(){
        $cache = Yii::$app->cache;
        $key = WebDataKey::CLASS_INFO_DATA_BY_USERID_KEY . $this->userID;
        $data = $cache->get($key);
        if($data==false){
            $data=$this->getClassInfo();
            if($data!=null){
                $cache->set($key, $data, 600);
            }
        }
        return $data;
    }

    /**
     * 用户所在班级及信息
     * @return array
     */
    function  getClassAndMember()
    {

        $seClassMembers = SeClassMembers::find()->active()->where(['userID' => $this->userID])->all();


        return $seClassMembers;
    }


    /**
     * 获取用户所在班级中相关信息
     * @return array|SeClassMembers[]
     */
    function  getUserInfoInClass()
    {
        $seClassMembers = SeClassMembers::find()->where(['userID' => $this->userID])->all();
        $items = [];
        foreach ($seClassMembers as $item) {

            $seClassSubject = SeClassSubject::find()->where(['teacherID' => $this->userID])->one();

            $items[] = ['classID' => $item->classID,
                'identity' => $item->identity,
                'stuID' => $item->stuID,
                'job' => $item->job,
                'gradeID' => ArrayHelper::getValue($item->seClass, 'gradeID'),
                'subjectNumber' => ArrayHelper::getValue($seClassSubject, 'subjectNumber')
            ];

        }
        return $items;
    }


    /**
     *  /todo 老方法移值
     * 是否在包括在班级中
     *
     */
    function  getInClassInfo($classId)
    {
       return  SeClassMembers::find()->where(['classID' => $classId])->andWhere(['userID'=>$this->userID])->exists();
    }


    /**
     * /todo 老方法移值
     * 用户是否该组中
     *
     */
    function  getInGroupInfo($groupId)
    {
        /** @var $items UserGroup[] */
        $items = $this->getGroupInfo();
        foreach ($items as $key => $item) {
            if ($item->groupID == $groupId) {
                return $item;
            }
        }
        return null;
    }

    /**
     * 获取学校模型
     */
    function  getSchoolName()
    {

        return SeSchoolInfo::find()->findNameById($this->userID);


    }

    /**
     *  是老师
     * @return bool|int
     */
    function isTeacher()
    {

        return $this->type == 1;

    }


    /**
     * /todo 老方法移值
     * 学用所在的班级
     * @return array|SeClassMembers[]
     */
    function  getUserClassGroup()
    {

        return \common\models\pos\SeClassMembers::find()->where(['userID' => $this->userID])->all();

    }


    /**
     * 是学生
     * @return bool|int
     */
    function isStudent()
    {
        return $this->type == 0;
    }


    /**
     * 获取头像
     */
    function  getFaceIcon()
    {
        $faceIcon = "/pub/images/tx.jpg";
        if ($this->headImgUrl != null && trim($this->headImgUrl) != '') {
            return $this->headImgUrl;
        }

        return $faceIcon;
    }


    /**
     * @return mixed
     * 获取真实姓名
     */
    function getTrueName()
    {
        return $this->trueName;
    }


    /**
     * @inheritdoc
     * @return SeUserinfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeUserinfoQuery(get_called_class());
    }

    /**
     * 获取单个用户信息
     * @param $userId
     * @param $schoolId
     * @return array|SeUserinfo|null
     */
    public static function getOne($userId,$schoolId)
    {
        return self::find()->where(['userID' => $userId, 'schoolID' => $schoolId, 'isDelete' => self::IS_DELETE])->one();
    }

    /**
     * 获取 se_userControl 信息
     * @return \yii\db\ActiveQuery
     */
    public function getSeUserControl()
    {
        return $this->hasMany(SeUserControl::className(),["userID"=>"userID"]);
    }

    /**
     * 获取 se_classMembers
     * @return \yii\db\ActiveQuery
     */
    public function getSeClassMembers()
    {
        return $this->hasMany(SeClassMembers::className(),["userID"=>"userID"]);
    }

    /**
     * 学生调班时班级信息的修改
     * @param $classID
     */
    public function updateClassMember($classID)
    {

        $classMember = SeClassMembers::find()->where(['userID' => $this->userID, 'identity'=>20403])->one();

        $classMember->classID = $classID;
        $classMember->save(false);
    }

    /**
     * 获取 se_groupMembers
     * @return \yii\db\ActiveQuery
     */
    public function getSeGroupMembers()
    {
        return $this->hasMany(SeGroupMembers::className(),["teacherID"=>"userID"]);
    }

    
}
