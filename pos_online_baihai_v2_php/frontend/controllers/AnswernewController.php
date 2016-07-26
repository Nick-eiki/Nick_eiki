<?php
namespace frontend\controllers;

use common\models\JsonMessage;
use common\models\pos\SeAnswerQuestion;
use common\models\pos\SeQuestionResult;
use common\models\pos\SeSameQuestion;
use common\models\pos\SeUserinfo;
use common\services\JfManageService;
use common\services\KehaiUserService;
use frontend\components\BaseAuthController;
use frontend\components\helper\PinYinHelper;
use frontend\components\WebDataCache;


/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/26
 * Time: 11:12
 * 答疑公共页
 */
class AnswernewController extends BaseAuthController
{

	/**
	 * 用于打开一个回答的片段
	 * @return string
	 */
	public function actionResponseOpen()
	{
		$aqId = app()->request->get('aqId');
		return $this->renderPartial("//publicView/answer/_new_answer_question_response",["aqId"=>$aqId]);
	}
    /**
     * 回答问题
	 * @return string
	 */
	public function actionResultQuestion()
	{
		$jsonResult = new JsonMessage();
		$userId = user()->id;
		$aqid = app()->request->post('aqid', 0);//获取问题id
		$answer = app()->request->post('answer', 0);//获取回答内容
		$imgPath = app()->request->post('img_val');//获取图片路径
		//权限 查询内容是否为空
		if(empty($answer) && empty($imgPath)){
			$jsonResult->success = false;
			$jsonResult->message = "回答内容不能为空！";
		}else{
			$questionResultModel = new SeQuestionResult;
			//调用 保存回答
			$saveResult = $questionResultModel->addResultQuestion($userId, $aqid,$answer,$imgPath);
			if ($saveResult) {
//             回复答疑增加积分
				$jfHelper=new JfManageService;
				$jfHelper->myAccount("pos-request",$userId);
				$jsonResult->success = true;
				$jsonResult->message = "回答成功！";
			} else {
				$jsonResult->success = false;
				$jsonResult->message = "回答失败！";
			}
		}
		return $this->renderJSON($jsonResult);
	}

	/**
	 * 同问问题
	 * @return string
	 */
	public function actionSameQuestion()
	{
		$jsonResult = new JsonMessage();
		$aqid = app()->request->get('aqid', 0);//获取问题id
		$userId = user()->id;

		$sameQuestionModel = new SeSameQuestion();
		//检查该用户是否同问过，同问过返回false，不予同问。
		$selSame = $sameQuestionModel->checkSame($aqid, $userId);

		if(!empty($selSame)){
			$jsonResult->success = false;
			$jsonResult->message = "您已同问过该问题！";
		}else{
			//保存同问
			$saveSame = $sameQuestionModel->addSame($aqid,$userId);
			if ($saveSame) {
				$jfHelper=new JfManageService;
				$jfHelper->myAccount("pos-identical",$userId);
				$jsonResult->success = true;
				$jsonResult->message = "同问成功！";
			} else {
				$jsonResult->success = false;
				$jsonResult->message = '同问失败！';
			}
		}
		return $this->renderJSON($jsonResult);
	}

	/**
	 * 查询同问头像
	 * 用户点击同问时，用来替换页面原同问头像列表，增加同问者头像
	 * @return string
	 */
	public function actionAlsoAskHead()
	{
		$aqId = app()->request->get('aqId');
		$sameQuestionModel = new SeSameQuestion();
		$alsoAsk = $sameQuestionModel->selectSameQuestionAll($aqId);
		return $this->renderPartial('//publicView/answer/_new_answer_question_alsoask_head', array('alsoAsk' => $alsoAsk));
	}

    /**
     * 采用答案
	 * @return string
	 */
    public function actionUseTheAnswer()
    {
        $jsonResult = new JsonMessage();
	    $resultid = app()->request->post('resultid', 0);//获取回答的id
	    $aqId = app()->request->post('aqid');//问题id

	    $questionResultModel = new SeQuestionResult();
	    $answerQuestionModel = new SeAnswerQuestion();

	    //权限，查询回答列表，防止一个答疑有多个最佳答案。
	    $checkReply = $questionResultModel->checkQuestionResult($aqId);
	    //查询单个答案
		$selOneQuestionResult = $questionResultModel->selectOneQuestionResult($aqId,$resultid);
	    $resultCreatorId = $selOneQuestionResult->creatorID;//回答人的userId
	    if(!empty($checkReply)){
		    $jsonResult->success = false;
		    $jsonResult->message = '已有采用过最佳答案！';
	    }elseif(empty($resultid)){
		    $jsonResult->success = false;
		    $jsonResult->message = '请正确采用！';
	    }elseif(empty($selOneQuestionResult)){
		    $jsonResult->success = false;
		    $jsonResult->message = "答案不存在，请刷新！";
	    }else {
		    //修改答案列表 设置最佳答案
		    $useAnswer = $questionResultModel->updateUseAnswer($resultid);
		    // 设置最佳答案  修改答疑为解决状态
		    $answerQuestionsSolve = $answerQuestionModel->updateAnswerQuestionsSolve($aqId);
		    if ($useAnswer) {
			    //采用成功，给回答者增加积分
			    $jfHelper = new JfManageService;
			    $jfHelper->myAccount("pos-accept", $resultCreatorId);
			    $jsonResult->success = true;
			    $jsonResult->message = '采用成功！';
		    } else {
			    $jsonResult->success = false;
			    $jsonResult->message = '采用失败！';
		    }
	    }
        return $this->renderJSON($jsonResult);
    }

