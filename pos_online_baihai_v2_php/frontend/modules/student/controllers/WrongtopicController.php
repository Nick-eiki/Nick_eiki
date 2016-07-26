<?php
namespace frontend\modules\student\controllers;
use common\models\JsonMessage;
use common\models\pos\SeWrongQuestionBookInfo;
use common\models\pos\SeWrongQuestionBookSubject;
use frontend\components\StudentBaseController;
use frontend\models\dicmodels\DegreeModel;
use frontend\models\TakePhotoForm;
use frontend\services\apollo\Apollo_QuestionTypeService;
use frontend\services\BaseService;
use frontend\services\pos\pos_WrongTopicService;
use yii\data\Pagination;
use yii\helpers\Html;

/**
 * Created by PhpStorm.
 * User: liquan
 * Date: 2014/11/17
 * Time: 10:05
 */
class WrongtopicController extends StudentBaseController
{
	public $layout = "lay_user";


	//错题列表 新
	public function actionManage()
	{
        $userId = user()->id;
        $questionList=SeWrongQuestionBookSubject::find()->where(['userId'=>$userId])->all();
        return $this->render('manage', array('questionList' => $questionList));
	}
    // 新单科 错题列表
    public function actionWroTopForItem(){
        $wrongSubjectId=app()->request->get('subjectId');
        $userId = user()->id;
        //错题本
        $wrongQuestionQuery=SeWrongQuestionBookInfo::find()->where(["userId"=>$userId,'wrongSubjectId'=>$wrongSubjectId,'isDetete'=>0])->orderBy(['createTime' => SORT_DESC]);
        $wrongSubject= SeWrongQuestionBookSubject::find()->where(['userId'=>$userId,'wrongSubjectId'=>$wrongSubjectId])->one();
       //错题
        $pages = new Pagination();
        $pages->validatePage=false;
        $pages->pageSize = 10;
        $pages->totalCount = $wrongQuestionQuery->count();
        $wrongQuestion=$wrongQuestionQuery->offset($pages->getOffset())->limit($pages->getLimit())->all();
        return $this->render('newwrongtopic',['wrongQuestion'=>$wrongQuestion,
            'wrongSubject'=>$wrongSubject,
            "pages" => $pages]);
    }

	// 旧 单科 错题列表
//	public function actionWroTopForItem()
//	{
//		$pages = new Pagination();
//		$pages->validatePage=false;
//		$pages->pageSize = 10;
//
//		$item = app()->request->getParam('item', null);
//		$userId = user()->id;
//		$gradeId = app()->request->getParam('grade', null);
//		$orderType = app()->request->getParam('orderType', null);
//		$obj = new pos_WrongTopicService();
//		$list = $obj->questionSearch('', $userId, $pages->getPage() + 1, $pages->pageSize, $gradeId, $item, $orderType, '', '');
//		$pages->totalCount = intval($list->countSize);
//		$pages->params['item'] = $item;
//		$pages->params['grade'] = $gradeId;
//		$subject = SubjectModel::model()->getSubjectName($item);
//
//		if(app()->request->isAjax){
//            return $this->renderPartial('//publicView/wrong/_new_wrong_list',array(
//				"model" => $list,
//				"subject" => $subject,
//				'pages'=>$pages
//			));
//
//		}
//        return $this->render('wrotopforitem', array("model" => $list, "subject" => $subject, "pages" => $pages));
//	}


	//新 录入题目
	public function actionWrongEnter()
	{
		$user = user()->id;
		$questionInfo = new pos_WrongTopicService();
		$reslut = $questionInfo->createTempQuestion($user);
        return $this->render('wrongEnter', array('data' => $reslut));
	}

	//保存题目头部
	public function actionSaveQuestionHead()
	{
		$id=$_POST['questionID'];
		$questionPrice=$_POST['questionPrice'];
		$provience=$_POST['provience'];
		$city=$_POST['city'];
		$country=$_POST['county'];
		$gradeid=$_POST['gradeID'];
		$subjectid=$_POST['subjectID'];
		$versionid=$_POST['versionID'];
		$source = $_POST['source'];//考试分类
		$year=$_POST['year'];
		$from=$_POST['from'];
		$nandu=$_POST['nandu'];
		$queslevel = $_POST['queslevel'];
		$capacity  = $_POST['capacity'];
		$tags=$_POST['tags'];
		$typeId=$_POST['tqtid'];
		$kid=$_POST['kid'];
		$name = '';
		$content=$_POST['name'];
		$textcontent = '';
		$questionInfo = new pos_WrongTopicService();
		$result = $questionInfo->saveQuestionHead($id,$provience,$city,$country,$gradeid,$subjectid,$versionid,$kid,$typeId,$source,$year,$nandu,$capacity,$tags,$name,$questionPrice,$queslevel,$from,$content,$textcontent);
		return $this->renderJSON($id);
	}

