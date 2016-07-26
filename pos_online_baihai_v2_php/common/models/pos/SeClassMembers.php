<?php

namespace common\models\pos;

use common\helper\DateTimeHelper;
use Exception;
use frontend\components\WebDataCache;
use frontend\components\WebDataKey;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "se_classMembers".
 *
 * @property integer $ID
 * @property string $classID
 * @property string $userID
 * @property string $identity
 * @property string $job
 * @property string $stuID
 * @property string $isDelete
 * @property string $memName
 * @property string $createTime
 */
class SeClassMembers extends PosActiveRecord
{
    /**
     * 班主任
     */
    const  TEACHER = '20401';
    /**
     * 学生
     */
    const  STUDENT = '20403';
    /**
     * 任课老师
     */
    const  TEACHER_COURSE = '20402';
    /**
     * 0  未删除
     */
    const  IS_DELETE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_classMembers';
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
     * @return SeClassMembersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeClassMembersQuery(get_called_class());
    }


    /**
     * 成员所在班级信息
     * @return \yii\db\ActiveQuery
     */
    public function getSeClass()
    {

        return $this->hasOne(SeClass::className(), ['classID' => 'classID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeClassSubject()
    {
        return $this->hasOne(SeClassSubject::className(), ['userID' => 'teacherID']);
    }

    /**
     * 查询班级成员信息
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfo(){
     return $this->hasOne(SeUserinfo::className(),['userID'=>'userID']);
     }
    /**
     * 根据classID查询班级老师和学生数
     * @param $classId
     * @param $identity
     * @return int|string
     */
    public static function getClassNumByClassId($classId, $identity = null)
    {
        $classQuery = SeClassMembers::find()->where(['classID' => $classId])->andWhere(['>', 'userID', 0]);
        if ($identity != null) {
            $classQuery = $classQuery->andWhere(['identity' => $identity]);
        }
        $classMemNum = $classQuery->count();
        return $classMemNum;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID'], 'required'],
            [['ID'], 'integer'],
            [['classID', 'userID', 'identity', 'job', 'stuID', 'createTime'], 'string', 'max' => 20],
            [['isDelete'], 'string', 'max' => 2],
            [['memName'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'id',
            'classID' => '班级id',
            'userID' => '用户ID',
            'identity' => '角色班内职务',
            'job' => '班级职务编码_职务名称',
            'stuID' => '学号。',
            'isDelete' => '是否删除',
            'memName' => '成员姓名',
            'createTime' => '创建时间',
        ];
    }

    /**
     * 查询班级班主任
     * 缓存一天时间
     * @param $classId
     * @return array|SeClassMembers|mixed|null|string
     */
    public function selectClassAdviser($classId)
    {
        if (empty($classId)) {
            return null;
        }

        return self::find()->where(['classID' => $classId, 'identity' => 20401])->andWhere(['>', 'userID', 0])->one();

    }

    /**
     * 查询教师列表
     * 缓存10分钟
     * @param $classId
     * @return array|SeClassMembers[]|mixed|string
     */
    public function selectClassTeacherList($classId)
    {
        if (empty($classId)) {
            return [];
        }
        $cache = Yii::$app->cache;
        $key = WebDataKey::CLASS_TEACHER_LIST_CACHE_KEY . "_" . $classId;
        $data = $cache->get($key);
        //20402 教师身份
        if ($data === false) {
            $data = self::find()->where(['classID' => $classId, 'identity' => 20402])->andWhere(['>', 'userID', 0])->all();
            if(!empty($data))
            {
                $cache->set($key,$data,600);
            }
        }
        return $data;
    }

    /**
     * 成员所在班级
     * @param $userId
     * @return array|SeClassMembers[]
     */
    public static function getClass($userId)
    {
        return SeClassMembers::find()->where(['userID' => $userId])->andWhere(['isDelete' => 0])->one();
    }


    /**
     * 查询班级学生列表
     * 缓存1天
     * @param $classId
     * @return array|SeClassMembers[]|mixed|string
     */
    public function selectClassStudentList($classId)
    {
        if(empty($classId)){
            return [];
        }
        $cache = Yii::$app->cache;
        $key = WebDataKey::CLASS_STUDENT_LIST_CACHE_KEY . "_" . $classId;
        $data = $cache->get($key);
        //20403 学生身份
        if($data === false)
        {
            $data = self::find()->where(['classID' => $classId, 'identity' => 20403])->andWhere(['>', 'userID', 0])->all();
            if(!empty($data))
            {
                $cache->set($key,$data,600);
            }
        }
        return $data;
    }

}
