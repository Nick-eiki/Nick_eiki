<?php
namespace frontend\models\dicmodels;
use common\models\sanhai\SeSchoolGrade;
use common\models\sanhai\SrChapter;
use frontend\components\helper\StringHelper;
use stdClass;

/**
 * 章节信息模型
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-7-28
 * Time: 下午6:22
 */
class ChapterInfoModel
{

    /**
     *获取章节查询主树
     */
    public static function  findChapter($str)
    {
        $strarr = StringHelper::splitNoEMPTY($str);
        $resultArr = array();
        foreach ($strarr as $key => $v) {
            $cacheId = 'cache_chapter_str'.$v;
            $res = app()->cache->get($cacheId);
            if ($res === false) {
                $res = SrChapter::find()->where(['cid'=>$v])->select('chaptername')->one();
                if (!empty($res)) {
                    app()->cache->set($cacheId, $res, 3600);
                }
            }
            if (isset($res)){$resultArr[] =$res; }
        }
        return $resultArr;
    }

    /**
     * 通过知识点ID组成的字符串获取知识点点名称组成的字符串
     * @param $str
     * @return string
     */
    public static function findChapterStr($str)
    {
        $strarr = StringHelper::splitNoEMPTY($str);
        $result = array();
        foreach ($strarr as $key => $v) {
            $cacheId = 'cache_chapter_idsV2'.$v;
            $res = app()->cache->get($cacheId);
            if ($res === false) {
                $res = SrChapter::find()->where(['cid'=>$v])->select('chaptername')->one();
                if (!empty($res)) {
                    app()->cache->set($cacheId, $res, 120);
                }
            }
            if (isset($res)){$result[] =$res; }
        }
        $Chapter = array();
        foreach ($result as $v) {
            array_push($Chapter, $v->chaptername);
        }
        return implode(",", $Chapter);

    }



    /**
     * 查询书转树形结点
     * @param $subjectID        科目
     * @param $departmentID     学籍（小学\初中\高中等）
     * @param $bookVersionID    教材版本（人教版\北师大版等）
     * @param $schoolLength     学制（五四\五三\六三等
     * @param $grade            年级
     * @param $session
     * @param $bookAtt
     * @return mixed
     */
    public static function searchChapterPointToTree($subjectID, $departmentID, $bookVersionID, $schoolLength, $grade, $session=null, $bookAtt=null)
    {
        $resultArr = [];
        if(empty($subjectID) || empty($bookVersionID)){return [];}
        if(empty($departmentID)){
            if(empty($grade)){
                return [];
            }else{
               $gradeid= SeSchoolGrade::find()->where(['gradeId'=>$grade])->select('schoolDepartment')->one();
               $arr=SrChapter::find()->where(['subject'=>$subjectID,'schoolLevel'=>$gradeid->schoolDepartment,'version'=>$bookVersionID ,'bookAtt'=>$bookAtt])->select('cid,pid,chaptername')->all();
            }
        }else{
            $arr=SrChapter::find()->where(['subject'=>$subjectID,'schoolLevel'=>$departmentID,'version'=>$bookVersionID,'bookAtt'=>$bookAtt])->select('cid,pid,chaptername')->all();
        }
        $callback =
            function ($item) {
                $c=  new  stdClass();
                $c->id=$item->cid;
                $c->pId=$item->pid;
                $c->name=$item->chaptername;
                return $c;
            };
        foreach ($arr as $item) {
            $resultArr[] = $callback($item);
        }
        return $resultArr;
    }


    /**
     * 根据ID获取名字
     * @param $id
     * @return string
     */
    public static function  getNamebyId($id)
    {
        $cacheId = 'cache_chapter_id'.$id;
        $result = app()->cache->get($cacheId);
        if ($result === false) {
            $result =SrChapter::find()->where(['cid'=>$id])->select('chaptername')->one();
            if (!empty($result)) {
                app()->cache->set($cacheId, $result, 120);
            }
        }

        return is_null($result)?'':$result->chaptername;
    }

    /**
     * 获取分册
     */
    public static function getMajorChapter($subjectID, $departmentID, $bookVersionID, $grade){
        $resultArr = [];
        if(empty($subjectID) || empty($bookVersionID)){return [];}
        if(empty($departmentID)){
            if(empty($grade)){
                return [];
            }else{
                $gradeid= SeSchoolGrade::find()->where(['gradeId'=>$grade])->select('schoolDepartment')->one();
                $arr=SrChapter::find()->where(['subject'=>$subjectID,'schoolLevel'=>$gradeid->schoolDepartment,'version'=>$bookVersionID,'pid'=>'0'])->select('cid,chaptername')->all();
            }
        }else{
            $arr=SrChapter::find()->where(['subject'=>$subjectID,'schoolLevel'=>$departmentID,'version'=>$bookVersionID ,'pid'=>'0'])->select('cid,chaptername')->all();
        }
        $callback =
            function ($item) {
                $c=  new  stdClass();
                $c->id=$item->cid;
                $c->name=$item->chaptername;
                return $c;
            };
        foreach ($arr as $item) {
            $resultArr[] = $callback($item);
        }
        return $resultArr;
    }

    /**
     * 根据科目，版本，学部获取章节数组
     * @param $subject
     * @param $version
     * @param $department
     * @return array
     */
    public static function getChapterArray($subject,$version,$department){
        $chapterTomeResult = SrChapter::find()
            ->where(['subject' => $subject, 'version' => $version, 'schoolLevel' => $department, 'pid' => 0])
            ->select('chaptername,cid')->asArray()->all();
        $chapterArray = [];
        foreach ($chapterTomeResult as $v) {
            $chapterArray[$v['cid']] = $v['chaptername'];
        }
        $chapterArray[''] = '请选择';
        return $chapterArray;
    }


}