	// 新 保存题目内容
	public function actionSaveQuesContent(){
		if($_POST){
			$id = $_POST['id'];
			$analytical = $_POST['analytical'];

			$answerOptionJson = isset($_POST['answerOptionJson'])? $_POST['answerOptionJson'] : '';
			$answerContent = isset($_POST['answerContent']) ? $_POST['answerContent'] : '';
			$childQuesJson = isset($_POST['childQuesJson']) ? $_POST['childQuesJson'] : '';

			$saveType = $_POST['saveType'] ;
			$obj = new pos_WrongTopicService();
			$result = $obj->saveQuestionContent($id, $answerOptionJson, $answerContent, $analytical, $childQuesJson, $saveType);

			$jsonResult = new JsonMessage();
			if ($result->resCode == BaseService::successCode) {
				$jsonResult->success = true;
			} else {
				$jsonResult->success = false;
			}
			return $this->renderJSON($jsonResult);
		}

		$topic = $_GET['question'];
		$userId = user()->id;

		$questionInfo = new pos_WrongTopicService();

		$info = $questionInfo->queryTempQuesById($topic);
        $info->answerOptionJson = [];
		if(empty($info)){
			return $this->notFound();
		}

		$child = new Apollo_QuestionTypeService();
		$data = $child->queryQuesTypeSubs($info->tqtid);

		$childType = '';
		if(empty($data)){
			$childType = Html::tag('option', Html::encode($info->questiontypename), array('value' => $info->tqtid, 'showTypeId' => $info->showTypeId));
		}else{
			foreach ($data as $item) {
				$childType .= Html::tag('option', Html::encode($item->typeName), array('value' => $item->typeId, 'showTypeId' => $item->showTypeId));
			}
		}
        return $this->render("wrongEnterContent",array('info'=>$info,'childType'=>$childType));
	}

	//移除错题
	public function actionRemoveTopic()
	{
		$subId = $_POST['subjectId'];
		$userId = user()->id;
		$obj = new pos_WrongTopicService();
		$result = $obj->removeQuestion($subId, $userId);
		$jsonResult = new JsonMessage();
		if ($result->resCode == BaseService::successCode) {
			$jsonResult->success = true;
		} else {
			$jsonResult->success = false;
		}
		return $this->renderJSON($jsonResult);
	}
	//重答
	public function actionReAnswer()
	{
		$subId =app()->request->getQueryParam('t');
		$obj = new pos_WrongTopicService();
		$topic = $obj->questionSearch($subId);
		if(isset($_POST['subId'])){
			$type = $_POST['topicType']; // 题型
			$subId = $_POST['subId'];    // 大题ID
			$ansArr = [];
			if ($type == 1) {  //选择题
				$reanswer = $_POST['answer'];
				$answer = explode(",", $reanswer);
				$ansArr[] =  ['id' => $subId, 'answerOption' =>$reanswer, 'answerUrl' =>''] ;
			} else if ($type == 2) {
				$reanswer = implode(",", $_POST['answer']);
				$answer = explode(",", $reanswer);
				$ansArr[] = ['id' => $subId, 'answerOption' =>$reanswer, 'answerUrl' =>''] ;
			} elseif($type == 8) {
				$answer = $_POST['ImgUrl'];
				$ansArr[] =  ['id' => $subId, 'answerOption' =>'', 'answerUrl' => $answer] ;
			}else {
				$answer = $_POST['ImgUrl'];
				foreach ($answer as $k => $v) {
					foreach ($v as $j => $z) {
						if ($j == 'img') {
							$ansArr[] = ['id' => $k, 'answerOption' => '', 'answerUrl' => implode(",", $z)];
						} else {
							$ansArr[] =['id' => $k, 'answerOption' =>implode(",", $z), 'answerUrl' =>''] ;
						}
					}
				}
			}
			$updateAnswer = $obj->answerQuestion(json_encode($ansArr), $subId);
			$ansId = $updateAnswer->data->id;
			$jsonResult = new JsonMessage();
			if ($updateAnswer->resCode == BaseService::successCode) {
				return $this->redirect('/student/wrongtopic/wrong-detail?subId='.$subId.'&ansId='.$ansId);
			} else {
				$jsonResult->success = false;
			}
		}
        return $this->render('reanswer', array('topic' => $topic));
	}

	/**
	 * 图片重答
	 */
	public function actionImgReset()
	{
		$subId =app()->request->post('t');

		$answer = $_POST['ImgUrl'];
		$ansArr[] =  ['id' => $subId, 'answerOption' =>'', 'answerUrl' => $answer] ;
		$obj = new pos_WrongTopicService();
		$model = $obj->answerQuestion(json_encode($ansArr), $subId);
		$jsonResult = new JsonMessage();
		if ($model->resCode == BaseService::successCode) {
			$jsonResult->success = true;
			return $this->renderJSON($jsonResult);
		} else {
			$jsonResult->success = false;
			$jsonResult->message = "添加失败！";
			return $this->renderJSON($jsonResult);
		}
	}

