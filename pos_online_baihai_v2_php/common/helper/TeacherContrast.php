<?php
/**
 * Created by PhpStorm.
 * User: aaa
 * Date: 2016/3/12
 * Time: 13:30
 */
namespace common\helper;

use common\models\pos\SeExamClassSubject;
use common\models\pos\SeExamReportBaseInfo;
use common\models\pos\SeExamSubject;

class TeacherContrast{

    /*
     * 老师下面的班级
     * $subjectId 科目ID
     * $schoolExamId 考试ID
     */
    public function teacherData($subjectId,$schoolExamId){

        $examSubId=SeExamSubject::find()->where(['subjectId'=>$subjectId,'schoolExamId'=>$schoolExamId])->one()->examSubId;
        $teacherResult=SeExamClassSubject::find()->where(['examSubId'=>$examSubId,'schoolExamId'=>$schoolExamId])->groupBy(['teacherId'])->all();
        $allDataArray=[];
        foreach($teacherResult as $v){
            $teacherData=SeExamClassSubject::find()->where(['teacherId'=>$v->teacherId,'examSubId'=>$examSubId])->all();
            if($v->teacherId!=0) {
                $classArray = [];
                foreach ($teacherData as $value) {
                    $examClassResult = $value->classExam;
                    $classId = $examClassResult->classId;
                    array_push($classArray, $classId);
                }
                $classData = implode(',', $classArray);
                $dataArray = ['teacherId' => $v->teacherId, 'classData' => $classData];
                array_push($allDataArray, $dataArray);
            }else{
                foreach($teacherData as $value){
                    $examClassResult = $value->classExam;
                    $classId = $examClassResult->classId;
                    array_push($allDataArray,['teacherId'=>0,'classData'=>$classId]);
                }
            }
        }

        return $allDataArray;
    }


    /*
     * 低分率
     * @retutn array
     * $list 老师对应的班级
     */
    public function lowList($list){
        $array=[];
        foreach($list as $val){
            //班级人数
            $lowNum=floatval($val['data']['realNumber'])+floatval($val['data']['missNumber']);

            if($lowNum==0 || floatval($val['data']['lowScoreNum'])==0){
                $lowContrast=0;
            }else{
                $lowContrast=(floatval($val['data']['lowScoreNum'])/$lowNum)*100;
            }
            $array[]=['teacherId'=>$val['teacherId'],'lowContrast'=>$lowContrast];
        }

        $lowNumber=[];
        $name=[];
        foreach($array as $val){
            $lowNumber[]=round($val['lowContrast'],2);
            $name[]=$val['teacherId']==0?'':UserInfoHelper::getUserName($val['teacherId']);
        }
        $lowNumList=['max'=>100,'min'=>0,'lowNumber'=>$lowNumber,'name'=>$name];
        return $lowNumList;
    }



    /*
     * 及格率
     * @return array
     * $list 老师对应的班级
     */
    public function noPassList($list){
        $array=[];
        foreach($list as $val){
            //班级人数
            $passNum=floatval($val['data']['realNumber'])+floatval($val['data']['missNumber']);
            if($passNum==0){
                $passContrast=0;
            }else{
                if(floatval($val['data']['noPassNum'])==0){
                    $passContrast=100;
                }else{
                    $passContrast=(1-(floatval($val['data']['noPassNum'])/$passNum))*100;
                }
            }
            $array[]=['teacherId'=>$val['teacherId'],'passContrast'=>$passContrast];
        }
        $passNumber=[];
        $name=[];
        foreach($array as $val){
            $passNumber[]=round($val['passContrast'],2);
            $name[]=$val['teacherId']==0?'':UserInfoHelper::getUserName($val['teacherId']);
        }
        $goodNumList=['max'=>100,'min'=>0,'passNumber'=>$passNumber,'name'=>$name];
        return $goodNumList;
    }



    /*
     * 优良率
     * @return array
     * $list array
     */
    public function goodNumList($list){
        $array=[];
        foreach($list as $val){
            //班级人数
            $gooNum=floatval($val['data']['realNumber'])+floatval($val['data']['missNumber']);

            if($gooNum==0 || floatval($val['data']['goodNum'])==0){
                $goodContrast=0;
            }else{
                $goodContrast=(floatval($val['data']['goodNum'])/$gooNum)*100;
            }
            $array[]=['teacherId'=>$val['teacherId'],'goodContrast'=>$goodContrast];
        }
        $goodNumber=[];
        $teacherName=[];
        foreach($array as $val){
            $goodNumber[]=round($val['goodContrast'],2);
            $teacherName[]=$val['teacherId']==0?'':UserInfoHelper::getUserName($val['teacherId']);
        }
        $goodNumList=['max'=>100,'min'=>0,'goodNumber'=>$goodNumber,'teacherName'=>$teacherName];
        return $goodNumList;
    }
    public function  overLineNum($list){

        $array=[];
        foreach($list as $val){
            $classNum=count(explode(',',$val['classId']));

            if($classNum==0 || floatval($val['data']['overLineNum'])==0){
                $overLineNum=0;
            }else{
                $overLineNum=(floatval($val['data']['overLineNum'])/$classNum);
            }
            $array[]=['teacherId'=>$val['teacherId'],'overLineNum'=>$overLineNum];
        }

        $overLineNumArray=[];
        $name=[];
        foreach($array as $val){
            $overLineNumArray[]=round($val['overLineNum'],2);
            $name[]=$val['teacherId']==0?'':UserInfoHelper::getUserName($val['teacherId']);
        }
        $overLineNum=['max'=>100,'min'=>0,'overLineNum'=>$overLineNumArray,'name'=>$name];
        return $overLineNum;
    }


    /*
     *公共方法
     * @renter array
     * $dataResult 回传数据，老师对应的班级
     * $examId  考试ID
     * $subjectId  科目ID
     */
    public function pubList($dataResult,$examId,$subjectId){
        $list=[];
        foreach(json_decode($dataResult) as $val){
            $array['teacherId']=$val->teacherId;
            $array['classId']=$val->classData;
            $array['data']=SeExamReportBaseInfo::findBySql("SELECT sum(realNumber) realNumber,sum(missNumber) missNumber,sum(goodNum) goodNum,sum(noPassNum) noPassNum,sum(lowScoreNum) lowScoreNum,sum(overLineNum) overLineNum
                FROM `se_exam_reportBaseInfo` WHERE schoolExamId=:examId and  subjectId=:subjectId and classId in ($val->classData)",[':examId'=>$examId,':subjectId'=>$subjectId])->one();
            $list[]=$array;
        }
        return $list;
    }


    /*
    *移动公共方法
    * @renter array
    * $dataResult 回传数据，老师对应的班级
    * $examId  考试ID
    * $subjectId  科目ID
    */
    public function pubTeacherList($dataResult,$examId,$subjectId){
        $list=[];
        foreach($dataResult as $val){
            $array['teacherId']=$val['teacherId'];
            $array['classId']=$val['classData'];
            $classId=$val['classData'];
            $array['data']=SeExamReportBaseInfo::findBySql("SELECT sum(realNumber) realNumber,sum(missNumber) missNumber,sum(goodNum) goodNum,sum(noPassNum) noPassNum,sum(lowScoreNum) lowScoreNum,sum(overLineNum) overLineNum
                FROM `se_exam_reportBaseInfo` WHERE schoolExamId=:examId and  subjectId=:subjectId and classId in ($classId)",[':examId'=>$examId,':subjectId'=>$subjectId])->one();
            $list[]=$array;
        }
        return $list;
    }


}