<?php
namespace frontend\controllers;

use common\helper\KeyWordsHelper;
use common\models\JsonMessage;
use common\models\pos\SeAnswerQuestion;
use common\models\pos\SeQuestionResult;
use common\models\pos\SeSameQuestion;
use common\services\JfManageService;
use common\services\KehaiUserService;
use frontend\components\BaseAuthController;


/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/26
 * Time: 11:12
 * 答疑公共页
 */
class AnswerController extends BaseAuthController
{
    /**
     * 回答问题
     */
	public function actionResultQuestion()
	{
		$jsonResult = new JsonMessage();

		$userId = user()->id;
		$aqid = app()->request->post('aqid', 0);
		$answer = app()->request->post('answer', 0);
		$imgPath = '';
		if(empty($answer)){
			$jsonResult->success = false;
			$jsonResult->message = "回答内容不能为空！";
		}else{
			$questionResultModel = new SeQuestionResult;
			//调用 保存回答
			$saveResult = $questionResultModel->addResultQuestion($userId, $aqid,$answer, $imgPath);

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
	 *同问问题
	 */
	public function actionSameQuestion()
	{
		$jsonResult = new JsonMessage();
		$aqid = app()->request->post('aqid', 0);
		$userId = user()->id;

		$sameQuestionModel = new SeSameQuestion();
		//检查该用户是否同问过
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
     * 采用答案
	 * @return string
	 */
    public function actionUseTheAnswer()
    {
        $jsonResult = new JsonMessage();
	    $resultid = app()->request->post('resultid', 0);

	    $aqId = app()->request->post('aqid');
	    $questionResultModel = new SeQuestionResult();
	    $answerQuestionModel = new SeAnswerQuestion();
	    //查询回答列表
	    $checkReply =$questionResultModel->checkQuestionResult($aqId);

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
		    // 设置最佳答案  修改答疑 为解决状态
		    $answerQuestionsSolve = $answerQuestionModel->updateAnswerQuestionsSolve($aqId);
		    if ($useAnswer) {
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
     */
    public function actionAnswerDetail()
    {
        $aqid = app()->request->post('aqid', 0);
	    $answerModel = new SeAnswerQuestion();
	    $questionDetail = $answerModel->selectAnswerOne($aqid);
        return $this->renderPartial('//publicView/answer/_answer_list_all', array('val' => $questionDetail));
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
		return $this->renderPartial('//publicView/answer/_reply_list', ['model'=>$replyList,'question'=>$questionDetail]);
	}

    /**
     * 获取用户信息
     * @return string
     */
    public function actionShowPerMsg(){
          $userID=app()->request->getBodyParam('userID');
          $source=app()->request->getBodyParam('source');
        if($source==0){
            $data=\common\models\pos\SeUserinfo::find()->where(['userID' => $userID])->one();
            return $this->renderPartial('//publicView/answer/show_per_msg',['data'=>$data,'userID'=>$userID]);
        }elseif($source==1){
            $data=KehaiUserService::model()->getUserData($userID);
            if(!empty($data->list)) {
                return $this->renderPartial('//publicView/answer/kehai_per_msg', ['data' => $data]);
            }
        }
    }

}