	// 新 上传答案 显示历史详情
	public function actionWrongDetail()
	{
		$obj = new pos_WrongTopicService();
		$userId = user()->id;
		$answer = [];
		//通过答题进入
		if(isset($_GET['subId'])){
			$subId = $_GET['subId'];
			$ansId = $_GET['ansId'];
		}else{
			if (isset($_GET['study'])) {  // 学生学习记录 查看题目
				$subId = $_GET['study'];
				$free = $obj->questionSearch($subId);
				$itemId = $free->list[0]->subjectid;
			}else if(isset($_GET['exam'])){
				$topicgo = $obj->questionSearch('', $userId,'', '', '', $_GET['item']);
				if($_GET['exam'] == 1){ //继续做
					$subId = $topicgo->list[0]->id;
					$itemId = $_GET['item'];
					foreach($topicgo->list as $val){
						if(empty($val->userAnswers)){
							$subId = $val->id;
							break;
						}
					}
				}
				if($_GET['exam'] == 0){ //重新做
					$subId = $topicgo->list[0]->id;
					$itemId = $_GET['item'];
				}
			}
		}
		$prev = '';
		$next = '';
		if (isset($_GET['p'])) { // 上一题
			$itemId = $_GET['sub'];
			$subId = $_GET['t'];
			$prev = $obj->questionSearchUpDown($userId, '', $itemId, '', '', $subId, 1);
			if ($prev->cnt != 0) {
				$subId = $prev->question->id;
			}
		}
		if (isset($_GET['n'])) { // 下一题
			$itemId = $_GET['sub'];
			$subId = $_GET['t'];
			$next = $obj->questionSearchUpDown($userId, '', $itemId, '', '', $subId, 2);
			if ($next->cnt != 0) {
				$subId = $next->question->id;
			}
		}


		if (isset($_GET['key'])) { // 批改后 返回
			$itemId = $_GET['sub'];
			$subId = $_GET['t'];
			$nexttop = $obj->questionSearchUpDown($userId, '', $itemId, '', '', $subId, 2);

			if ($nexttop->cnt == 0) {
				$list = $obj->questionSearch('', $userId, '', '', '', $itemId);
				if (empty($list->list)) {
				return 	$this->actionManage();

				} else {
					$subId = $list->list[0]->id;
				}
			} else {
				$subId = $nexttop->question->id;
			}
		}

		// 查询题目信息
		$topic = $obj->questionSearch($subId);

		// 查询答题记录
		if(isset($ansId)){
			$answer = $obj->queryAnswerRec($ansId);
		}
		if (!empty($topic->list[0]->childQues)) {
			foreach ($topic->list[0]->childQues as $k => $v) {
				$num = count($v->userAnswers);
				if ($num == 0) {
					$history_answer = [];
				}else{
					for($i=1;$i<=$num;$i++){
						$history_answer[$num - $i][] = $v->userAnswers[$num - $i];
					}
				}

			}
		} else {
			$num = count($topic->list[0]->userAnswers);
			if ($num == 0) {
				$history_answer = [];
			}else{
				for($i=1;$i<=$num;$i++){
					$history_answer[$num - $i][] = $topic->list[0]->userAnswers[$num - $i];
				}
			}
		}

		if(empty($topic->list[0]->childQues)){
			$seltopanswernum = count($topic->list[0]->userAnswers);
			$selrightanswernum = 0;
			$errorAnswerNum = 0;
			if($topic->list[0]->showTypeId == 8){
				foreach ($topic->list[0]->userAnswers as $ab) {
					if($ab->ischecked == 1){
						if ($ab->answerRight == 1) {
							$selrightanswernum += 1;
						}elseif($ab->answerRight == 0){
							$errorAnswerNum += 1;
						}
					}
				}
			}else{
				foreach ($topic->list[0]->userAnswers as $ab) {
					if ($ab->answerRight == 1) {
						$selrightanswernum += 1;
					}elseif($ab->answerRight == 0){
						$errorAnswerNum += 1;
					}
				}
			}
		}else{
			$seltopanswernum = count($topic->list[0]->childQues[0]->userAnswers);
			$selrightanswernum = 0;
			$errorAnswerNum = 0;
			foreach ($topic->list[0]->childQues[0]->userAnswers as $ab) {
				if ($ab->answerRight == 1) {
					$selrightanswernum += 1;
				}elseif($ab->answerRight == 0){
					$errorAnswerNum += 1;
				}
			}
		}

		foreach ($topic->list as $key => $val) {
			// 将答案转化成数组
			$topic->list[$key]->answerContent = explode(",", $val->answerContent);
		}
        return $this->render("wrongDetail", array("topic" => $topic, "answer" => $answer, "history" => $history_answer, 'subId' => $subId, 'seltopanswernum' => $seltopanswernum, 'selrightanswernum' => $selrightanswernum, 'errorAnswerNum'=>$errorAnswerNum, 'prev'=>$prev,'next'=>$next));
	}

