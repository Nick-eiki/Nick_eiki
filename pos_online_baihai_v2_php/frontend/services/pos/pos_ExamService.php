<?php
namespace frontend\services\pos;
use ArrayObject;
use frontend\components\helper\StringHelper;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-16
 * Time: 下午3:22
 */
class pos_ExamService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/examService?wsdl");
    }

    /**
     * 创建考试
     * @param $userID
     * @param $classID
     * @param $examName
     * @param $schoolYear
     * @param $semester
     * @param $type
     * @param $subjectList
     * @return ServiceJsonResult
     */
    public function creatExamByMaster($userID, $classID, $examName, $schoolYear, $semester, $type, $subjectList,$paperId,$examTime)
    {
        $soapResult = $this->_soapClient->creatExamByMaster(
            array("userID" => $userID,
                "classID" => $classID,
                "examName" => $examName,
                "schoolYear" => $schoolYear,
                "semester" => $semester,
                "type" => $type,
                "subjectList" => $subjectList,
                "paperId"=>$paperId,
                "examTime"=>$examTime
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.1.    通过id查询考试信息
     * @param $examID
     * @param $userID
     * @return ServiceJsonResult
     */
    public function queryExamById($examID, $userID)
    {

        $soapResult = $this->_soapClient->queryExamById(
            array("userID" => $userID,
                "examID" => $examID
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 查询考试列表
     * @param $classID
     * @param $userID
     * @param $currPage
     * @param $pageSize
     * @return ServiceJsonResult
     */
    public function queryExamList($classID,$userID,$type, $currPage, $pageSize,$examName="")
    {
        $soapResult = $this->_soapClient->queryExamList(
            array("userID" => $userID,
                "classID" => $classID,
                "type"=>$type,
                "currPage" => $currPage,
                "pageSize" => $pageSize,
                "examName"=>$examName
            )
        );
        return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.1.    查询学生考试列表（10_31新增接口）
     * @param $classID
     * @param $userID
     * @param $currPage
     * @param $pageSize
     * @return ServiceJsonResult
     */
    public function queryExamListByStudent($classID, $userID, $currPage, $pageSize,$type="")
    {
        $soapResult = $this->_soapClient->queryExamListByStudent(
            array(
                "classID" => $classID,
                "userID" => $userID,
                "currPage" => $currPage,
                "pageSize" => $pageSize,
                "type"=>$type
            )
        );
        $result=  $this->soapResultToJsonResult($soapResult);
        if($result->resCode==BaseService::successCode){
            return $result->data;
        }
        return array();
    }

    /**
     * 3.13.7.    录入学生科目成绩
     * @param $userID
     * @param $subjectID
     * @param $scoreList
     * @return ServiceJsonResult
     */
    public function loggingStudentScore($userID, $examSubID, $scoreList)
    {
        $soapResult = $this->_soapClient->loggingStudentScore(
            array("userID" => $userID,
                "examSubID" => $examSubID,
                "scoreList" => $scoreList
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.9.    老师上传考试试卷
     * @param $examSubID
     * @param $senderID
     * @param $paperName
     * @param $describe
     * @param $url
     * @param $knowledgePoint
     * @return ServiceJsonResult
     */
    public function teacherUploadPaperInfo($examSubID, $senderID, $paperName, $describe, $url, $knowledgePoint)
    {
        $soapResult = $this->_soapClient->teacherUploadPaperInfo(
            array(
                "examSubID" => $examSubID,
                "senderID" => $senderID,
                "paperName" => $paperName,
                "describe" => $describe,
                "url" => $url,
                "knowledgePoint" => $knowledgePoint,
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.11.    老师修改考试试卷
     * @param $examSubID
     * @param $senderID
     * @param $paperName
     * @param $describe
     * @param $url
     * @param $knowledgePoint
     * @return ServiceJsonResult
     */
    public function teacherModifyPaper($examSubID, $senderID, $paperName, $describe, $url, $knowledgePoint)
    {
        $soapResult = $this->_soapClient->teacherModifyPaper(
            array(
                "examSubID" => $examSubID,
                "senderID" => $senderID,
                "paperName" => $paperName,
                "describe" => $describe,
                "url" => $url,
                "knowledgePoint" => $knowledgePoint,
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.10.    通过考试科目id查询科目试卷
     * @param $examSubID
     * @return ServiceJsonResult
     */
    public function searchExamPaper($examSubID)
    {
        $soapResult = $this->_soapClient->searchExamPaper(
            array(
                "examSubID" => $examSubID
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.3.    班主任修改考试名称
     * @param $userID
     * @param $examID
     * @param $newExamName
     * @return ServiceJsonResult
     */
    public function changeExamNameByMaster($userID, $examID, $newExamName)
    {
        $soapResult = $this->_soapClient->changeExamNameByMaster(
            array(

                "userID" => $userID,
                "examID" => $examID,
                "NewExamName" => $newExamName
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.13.    根据科目教师查询考生列表及答案
     * @param $examSubID
     * @return ServiceJsonResult
     */
    public function teacherQueryAnswerList($examSubID, $isneedPaper, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->teacherQueryAnswerList(
            array(
                "examSubID" => $examSubID,
                "isneedPaper" => $isneedPaper,
                "currPage" => $currPage,
                "pageSize" => $pageSize
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.4.    教师制定科目总评
     * @param $examSubID
     * @param $teacherID
     * @param $knowledgePoint
     * @param $summary
     * @return ServiceJsonResult
     */
    public function subjectSummary($examSubID, $teacherID, $knowledgePoint, $summary)
    {
        $soapResult = $this->_soapClient->subjectSummary(
            array(
                "examSubID" => $examSubID,
                "teacherID" => $teacherID,
                "knowledgePiont" => $knowledgePoint,
                "summary" => $summary
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.17.    查询科目最高和最低分
     * @param $examSubID
     * @param $teacherID
     * @return ServiceJsonResult
     */
    public function querySubjectMAXandMIN($examSubID, $teacherID)
    {
        $soapResult = $this->_soapClient->querySubjectMAXandMIN(
            array(
                "examSubID" => $examSubID,
                "teacherID" => $teacherID,
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.19.    查询考试最高和最低分（根据考试）
     * @param $examID
     * @param $teacherID
     * @return ServiceJsonResult
     */
    public function queryExamMAXandMIN($teacherID, $examID)
    {
        $soapResult = $this->_soapClient->queryExamMAXandMIN(
            array(
                "examID" => $examID,
                "teacherID" => $teacherID,
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.1.    查询分数区间人数
     * @param $examSubID
     * @param $teacherID
     * @return ServiceJsonResult
     */
    public function queryNumberByInterval($examSubID, $teacherID, $interval)
    {
        $soapResult = $this->_soapClient->queryNumberByInterval(
            array(
                "examSubID" => $examSubID,
                "teacherID" => $teacherID,
                "interval" => $interval
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.21.    查询考试分数区间人数(根据考试)
     * @param $teacherID
     * @param $examID
     * @param $Interval
     * @return ServiceJsonResult
     */
    public function queryExamNum($teacherID, $examID, $Interval)
    {
        $soapResult = $this->_soapClient->queryExamNum(
            array(
                "teacherID" => $teacherID,
                "examID" => $examID,
                "interval" => $Interval
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.5.    教师查询科目总评
     * @param $examSubID
     * @return ServiceJsonResult
     */
    public function searchSubjectEvaluate($examSubID)
    {
        $soapResult = $this->_soapClient->searchSubjectEvaluate(
            array(
                "examSubID" => $examSubID,
            )
        );
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

       $result= $this->mapperJsonResult($json);
        if($result->resCode==BaseService::successCode){
            return $result->data;
        }else{
            return array();
        }
    }

    /**
     * 3.13.2.    教师填写学生评价（个人或多人）
     * @param $examID
     * @param $studentlist
     * @param $evaluate
     * @return ServiceJsonResult
     */
    public function writeStudentEvaluation($examID, $studentlist, $evaluate)
    {
        $soapResult = $this->_soapClient->writeStudentEvaluation(
            array(
                "examID" => $examID,
                "studentlist" => $studentlist,
                "evaluate" => $evaluate
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.16.    查询考生成绩信息列表（根据考试）
     * @param $classID
     * @param $examID
     * @param $studentID
     * @param $currPage
     * @param $pageSzie
     * @return ServiceJsonResult
     */
    public function teacherQueryStuExamInfoList( $examID, $studentID="", $currPage="", $pageSize="")
    {
        $soapResult = $this->_soapClient->teacherQueryStuExamInfoList(
            array(

                "examID" => $examID,
                "studentID" => $studentID,
                "currPage" => $currPage,
                "pageSize" => $pageSize
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.22.    班主任评价班级
     * @param $examID
     * @param $learnSituation
     * @param $commonPro
     * @param $improveAdvise
     * @return ServiceJsonResult
     */
    public function writeClassEvaluate($examID, $learnSituation, $commonPro, $improveAdvise)
    {
        $soapResult = $this->_soapClient->writeClassEvaluate(
            array(
                "examID" => $examID,
                "learnSituation" => $learnSituation,
                "commonPro" => $commonPro,
                "improveAdvise" => $improveAdvise
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.23.    查询本班级评价
     * @param $examID
     * @return ServiceJsonResult
     */
    public function searchClassEvaluate($examID)
    {
        $soapResult = $this->_soapClient->searchClassEvaluate(
            array(
                "examID" => $examID,

            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.20.    教师填写学生评价（个人或多人）
     * @param $examID
     * @param $studentList
     * @param $evaluate
     * @return ServiceJsonResult
     */
    public function writeStudentEvaluate($examID, $studentList, $evaluate,$teacherID)
    {
        $soapResult = $this->_soapClient->writeStudentEvaluate(
            array(
                "teacherID"=>$teacherID,
                "examID" => $examID,
                "studentList" => $studentList,
                "evaluate" => $evaluate
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.13.21.    查询学生个人评价
     * @param $examID
     * @param $studentID
     * @return ServiceJsonResult
     */
    public function searchStudentEvaluate($examID, $studentID,$cmemID=null)
    {
        $soapResult = $this->_soapClient->searchStudentEvaluate(
            array(
                "examID" => $examID,
                "studentID" => $studentID,
                "cmemID"=>$cmemID
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * //
     * 3.13.11.    学生上传试卷（答案）图片
     * @param $userID
     * @param $examSubID
     * @param $url
     * @return ServiceJsonResult
     */
    public function uploadPaperImg($userID, $examSubID, $url)
    {
        $soapResult = $this->_soapClient->uploadPaperImg(
            array(
                "userID" => $userID,
                "examSubID" => $examSubID,
                "url" => $url,
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 上传测验答案图片
     * @param $studentID
     * @param $testID
     * @param $imageList
     * @return ServiceJsonResult
     */
    public function uploadTestAnswerImageUrl($studentID, $testID, $imageList)
    {
        $soapResult = $this->_soapClient->uploadTestAnswerImageUrl(
            array(
                "studentID" => $studentID,
                "examSubID" => $testID,
                "imageList" => $imageList
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    public function upload_test_AnswerImageUrl($studentID, $testID, $url)
    {
        $strArr=  StringHelper::splitNoEMPTY($url);
        $lst= from($strArr)->select(function($v){return ['imageUrl'=>$v];})->toList();
        $imageList= json_encode(['imageUrls'=>$lst]);
        $result=   $this->uploadTestAnswerImageUrl($studentID,$testID,$imageList);
        return $result;
    }

    /**
     * 3.13.17.    查询学生个人成绩
     * @param $examID
     * @param $studentID
     * @return ServiceJsonResult
     */
    public function searchStudentScore($examID, $studentID)
    {
        $soapResult = $this->_soapClient->searchStudentScore(
            array(
                "examID" => $examID,
                "studentID" => $studentID,
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 通过考试ID查询考试各个科目信息
     * @param $examID
     * @param $userID
     * @return ServiceJsonResult
     */
    public function queryExamByIdByStudent($examID, $userID)
    {
        $soapResult = $this->_soapClient->queryExamByIdByStudent(
            array(
                "examID" => $examID,
                "userID" => $userID
            )
        );
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        if ($this->mapperJsonResult($json)->resCode == pos_ExamService::successCode) {
            return $this->mapperJsonResult($json)->data;
        } else {
            return array();
        }

    }

    /**
     * 3.13.10.	 学生通过考试科目id查询自己上传的科目试卷（10_31新增接口）
     * @param $examSubID
     * @param $studentID
     * @return ServiceJsonResult
     */
    public function searchExamPaperByStudent($examSubID,$studentID){
        $soapResult = $this->_soapClient->searchExamPaperByStudent(
            array(
              "examSubID"=>$examSubID,
                "studentID" => $studentID,
            )
        );
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.42.7.	老师管理考试科目试卷
     * @param $examSubID
     * @param $userID
     * @param $paperId
     * @return ServiceJsonResult
     */
    public function manaExamPaper($examSubID,$userID,$paperId){
        $array=array(
          "examSubID"=>$examSubID,
            "userID"=>$userID,
            "paperId"=>$paperId
        );
        $soapResult = $this->_soapClient->manaExamPaper($array);
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        return $this->mapperJsonResult($json);
    }

    /**
     * 3.42.21.	教师查询考生答案详情
     * @param $answerID
     * @return ServiceJsonResult
     */
    public function teacherQueryAnswerInfo($answerID){
        $array=array(
            "answerID"=>$answerID
        );
        $soapResult = $this->_soapClient->teacherQueryAnswerInfo($array);
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        $result= $this->mapperJsonResult($json);
        if($result->resCode==BaseService::successCode){
            return $result->data;
        }
        return array();
    }
    /**
     * 3.42.31.	预览试卷(在线组卷类型使用)
     * @param $examSubID
     * @param string $paperId
     * @param string $currPage
     * @param string $pageSize
     * @return array
     */
    public function queryPaperByIDOrgType($examSubID ,$paperId="",$currPage="",$pageSize="1000"){
        $soapResult = $this->_soapClient->queryPaperByIDOrgType(
            array(
                "examSubID"=>$examSubID,
                "paperId"=>$paperId,
                "currPage"=>$currPage,
                "pageSize"=>$pageSize
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.42.6.	班主任设定科目
     * @param $examID
     * @param $userID
     * @param $subjectList
     * @param $examTime
     * @return ServiceJsonResult
     */
    public function masterSetSub($examID,$userID,$subjectList,$examTime){
        $array=array(
            "userID"=>$userID,
             "examID"=>$examID,
            "subjectList"=>$subjectList,
            "examTime"=>$examTime
        );
        $soapResult = $this->_soapClient->masterSetSub($array);
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        return $this->mapperJsonResult($json);
    }

    /**
     * 3.45.22.	查询科目最高分和最低分
     * @param $examSubID
     * @return array
     */
    public function searchTheMaxAndMinScoreSub($examSubID){
        $soapResult = $this->_soapClient->searchTheMaxAndMinScoreSub(
            array(
                "examSubID"=>$examSubID,

            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.45.24.	查询科目分数区间人数（√）
     * @param $examSubID
     * @return array
     */
    public function queryNumberByIntervalSub($examSubID,$interval){
        $soapResult = $this->_soapClient->queryNumberByIntervalSub(
            array(
                "examSubID"=>$examSubID,
                "interval"=>$interval
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

	/**
	 * 3.45.28.查询考试详情通过考试id(电子)（答题用）（√）
	 *
	 * @param $examSubID 	科目id  (原 examID)
	 * @param $userID		需要查询答案的学生的ID(可为空)
	 * @param $currPage		当前显示页码，可以为空,默认值1
	 * @param $pageSize		每页显示的条数，可以为空，默认值10
	 * @return array()
	 *
	 */
	public function queryTestInfoByIDOrgType($examSubID ,$userID,$currPage,$pageSize)
	{

		$soapResult = $this->_soapClient->queryTestInfoByIDOrgType(
			array(
				'examSubID' => $examSubID,
				'userID' => $userID,
				'currPage' => $currPage,
				'pageSize' => $pageSize
			));
		$result = $this->soapResultToJsonResult($soapResult);
		if ($result->resCode == self::successCode) {

			return $result->data;
		}
		return array();
	}
	/**
	 * 3.45.20.上传考试答案(电子)（√）(用于题库组卷试卷作答)
	 * @param $studentID
	 * @param $testID
	 * @param $answerList
	 * @return ServiceJsonResult
	 */
	public function uploadTestAnswerQuestion($studentID,$examSubID,$answerList){
		$soapResult = $this->_soapClient->uploadTestAnswerQuestion(
			array(
				"examSubID" => $examSubID,
				"studentID"=>$studentID,
				"answerList"=>$answerList
			));
		$result = $this->soapResultToJsonResult($soapResult);
		return $result;
	}
	/**
	 * 3.20.20.    查询最高分和最低分
	 * @param $testID
	 * @return array
	 */
	public function searchTheMaxAndMinScore($testID)
	{
		$soapResult = $this->_soapClient->searchTheMaxAndMinScore(
			array(
				"examID" => $testID
			));

		$result = $this->soapResultToJsonResult($soapResult);
		if ($result->resCode == BaseService::successCode) {
			return $result->data;
		}
		return array();
	}

    /**
     *3.45.25.	查询考试分数区间人数（√）
     * examID	考试科目列表中科目ID
    interval	{
    "data":[
    {
    'bottomlimit':'00',区间下限
    'toplimit':'59'区间上限
    },
    {
    'bottomlimit':'60',区间下限
    'toplimit':'69'区间上限
    },
    {
    'bottomlimit':'70',
    'toplimit':'79'
    },
    {
    'bottomlimit':'80',
    'toplimit':'89'
    },
    {
    'bottomlimit':'90',
    'toplimit':'99'
    }
    ]
    }
    token	安全保护措施
    查询成功	返回的JSON：
    返回的JSON示例：
    {
    "data":
    {
    "socreList":
    [
    {
    "bottomlimit":"00",区间下限
    "num":"3", 人数
    "toplimit":"59"区间上限
    },
    {
    "bottomlimit":"60",
    "num":"5",
    "toplimit":"69"
    },
    {
    "bottomlimit":"70",
    "num":"8",
    "toplimit":"79"
    },
    {
    "bottomlimit":"80",
    "num":"4",
    "toplimit":"89"
    },
    {
    "bottomlimit":"90",
    "num":"7",
    "toplimit":"99"
    }
    ]
    }
    "resCode": "000000",
    "resMsg": "成功"
    }
    应答代码和应答描述见《响应代码对照表》
    查询失败	返回的JSONB：
    {
    "data":{},
    "resCode":"应答代码",
    "resMsg":"应答描述"
    }
    应答代码和应答描述见《响应代码对照表》

     * @param $testID
     * @return array
     */
    public function queryNumberByIntervalExam($examID,$intervalArr)
    {
        $soapResult = $this->_soapClient->queryNumberByIntervalExam(
            array(
                "examID" => $examID,
                "interval"=>json_encode(['data'=>$intervalArr])
            ));

        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.45.23.	查询考试最高分和最低分（√）
     * @param $examID
     * @return array
     */
    public function searchTheMaxAndMinScoreExam($examID)
    {
        $soapResult = $this->_soapClient->searchTheMaxAndMinScoreExam(
            array(
                "examID" => $examID
            ));

        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.45.13.	查询考试答案列表及教师批改（√）
     *
    "resCode":"000000",
    "resMsg":"查询成功",
    "data":{
    "currPage":"当前页码",
    "totalPages":"总页数",
    "countSize":"总记录数",
    "pageSize":"每页数据的条数",

    “notCheckAnswerNum”为批改答案数
    “isHaveCrossCheck”“是否设置相互判卷 0没有，1有
    "answerlist":[//列表
    “studentID”:学生ID
    “studentName”:学生名称
    “testAnswerID”:””测验答案ID
    “getType”：“测验类型，试卷组织类型（0上传图片，1组卷）”
    isUploadAnswer:是否已上传答案 0未上传，1已上传
    “isCheck”:是否批改完成
    "stuSubScore":"个人科目成"
    “testScore”：“测验分数”
    //以下是上传图片类型试卷答案及批改
    “testCheckInfoS”：[
    {“tID”:”图片id”
    “imageUrl”:”图片地址”,
    “checkInfoJson”:”” 批改json}，
    {“tID”:”图片id”
    “imageUrl”:”图片地址”
    “checkInfoJson”:”” 批改json}
    ]


    //以下是组卷类型的答案及批改

    //客观题，选择题
    “objQuestionAnswerList”[
    {
    “tID”:”此题答案ID”//针对大题做一次记录
    “questionId”:问题ID
    “questionName”问题名称
    "tqtid":"",// 题型
    "questiontypename":"",// 题型名称
    “"showTypeId":"",//题目显示类型”

    "answerId": 答案记录ID//针对每个答案，包括大题小题做的一次记录
    "userAnswerOption": "选择题答案",
    "answerRight": "1",是否正确
    “score”:”分数”
    "answerTime": null,
    "ischecked":0 ,//是否批改 0否 1是

    }

    ]
    //主观题
    “resQuestionAnswerList”[
    {
    “tID”:”此题答案ID”//针对大题做一次记录
    “questionId”:问题ID
    “questionName”问题名称
    "tqtid":"",// 题型
    "questiontypename":"",// 题型名称
    “"showTypeId":"",//题目显示类型”

    "answerId": 答案记录ID//针对每个答案，包括大题小题做的一次记录
    "userAnswerOption": "选择题答案",
    "answerRight": "1",是否正确
    “score”:”分数”
    "answerTime": null,
    "ischecked":0 ,//是否批改 0否 1是
    "picList": [
    { "picId": 1012,答案图片ID
    "picUrl": "sdfdsf"地址
    "checkJson":""批改
    }
    ]


    }

    ]
    },

    ...
    ]
    }
    }

     * @param $examSubID
     * @param $currPage
     * @param $pageSize
     * @return array
     */
    public function queryTestAllAnswerList($examSubID,$classID,$currPage="",$pageSize="")
    {
        $soapResult = $this->_soapClient->queryTestAllAnswerList(
            array(
                "examSubID" => $examSubID,
                "classID"=>$classID,
                "currPage"=>$currPage,
                "pageSize"=>$pageSize
            ));

        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.45.19.	查询考试学生的答案(纸质)（√）
     * 	成功	{
    "resCode":"000000",
    "resMsg":"查询成功",
    "data":{
    “studentID”:””
    “studentName”:””
    "paperId":"",//试卷id
    “name”:”试卷名”
    “testAnswerID”：“答案ID”
    “isUploadAnswer” ： “是否上传答案”
    “testAnswerImages”：[
    {“tID”:”图片id”
    “imageUrl”:”图片地址”}，
    {“tID”:”图片id”
    “imageUrl”:”图片地址”}
    ]
    “isCheck”：“是否批改”
    "stuSubScore":"个人科目成绩"
    “testScore”：“分数”
    “teacherID”:批改教师
    “teacherName”:批改教师名称
    “testCheckInfoS”：[
    {“tID”:”图片id”
    “imageUrl”:”图片地址”,
    “checkInfoJson”:”” 批改json}，
    {“tID”:”图片id”
    “imageUrl”:”图片地址”
    “checkInfoJson”:”” 批改json}
    ]

    //以下是有相互批改其他人的
    “isHaveCrossCheck”：“是否需要相互判卷”
    0没有，1有
    “otherStudentID”:其他学生id
    “otherStudentName”:””其他学生名
    “otherTestAnswerID”他人答案id
    “otherIsCheck”他人的试卷是否批改
    0为批改，1已批改
    “otherHomeworkAnswerInfo”:[//相互判卷其他人的试卷信息
    {“tID”:”图片id”
    “imageUrl”:”图片地址”,
    “checkInfoJson”:”” 批改json}，
    {“tID”:”图片id”
    “imageUrl”:”图片地址”
    “checkInfoJson”:”” 批改json}

    }
    ]


    }
    }
     * @param $studentID
     * @param $examSubID
     * @param $testAnswerID
     * @return array
     */
    public function queryTestAnswerImages($studentID,$examSubID,$testAnswerID)
    {
        $soapResult = $this->_soapClient->queryTestAnswerImages(
            array(
                "studentID" => $studentID,
                "examSubID"=>$examSubID,
                "testAnswerID"=>$testAnswerID
            ));

        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     *3.45.27.	批阅试卷（纸质）（√）
     * @param $studentID
     * @param $examSubID
     * @param $testAnswerID
     * @return ServiceJsonResult
     */
    public function commitCheckInfo($teacherID,$testAnswerID,$tID,$checkInfoJson)
    {
        $soapResult = $this->_soapClient->commitCheckInfo(
            array(
                "teacherID"=>$teacherID,
                "testAnswerID"=>$testAnswerID,
                "tID"=>$tID,
                "checkInfoJson"=>$checkInfoJson
            ));

        $result = $this->soapResultToJsonResult($soapResult);
       return $result;
    }

    /**
     *
    3.45.41.	学生答案批改状态修改（√）
     * @param $testAnswerID
     * @param $teacherID
     * @param $isCheck
     * @param $summary
     * @param $testScore
     * @return ServiceJsonResult
     */
    public function updateCheckState($testAnswerID, $teacherID, $isCheck, $stuSubScore)
    {
        $soapResult = $this->_soapClient->updateCheckState(
            array(
                "teacherID" => $teacherID,
                "testAnswerID" => $testAnswerID,
                "isCheck" => $isCheck,
                "stuSubScore"=>$stuSubScore
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 3.45.29.	用于批改的答案查询（电子）（√）
     * @param $testAnswerID
     * @return array
     */
    public function querytestAllAnswerPicList($testAnswerID){
        $soapResult = $this->_soapClient->querytestAllAnswerPicList(
            array(
                "testAnswerID"=>$testAnswerID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.45.30.	批阅试卷（电子）（√）
     * @param $teacherID
     * @param $testAnswerID
     * @param $answerId
     * @param $picId
     * @param $checkJson
     * @return ServiceJsonResult
     */
    public function commitCheckInfoForOrgPaper($teacherID,$testAnswerID,$answerId,$picId,$checkJson){
        $soapResult = $this->_soapClient->commitCheckInfoForOrgPaper(
            array(
                "teacherID"=>$teacherID,
                "testAnswerID"=>$testAnswerID,
                "answerId"=>$answerId,
                "picId"=>$picId,
                "checkJson"=>$checkJson
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     *查询满分
     * @param $examID
     * @return array
     */
    public function queryExamFullScore($examID,$examSubID="",$testAnswerID="",$subjectID=""){
        $soapResult = $this->_soapClient->queryExamFullScore(
            array(
                "examID"=>$examID,
                "examSubID"=>$examSubID,
                "testAnswerID"=>$testAnswerID,
                "subjectID"=>$subjectID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.45.45.	统计班级学生排名详情
     * @param $examID
     * @return array
     */
    public function examRankingStas($examID,$orderType,$orderBySubject,$ascDesc){
        $soapResult = $this->_soapClient->examRankingStas(
            array(
                "examID"=>$examID,
                "orderType"=>$orderType,
                "orderBySubject"=>$orderBySubject,
                "ascDesc"=>$ascDesc
            ));
        $result = $this->soapResultToJsonResult($soapResult);

        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.45.43.	教师真在批改考试答案
     * @param $teacherID
     * @param $testAnswerID
     * @param $answerId
     * @param $picId
     * @param $checkJson
     * @return ServiceJsonResult
     */
    public function teacherChecking($teacherID,$testAnswerID){
        $soapResult = $this->_soapClient->teacherChecking(
            array(
                "teacherID"=>$teacherID,
                "testAnswerID"=>$testAnswerID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 3.45.44.	名称与上次考试变化
     * @param $examID
     * @param $userID
     * @return array
     */
    public function getRankChange($examID,$userID){
        $soapResult = $this->_soapClient->getRankChange(
            array(
                "examID"=>$examID,
                "userID"=>$userID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.45.21.	查询考试学生的答案(电子)（√）
     * @param $studentID
     * @param $examSubID
     * @param $testAnswerID
     * @return array
     */
    public function queryTestAnswerQuestion($studentID,$examSubID,$testAnswerID){
        $soapResult = $this->_soapClient->queryTestAnswerQuestion(
            array(
               "studentID"=>$studentID,
                "examSubID"=>$examSubID,
                "testAnswerID"=>$testAnswerID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.45.46.	查询一个科目下未录入成绩的学生
     * @param $studentID
     * @param $examSubID
     * @param $testAnswerID
     * @return array
     * 查询成功	{
    "data": {
    "noScoreStuList":
    [
    {
    "studentID":"考生id，用户表id",
    "studentName":"考生姓名",
    "stuID":"考生学号",

    },
    ],

    },
    "resCode": "000000",
    "resMsg": "成功"
    }

     */
    public function queryExamSubNoScoreStu($examSubID){
        $soapResult = $this->_soapClient->queryExamSubNoScoreStu(
            array(
                "examSubID"=>$examSubID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.45.47.	查询答案批改状态
     * @param $testAnswerID
     * @return array
     */
    public function answerCheckStatus($testAnswerID){
        $soapResult = $this->_soapClient->answerCheckStatus(
            array(
                "testAnswerID"=>$testAnswerID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     *一个班级所有考试中最高分
     * @param $classID
     * @param $subjectID
     * @return array
     */
    public function getAllExamHighFull($classID,$subjectID=""){
        $soapResult = $this->_soapClient->getAllExamHighFull(
            array(
                "classID"=>$classID,
                "subjectID"=>$subjectID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.45.49.	查询考试试卷设置的分数
     * @param $testAnswerID
     * @return array
     */
    public function queryPaperScore($testAnswerID){
        $soapResult = $this->_soapClient->queryPaperScore(
            array(
                "testAnswerID"=>$testAnswerID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.45.50.	查询考试分数区间人员列表（√）
     * @param $examID
     * @param $bottomlimit
     * @param $toplimit
     * @return array
     */
    public function queryStudentByIntervalExam($examID,$bottomlimit,$toplimit){
        $soapResult = $this->_soapClient->queryStudentByIntervalExam(
            array(
                "examID"=>$examID,
                "bottomlimit"=>$bottomlimit,
                "toplimit"=>$toplimit
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.45.51.	查询科目分数区间人员列表（√）
     * @param $testAnswerID
     * @return array
     */
    public function queryStudentByIntervalSub($examSubID,$bottomlimit,$toplimit){
        $soapResult = $this->_soapClient->queryStudentByIntervalSub(
            array(
               "examSubID"=>$examSubID,
                "bottomlimit"=>$bottomlimit,
                "toplimit"=>$toplimit
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }
}

?>