    /**
     * 答疑详情
     * (目前用于 采用最佳答案 时 刷新 答疑列表的单条答疑数据)
	 * @return string
	 */
    public function actionAnswerDetail()
    {
        $aqid = app()->request->post('aqid', 0);
	    $answerModel = new SeAnswerQuestion();
	    $questionDetail = $answerModel->selectAnswerOne($aqid);
        return $this->renderPartial('//publicView/answer/_new_answer_question_list_details', array('val' => $questionDetail));
    }

	/**
	 * 答案列表
	 * @return string
	 */

	public function actionReplyList(){
		$aqId = app()->request->post('apid');
		$questionResultModel = new SeQuestionResult();
		$answerModel = new SeAnswerQuestion();
		//查询回答列表
		$replyList = $questionResultModel->selectQuestionResultList($aqId);

		//查询答疑单条问题
		$questionDetail = $answerModel->selectAnswerOne($aqId);
		return $this->renderPartial('//publicView/answer/_new_answer_question_list_reply_list', ['model'=>$replyList, 'question'=>$questionDetail]);
	}

    /**
     * 获取用户信息
     * @return string
     */
    public function actionShowPerMsg(){
		$jsonResult = new JsonMessage();
	    $userID=app()->request->get('userID');
	    $source=app()->request->get('source');
	    //当source是0的时候是 班海的注册用户，当为1时是科海注册用户
        if($source==0){

	        $data=SeUserinfo::find()->where(['userID' => $userID])->one();
	        $schoolName=WebDataCache::getSchoolName($data->schoolID);

	        if($data->type==1){
		        $subjectName = WebDataCache::getDictionaryName($data->subjectID);

		        //拼写js需要的json串 教师的
		        $jsonResult->data = ['QRCode'=>url('qrcode/gr/',['id'=>$data->userID,"source"=>$source]),'headImg'=>$data->headImgUrl,'name'=>$data->trueName,'identity'=>'教师','courseName'=> mb_substr($subjectName,0,1,'utf-8'),'courseClass'=>PinYinHelper::firstChineseToPin($subjectName),'stu_school'=>'就职于&nbsp;&nbsp;'.$schoolName];
		        $jsonResult->success = true;
	        }else{
		        //学生的
		        $jsonResult->data = ['QRCode'=>url('qrcode/gr/',['id'=>$data->userID,"source"=>$source]),'headImg'=>$data->headImgUrl,'name'=>$data->trueName,'identity'=>'学生','courseName'=> '','courseClass'=>'','stu_school'=>'就读于&nbsp;&nbsp;'.$schoolName];
		        $jsonResult->success = true;
	        }

	        return $this->renderJSON($jsonResult);
        }elseif($source==1){
	        //科海用户的信息，
            $data=KehaiUserService::model()->getUserData($userID);
//学生的
	        if(empty($data->list)){
		        $jsonResult->data = [];
		        $jsonResult->success = false;
		        return $this->renderJSON($jsonResult);
	        }else{
		        $jsonResult->data = ['QRCode'=>url('qrcode/gr/',['id'=>$data->userId,"source"=>$source]),'name'=>$data->nickName,'identity'=>'大学生','courseName'=> '','courseClass'=>'','stu_school'=>'就读于&nbsp;&nbsp;'.$data->school];
		        $jsonResult->success = true;
		        return $this->renderJSON($jsonResult);
	        }
//            return $this->renderPartial('//publicView/answer/kehai_per_msg',['data'=>$data]);
        }

    }

	/**
	 * 查询用户提交答疑次数
	 * @return string
	 */
	public function actionCheckAnswer(){

		$jsonResult = new JsonMessage();
		$userId = user()->id;
		$model = new SeAnswerQuestion();

		$checkAnswer = $model->checkAnswerNum($userId);
		if($checkAnswer<2){
			$jsonResult->success = true;

		}else{
			$jsonResult->success = false;
			$jsonResult->message = "您今天已经提问过2次问题，请明天再提问！";
		}
		return $this->renderJSON($jsonResult);
	}
}