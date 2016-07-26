<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 15-6-25
 * Time: 下午12:15
 */

namespace common\helper;


use common\models\pos\SeClass;
use common\models\pos\SeClassMembers;
use common\models\pos\SeSchoolInfo;
use common\models\pos\SeUserinfo;
use common\models\sanhai\SeDateDictionary;
use common\models\sanhai\SeSchoolGrade;

class UserInfoHelper
{

    /**
     * 根据用户id返回用户的名字
     */
    static function getUserName($id)
    {
        $userModel = SeUserinfo::find()->where(['userID' => $id])->one();
        return $userModel->trueName;
    }

    /**
     * 根据用户id返回用户教的科目
     */
    static function getUserSubject($id)
    {
        $userModel = SeUserinfo::find()->where(['userID' => $id])->one();
        $subjectId = $userModel->subjectID;
        $dateDictionaryModel = SeDateDictionary::find()->where(['firstCode' => '100', 'secondCode' => $subjectId])->one();
        return $dateDictionaryModel->secondCodeValue;

    }

    /**
     * 根据用户id返回用户教的科目id
     */
    static function getUserSubjectId($id)
    {
        $userModel = SeUserinfo::find()->where(['userID' => $id])->one();
        return $userModel->subjectID;

    }

    /**
     * 根据用户id返回用户教的年级
     */
    static function getGradeName($id)
    {

        $classMemberModel = SeClassMembers::find()->where(['userID' => $id])->all();
        $result = "";
        foreach ($classMemberModel as $MemberModel) {
            $classId = $MemberModel->classID;
            $classModel = SeClass::find()->where(['classID' => $classId])->one();
            $gradeId = $classModel->gradeID;
            $gradeNameModel = SeSchoolGrade::find()->where(['gradeId' => $gradeId])->one();
            $result = $result . $gradeNameModel->gradeName . '&nbsp';
        }
        return $result;
    }

    /**
     * 根据用户id返回用户所在学校id 和 名字
     */
    static function getSchoolName($id)
    {
        $arr = [];
        $userModel = SeUserinfo::find()->where(['userID' => $id])->one();
        $schoolId = $userModel->schoolID;
        $schoolModel = SeSchoolInfo::find()->where(['schoolID' => $schoolId])->one();
        $schoolName = $schoolModel->schoolName;
        $arr[0] = $schoolId;
        $arr[1] = $schoolName;
        return $arr;
    }

}