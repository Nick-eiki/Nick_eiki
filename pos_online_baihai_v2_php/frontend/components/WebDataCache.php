<?php
namespace frontend\components;

use common\models\pos\SeClassMembers;
use common\models\pos\SeSchoolInfo;
use common\models\sanhai\SeDateDictionary;
use common\models\sanhai\SeSchoolGrade;
use frontend\components\helper\ImagePathHelper;
use frontend\models\dicmodels\SubjectModel;
use Yii;

/**
 * Created by PhpStorm.
 * User: yang
 * Date: 15-7-13
 * Time: 上午11:46
 */
class WebDataCache
{


    /**
     * 用户名称
     * @param $useId
     * @return string
     */
    public static function getTrueName($useId)
    {

        $model = self::getUserModel($useId);

        if ($model) {
            return $model->trueName;
        }
        return '';
    }

    /**
     * 用户名
     * @param $useId
     * @return string
     */
    public static function getUserName($useId)
    {

        $model = self::getUserModel($useId);

        if ($model) {
            return $model->phoneReg;
        }
        return '';
    }

    /**
     * 用户科目
     * @param $useId
     * @return string
     */
    public static function getSubjectName($useId)
    {

        $model = self::getUserModel($useId);

        if ($model) {
            return SubjectModel::model()->getSubjectName($model->subjectID);
        }
        return '';
    }

    /**
     * 根据科目查询学科
     * @param $subjectId
     * @return string
     */
    public static function getSubjectNameById($subjectId)
    {
        return SubjectModel::model()->getSubjectName($subjectId);
    }


    /**
     * @param $userId
     * @return array|\common\models\pos\SeUserinfo|mixed|null
     */
    private static function getUserModel($userId)
    {

        if (intval($userId) <= 0) {
            return null;
        }
        $cache = Yii::$app->cache;
        $key = WebDataKey::USER_CACHE_KEY . $userId;
        $data = $cache->get($key);
        if ($data === false) {
            $data = \common\models\pos\SeUserinfo::find()->where(['userID' => $userId])->one();
            if ($data != null) {
                $cache->set($key, $data, 600);
            }
        }
        return $data;
    }

    /**
     * 判断用户身份，0-学生，1-老师
     * @param $userId
     * @return int|string
     */
    public static function getUserType($userId)
    {
        $model = self::getUserModel($userId);
        if ($model) {
            return $model->type;

        }
        return 0;
    }

    /**
     * 获取用户所在的学校id
     */
    public static function getSchoolId($userId)
    {
        $model = self::getUserModel($userId);

        if ($model) {
            return $model->schoolID;
        }

        return '';
    }

    /**
     * 获取头像
     */
    public static function getFaceIcon($useId, $wh = 0)
    {
        $faceIcon = "/pub/images/tx.jpg";
        $model = self::getUserModel($useId);

        if ($model) {
            if ($model->headImgUrl != null && trim($model->headImgUrl) != '') {
                $faceIcon = $model->headImgUrl;
            }
        }

        if ($wh > 0) {
            return ImagePathHelper::imgThumbnail($faceIcon, $wh, $wh);
        }


        return $faceIcon;
    }

    /**
     * 获取班级头像
     * @param $classId
     * @param int $wh
     * @return string
     */
    public static function getClassFaceIcon($classId, $wh = 0)
    {
        $classFaceIcon = "/static/images/cla.png";

        return $classFaceIcon;
    }

    /**
     * 获取学校头像
     * @param $schoolId
     * @param int $wh
     * @return string
     */
    public static function getSchoolFaceIcon($schoolId, $wh = 0)
    {
        $schoolFaceIcon = "/static/images/sch.png";

        return $schoolFaceIcon;
    }

    /**
     * 获取教研组头像
     * @param $groupId
     * @param int $wh
     * @return string
     */
    public static function getTeaGroupFaceIcon($groupId, $wh = 0)
    {
        $groupFaceIcon = "/static/images/tea.png";

        return $groupFaceIcon;
    }
    /**
     * 班级名称
     * @param $classId
     * @return string
     */
    public static function getClassesName($classId)
    {
        if (empty($classId))
        {
            return '';
        }

        $model = self::getClassModel($classId);

        if ($model) {
            return $model->className;
        }

        return '';

    }

    /**
     * @param $classId
     * @return array|\common\models\pos\SeClass|mixed|null
     */
    private static function getClassModel($classId)
    {
        if (intval($classId) <= 0) {
            return null;
        }

        $cache = Yii::$app->cache;
        $key = WebDataKey::CLASS_CACHE_KEY . $classId;
        $data = $cache->get($key);
        if ($data === false) {
            $data = \common\models\pos\SeClass::find()->where(['classID' => $classId])->one();
            if ($data != null) {
                $cache->set($key, $data, 6000);
            }
        }
        return $data;
    }


    /**
     * 查询班级学生人数
     * wgl
     * @param $classId
     * @return int|mixed|null|string
     */
    public static function getClassStudentMember($classId)
    {
        if (intval($classId) <= 0) {
            return null;
        }

        $cache = Yii::$app->cache;
        $key = WebDataKey::CLASS_STUDENT_MEMBER_CACHE_KEY . $classId;
        $data = $cache->get($key);
        if ($data === false) {
            $data = SeClassMembers::find()->where(['classID' => $classId, 'identity' => '20403'])->andWhere(['>', 'userID', 0])->count();
            if ($data != null) {
                $cache->set($key, $data, 6000);
            }
        }
        return $data;
    }

