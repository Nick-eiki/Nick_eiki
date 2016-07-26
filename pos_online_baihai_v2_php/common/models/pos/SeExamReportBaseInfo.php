<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_exam_reportBaseInfo".
 *
 * @property integer $examReportId
 * @property integer $schoolExamId
 * @property integer $classExamId
 * @property integer $classId
 * @property integer $subjectId
 * @property integer $realNumber
 * @property integer $missNumber
 * @property integer $fullScore
 * @property string $avgScore
 * @property string $maxScore
 * @property string $minScore
 * @property integer $goodNum
 * @property integer $noPassNum
 * @property integer $lowScoreNum
 * @property integer $overLineNum
 * @property integer $createTime
 * @property integer $updateTime
 */
class SeExamReportBaseInfo extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_exam_reportBaseInfo';
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
            [['schoolExamId', 'fullScore', 'avgScore', 'maxScore', 'minScore', 'createTime'], 'required'],
            [['schoolExamId', 'classExamId', 'classId', 'subjectId', 'realNumber', 'missNumber', 'fullScore', 'goodNum', 'noPassNum', 'lowScoreNum', 'overLineNum', 'createTime', 'updateTime'], 'integer'],
            [['avgScore', 'maxScore', 'minScore'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'examReportId' => '报表主键id',
            'schoolExamId' => '考试id（学校）',
            'classExamId' => '考试id（班级）',
            'classId' => '班级id',
            'subjectId' => '科目id',
            'realNumber' => '实考人数',
            'missNumber' => '缺考人数',
            'fullScore' => '满分',
            'avgScore' => '平均分',
            'maxScore' => '最高分',
            'minScore' => '最低分',
            'goodNum' => '优良人数',
            'noPassNum' => '不及格人数',
            'lowScoreNum' => '低分人数',
            'overLineNum' => '上线人数',
            'createTime' => '创建时间',
            'updateTime' => '更新时间',
        ];
    }

    /**
     * @inheritdoc
     * @return SeExamReportBaseInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeExamReportBaseInfoQuery(get_called_class());
    }

    /**
     * 统计——概览
     * 成绩概览
     * 单年级，单科目
     */
    public static function getSingleClassSingleSubjectInfo( $examId ,$classId ,$subjectId){

        $data = [];
        $seExamReprotBaseInfoList = SeExamReportBaseInfo::find()->where(['schoolExamId'=>$examId ,'subjectId'=>$subjectId ,'classId'=>$classId])->one();

        $seExamClass = SeExamClass::find()->where(['schoolExamId'=>$examId ,'classId'=>$classId])->one();
        if(empty($seExamClass)){
            return false;
        }
        $classExamId = $seExamClass->classExamId;

        //班级前5名
        $rankListDesc = SeExamPersonalScore::find()->where(['classExamId'=>$classExamId])->select("sub$subjectId")->groupBy('sub'.$subjectId)->orderBy('sub'.$subjectId.' desc')->limit('5')->column();
        $subArrDesc = [];
        foreach ($rankListDesc as $rankDesc) {
            $userInfoAsc = [];
            $userInfoAsc['score'] = $rankDesc;
            $rankScoreListDesc = SeExamPersonalScore::find()->where(['sub'.$subjectId=>$rankDesc , 'classExamId'=>$classExamId])->select("userId")->all();

            foreach($rankScoreListDesc as $rankScoreDesc){
                $userInfoAsc['userId'][] = \common\helper\UserInfoHelper::getUserName($rankScoreDesc->userId);;
            }
            array_push($subArrDesc , $userInfoAsc);
        }

        //班级后5名
        $rankListAsc = SeExamPersonalScore::find()->where(['classExamId'=>$classExamId])->select("sub$subjectId")->groupBy('sub'.$subjectId)->orderBy('sub'.$subjectId.' asc')->limit('5')->column();
        $subArrAsc = [];
        foreach ($rankListAsc as $rankAsc) {
            $userInfoAsc = [];
            $userInfoAsc['score'] = $rankAsc;
            $rankScoreListAsc = SeExamPersonalScore::find()->where(['sub'.$subjectId=>$rankAsc , 'classExamId'=>$classExamId])->select("userId")->all();

            foreach($rankScoreListAsc as $rankScoreAsc){
                $userInfoAsc['userId'][] = \common\helper\UserInfoHelper::getUserName($rankScoreAsc->userId);
            }
            array_push($subArrAsc , $userInfoAsc);
        }

        $data['seExamReprotBaseInfoList'] = $seExamReprotBaseInfoList;
        $data['rankListDesc'] = $subArrDesc;
        $data['rankListAsc'] = $subArrAsc;

        return $data;
    }

    /**
     * 统计——概览
     * 成绩概览
     * 全年级，单科目
     */
    public static function getAllClassSingleSubjectInfo( $examId  ,$subjectId){

        $seExamReportBaseInfo = SeExamReportBaseInfo::findBySql("select schoolExamId ,subjectId ,MAX(maxScore) as maxScore ,MIN(minScore) as minScore,SUM((realNumber+missNumber)*avgScore)/SUM(realNumber+missNumber) avgScore,SUM(realNumber) realNumber,SUM(missNumber) missNumber,SUM(noPassNum) noPassNum , SUM(goodNum) goodNum, SUM(lowScoreNum) lowScoreNum,SUM(overLineNum) as overLineNum from se_exam_reportBaseInfo".
            " where schoolExamId=:schoolExamId and subjectId=:subjectId" , [':schoolExamId'=>$examId ,':subjectId'=>$subjectId ])->one();

        return $seExamReportBaseInfo;
    }

    /**
     * 统计——概览
     * 成绩概览
     * 单年级，全科目
     */
    public static function getSingleClassAllSubjectInfo( $examId  ,$classId){

        $seExamReprotBaseInfoList = SeExamReportBaseInfo::find()->where(['schoolExamId'=>$examId  ,'classId'=>$classId])->all();
        return $seExamReprotBaseInfoList;
    }

    /**
     * 统计——概览
     * 成绩概览
     * 全部年级全部科目
     */
    public static function getAllClassAllSubjectInfo( $examId ){


        $subjectList = SeExamReportBaseInfo::find()->where(['schoolExamId'=>$examId])->groupBy(['subjectId' ])->select('subjectId')->all();
        $data = [];
        foreach($subjectList as $subject){

            $seExamReportBaseInfo = SeExamReportBaseInfo::findBySql("select schoolExamId , subjectId ,MAX(maxScore) as maxScore ,MIN(minScore) as minScore,SUM((realNumber+missNumber)*avgScore)/SUM(realNumber+missNumber) avgScore,SUM(realNumber) realNumber,SUM(missNumber) missNumber,SUM(noPassNum) noPassNum , SUM(goodNum) goodNum, SUM(lowScoreNum) lowScoreNum,SUM(overLineNum) as overLineNum from se_exam_reportBaseInfo".
                " where schoolExamId=:schoolExamId and subjectId=:subjectId" , [':schoolExamId'=>$examId ,':subjectId'=>$subject->subjectId ])->one();

            array_push($data , $seExamReportBaseInfo);
        }

        return $data;
    }

    /**
     * 统计——概览
     * 成绩概览
     * 优良率
     */
    public function getExcellentRate(){

        $classNum = $this->realNumber + $this->missNumber;
        return sprintf("%.2f", $classNum == 0 ? 0 : $this->goodNum / $classNum * 100);

    }

    /**
     * 统计——概览
     * 成绩概览
     * 及格率
     */
    public function getPassRate(){

        $classNum = $this->realNumber + $this->missNumber;
        return sprintf("%.2f", $classNum == 0 ? 0 : ($classNum - $this->noPassNum) / $classNum * 100);

    }

    /**
     * 统计——概览
     * 成绩概览
     * 低分率
     */
    public function getLowScoreRate(){

        $classNum = $this->realNumber + $this->missNumber;
        return sprintf("%.2f", $classNum == 0 ? 0 : $this->lowScoreNum / $classNum * 100) ;

    }

    /**
     * 统计——概览
     * 成绩概览
     * 分数线
     */
    public function getScoreLineOne(){

        $scoreLine = 0;
        $seExamSubject = SeExamSubject::find()->where(['schoolExamId'=>$this->schoolExamId ,'subjectId'=>$this->subjectId])->select('borderlineOne')->one();

        if(!empty($seExamSubject->borderlineOne)){
            $scoreLine = $seExamSubject->borderlineOne;
        }
        return $scoreLine;
    }

    /**
     * 统计——概览
     * 成绩概览
     * 满分
     */
    public function getFullScore(){

        $fullScore = 0;
        $seExamSubject = SeExamSubject::find()->where(['schoolExamId'=>$this->schoolExamId ,'subjectId'=>$this->subjectId])->select('fullScore')->one();

        if(!empty($seExamSubject->fullScore)){
            $fullScore = $seExamSubject->fullScore;
        }
        return $fullScore;
    }




}