	// 批改 、展示批改
	public function actionCorrectPaper()
	{
		$subId = $_GET['t'];
		$count = app()->request->getQueryParam('c',null);
		$status = $_GET['a'];
		$obj = new pos_WrongTopicService();
		$topic = $obj->questionSearch($subId);
		if (!empty($topic->list[0]->childQues)){
			foreach ($topic->list[0]->childQues as $k => $v) {
				$piclist[] = $v->userAnswers[$count];
			}
		} else {
			$piclist[] = $topic->list[0]->userAnswers[$count];
		}
		$recSeq = $piclist[0]->recId;

        return $this->render("correctpaper", array('topic' => $topic->list, 'piclist' => $piclist, 'status' => $status, 'recseq' => $recSeq));
	}

	//批改图片
	public function actionAddAnswerPic()
	{
		$answer = '{"checkInfoList":' . $_POST['answer'] . '}';
		$picId = $_POST['pid'];
		$userId = user()->id;
		$obj = new pos_WrongTopicService();
		$result = $obj->checkAnswerPic($userId, $picId, $answer);
		$jsonResult = new JsonMessage();
		if ($result->resCode == BaseService::successCode) {
			$jsonResult->success = true;
		} else {
			$jsonResult->success = false;
		}
		return $this->renderJSON($jsonResult);
	}

	// 提交得分
	public function actionCheckQuestionAnswerRec()
	{
		$userId = user()->id;
		$recId = $_POST['recseq'];
		$score = $_POST['score'];
		$obj = new pos_WrongTopicService();
		$result = $obj->checkQuestionAnswerRec($userId, $recId, $score);
		$jsonResult = new JsonMessage();
		if ($result->resCode == BaseService::successCode) {
			$jsonResult->success = true;
		} else {
			$jsonResult->success = false;
		}
		return $this->renderJSON($jsonResult);
	}

	// 修改题目难度
	public function actionModifyComplexity()
	{
		$userId = user()->id;
		$topId = $_POST['tid'];
		$complexity = $_POST['val'];
		$obj = new pos_WrongTopicService();
		$result = $obj->modifyQuestionSomeInfo($topId, $userId, $complexity);
		$jsonResult = new JsonMessage();
		//$jsonResult->message = "修改失败！";

		if($result->resCode == BaseService::successCode){
			$jsonResult->success = true;
			$jsonResult->data = DegreeModel::model()->getDegreeName($complexity);
			return $this->renderJSON($jsonResult);
		}elseif($result->resCode == 100102){
			$jsonResult->success = false;
			$jsonResult->message = "您没有权限修改此题！";
			return $this->renderJSON($jsonResult);
		}
	}
	// 录入完成跳转页
	public function actionTopicFinish(){
		$status = $_GET['status'];
        return $this->render("topicfinish",array('status'=>$status));
	}
	//拍照录题
	public function actionTakePhotoTopic(){
		$model = new TakePhotoForm();
		$model->provience = loginUser()->getModel()->provience;
		$model->city = loginUser()->getModel()->city;
		$model->country = loginUser()->getModel()->country;
	//	$model->gradeid = loginUser()->getModel()->userClass[0]['gradeID'];
		$model->subjectid = loginUser()->getModel()->subjectID;
		$model->versionid = loginUser()->getModel()->textbookVersion;
		if(isset($_POST['TakePhotoForm'])){

			$picurls = app()->request->post('picurls',[]);
			$imgurls = app()->request->post('imgurls',[]);
			$_POST['TakePhotoForm']['content'] = implode(',',$picurls);

			$_POST['TakePhotoForm']['analytical'] = implode(',',$imgurls);
			$model->attributes = $_POST['TakePhotoForm'];

			if ($model->validate()) {
				$obj = new pos_WrongTopicService();
				$createQt = $obj->questionPicAdd($model,'','',user()->id);
				if ($createQt->resCode == BaseService::successCode) {
					return $this->redirect(url('student/wrongtopic/wro-top-for-item',array('item'=>$model->subjectid)));
				}
			}
		}
        return $this->render('takephototopic',array('model'=>$model));
	}

	//题目预览
	public function actionViewTopic(){
		$topId = $_POST['id'];
		$userId = user()->id;
		$obj = new pos_WrongTopicService();
		$result = $obj->queryTempQuesById($topId);
        return $this->renderPartial('_viewTopicData', array('item' => $result));
	}
}