    /**
     * 学校名称
     * @param $schoolId
     * @return string
     */
    public static function getSchoolName($schoolId)
    {

        $model = self::getSchoolModel($schoolId);

        if ($model) {
            return $model->schoolName;
        }

        return '';

    }

    /**
     * @param $schoolId
     * @return array|\common\models\pos\SeSchoolInfo|mixed|null
     */
    private static function getSchoolModel($schoolId)
    {
      return  SeSchoolInfo::getOneCache($schoolId);
    }

    /**
     * 教研组名称
     * @param $groupId
     * @return string
     */
    public static function getTeachingGroupName($groupId)
    {

        $model = self::getGroupModel($groupId);

        if ($model) {
            return $model->groupName;
        }

        return '';

    }

    /**
     * @param $groupId
     * @return array|\common\models\pos\SeTeachingGroup|mixed|null
     */
    private static function getGroupModel($groupId)
    {

        if (intval($groupId) <= 0) {
            return null;
        }

        $cache = Yii::$app->cache;
        $key = WebDataKey::TEACHER_GROUP_CACHE_KEY . $groupId;
        $data = $cache->get($key);
        if ($data === false) {
            $data = \common\models\pos\SeTeachingGroup::find()->where(['ID' => $groupId])->one();
            if ($data != null) {
                $cache->set($key, $data, 6000);
            }
        }
        return $data;
    }

    /**
     * @param $gradeId
     * gradeModel
     */
    public static function getGradeModel($gradeId)
    {

        if (intval($gradeId) <= 0) {
            return null;
        }

        $cache = Yii::$app->cache;
        $key = WebDataKey::GRADE_CACHE_KEY . $gradeId;
        $data = $cache->get($key);
        if ($data === false) {
            $data = SeSchoolGrade::find()->where(['gradeId' => $gradeId])->one();
            if ($data != null) {
                $cache->set($key, $data, 6000);
            }
        }

        return $data;

    }

    /**
     * 数据字典模型
     * @param $tqtid
     * @return array|SeDateDictionary|mixed|null
     */
    public static function getDictionaryModel($secondCode)
    {
        $cache = Yii::$app->cache;
        $key = WebDataKey::SHOWTYPE_CACHE_KEY . $secondCode;
        $data = $cache->get($key);
        if ($data === false) {
            $data = SeDateDictionary::find()->where(['secondCode' => $secondCode])->one();
            if ($data != null) {
                $cache->set($key, $data, 6000);
            }
        }

        return $data;

    }


    /**
     * 科目
     * @param $id
     * @return mixed|string
     */
    public static function getClassSubject($id)
    {
        if(empty($id))
        {
            return '';
        }
        $cache = Yii::$app->cache;
        $key = WebDataKey::CLASS_SUBJECT_ID_CACHE_KEY.$id;
        $data = $cache->get($key);
        if($data === false)
        {
            $data = SeDateDictionary::find()->where(['firstCode'=>100,'secondCode'=>$id])->select('secondCode,secondCodeValue')->one()->secondCodeValue;
            if($data != null )
            {
                $cache->set($key,$data,6000);
            }
        }
        return $data;
    }


    /**
     * 学段
     * @param $id
     * @return mixed|string
     */
    public static function getClassDepartment($id)
    {
        if(empty($id))
        {
            return '';
        }
        $cache = Yii::$app->cache;
        $key = WebDataKey::CLASS_DEPARTMENT_ID_CACHE_KEY.$id;
        $data = $cache->get($key);
        if($data === false)
        {
            $data = SeDateDictionary::find()->where(['firstCode'=>202,'secondCode'=>$id])->select('secondCode,secondCodeValue')->one()->secondCodeValue;
            if($data != null )
            {
                $cache->set($key,$data,6000);
            }
        }
        return $data;
    }

    /**
     * 根据题型获取showTypeID
     * @param $tqtid
     * @return string
     */
    public static function getShowTypeID($tqtid)
    {
        $result = 0;
        $data = self::getDictionaryModel($tqtid);
        if($data){
            $result =     $data->reserve1;
        }
        return $result;
    }

    /**
     * 根据tqtid判断是否是主观题
     * @param $tqtid
     * @return bool
     */
    public static function isMajorQuestion($tqtid)
    {
        $showTypeID = self::getShowTypeID($tqtid);
        $isMajor = true;
        if ($showTypeID == 1 || $showTypeID == 2) {
            $isMajor = false;
        }
        return $isMajor;
    }

    /**
     * 获取数据字典名称
     * @param $secondCode
     * @return string
     */
    public static function getDictionaryName($secondCode)
    {
        $data = self::getDictionaryModel($secondCode);
        if ($data) {
            return $data->secondCodeValue;
        }
        return '';

    }

    /**
     * 年级名称
     * @param $groupId
     * @return string
     */
    public static function getGradeName($gradeId)
    {

        $model = self::getGradeModel($gradeId);

        if ($model) {
            return $model->gradeName;
        }

        return '';

    }


    /**
     * 获取班级状态
     * @param $gradeId
     * gradeModel
     */
    public static function getClassStatus($status)
    {
        $classStatus = '活动';
        if($status == 0){
            $classStatus = '活动';
        }else if($status == 1){
            $classStatus = '已封班';
        }else if($status == 2){
            $classStatus = '已毕业';
        }

        return $classStatus;

    }

}