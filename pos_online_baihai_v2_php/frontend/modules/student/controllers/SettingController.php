<?php
namespace frontend\modules\student\controllers;
use common\models\JsonMessage;
use common\models\pos\SeAnswerQuestion;
use common\models\pos\SeClassMembers;
use common\models\pos\SeHomeworkRel;
use common\models\pos\SeInbox;
use common\models\pos\SeWrongQuestionBookInfo;
use common\models\pos\SeWrongQuestionBookSubject;
use frontend\components\StudentBaseController;
use frontend\components\WebDataCache;
use frontend\models\EditEmailForm;
use frontend\models\EditPasswordForm;
use frontend\models\UserForm;
use frontend\services\pos\pos_MessageSentService;
use frontend\services\pos\pos_PersonalInformationService;
use frontend\services\pos\pos_QuestionTeamAnswerService;
use frontend\services\pos\pos_UserRegisterService;
use Yii;
use yii\data\Pagination;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-9-19
 * Time: 上午9:54
 */
class SettingController extends StudentBaseController
{

	public function actions()
	{
		//私信部分
		return ['message-list' => [
			'class' => 'frontend\controllers\message_box\MessageListAction'
		],
			'view-message' => [
				'class' => 'frontend\controllers\message_box\ViewMessageAction'
			]
		];
	}

    public $layout = "lay_user";

    public function actionIndex(){
        $this->actionSetInfo();
    }
    /**
     *个人信息的修改
     */
    public function actionSetInfo()
    {
        $this->layout = "lay_user_info";
        $model = new UserForm();
        $model->parseUserInfo(loginUser()->getModel(false));
        if (isset($_POST['UserForm'])) {
            $model->attributes = $_POST["UserForm"];

            if ($model->validate()) {
                $student = new pos_UserRegisterService();
                $result = $student->editTeacherUserInfoByModel($model);
                if ($result->resCode == pos_UserRegisterService::successCode) {
                    loginUser()->getModel(false);
                    return $this->redirect(url("class/index",array("classId"=>$model->classId)));
                }
            } else {

            }
        }
        return $this->render("setInfo", array("model" => $model));
    }


    /**
     *修改邮箱
     */
    public function actionSetEmail()
    {
        $this->layout = "lay_user_info";
        $model = new EditEmailForm();
        if ($_POST) {

            $model->attributes = $_POST["EditEmailForm"];
            $model->email = loginUser()->getEmail();
            $afterEmail=$model->afterEmail;
            $emailUrl=explode("@",$afterEmail);
            $emailUrl=$emailUrl[1];
            if ($model->validate()) {
                //发送邮箱验证
                $this->sendActiveEmail($model->afterEmail);
                Yii::$app->user->logout();
                return $this->redirect("http://mail.".$emailUrl);
            }

        }
        return $this->render("setEmail", array("model" => $model));
    }
    /**
     * 发送邮件
     * @param $email
     */
    public   function sendActiveEmail($email)
    {
        //生成邮箱激活码
        $register = new pos_UserRegisterService();
        $result = $register->getActiviteTolken($email);
        $activateMailCode = $result->data->activateMailCode;
        $activeUrl = 'http://' . $_SERVER['HTTP_HOST'] . url('register/email-active') . '?guid=' . $activateMailCode . '&email=' . $email;
        $message = new YiiMailMessage;
        $message->view = "active_mail";
        $message->setBody(array("email" => $email, 'activeUrl' => $activeUrl), 'text/html');
        $message->subject = '三海用户修改邮箱激活';
        $message->addTo($email);
        $message->from = Yii::$app->params['adminEmail'];
        //发邮件
        Yii::$app->mail->send($message);
    }

    /**
     *AJAX修改邮箱
     */
    public function actionUpdateEmail()
    {
        $email = new pos_UserRegisterService();
        $updateResult = $email->updateEmail($_POST["email"], $_POST["afterEmail"], $_POST["passWd"]);
        $jsonResult = new JsonMessage();
        $jsonResult->success = $updateResult->resMsg;
        return $this->renderJSON($jsonResult);

    }

    /**
     *修改密码
     */
    public function actionChangePassword()
    {
       // $this->layout = "lay_user_info";

        $model = new EditPasswordForm();

        if ($_POST) {

            $model->attributes = $_POST['EditPasswordForm'];
	        $model->userId = user()->id;

            if ($model->validate()) {
	            Yii::$app->getSession()->setFlash('success','密码修改成功！');
	            return $this->redirect(['change-password']);
//                Yii::$app->user->logout();
//                return $this->redirect(Yii::$app->homeUrl);
            }
        }
        return $this->render("//publicView/setting/changePassword", array("model" => $model));
    }

    /**
     *修改头像
     */
    public function actionSetHeadPic()
    {
       // $this->layout = "lay_user_info";
        return $this->render("//publicView/setting/setHeadPic");
    }

    /**
     * AJAX修改头像
     */
    public function actionUpdateHeadPic()
    {
        $student = new pos_PersonalInformationService();
        $result = $student->updateHeadImg(user()->id, $_POST["headImgUrl"]);
        loginUser()->getModel(false);
        $jsonResult = new JsonMessage();
        $jsonResult->success = $result;
        return $this->renderJSON($jsonResult);
    }

	/**
	 * 个人中心
	 */

	public function actionMyCenter()
	{
        $proFirstime = microtime();
		$classId = loginUser()->getClassInfo()[0]->classID;

		$userId = user()->id;

		$pages = new Pagination();
		$pages->validatePage=false;
		$pages->pageSize = 2;
		//消息个数
		$obj = new pos_MessageSentService();
		$resultNum = $obj->stasticUserMessage($userId);
		//通知
		$noticeModel = new pos_MessageSentService();
		$noticeResult = $noticeModel->readerQuerySentMessageInfo($userId, '507', "507201", null, 3);

		//我的作业
        $studentMember = WebDataCache::getClassStudentMember($classId);

		$query = SeHomeworkRel::find()->active()->where(['classID'=>$classId]);
		$taskResult = $query->orderBy("createTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();

		//练一练
		$practiceModel = new pos_QuestionTeamAnswerService();
		$practiceResult = $practiceModel->searchQuestionTeam($userId,null,null,null,null,null,1);

		//答疑
		$questionQuery = SeAnswerQuestion::find()->where(['creatorID'=>user()->id])->active();
		$answerResult = $questionQuery->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();
		//错题集

//		$wrongModel = new pos_WrongTopicService();
//		$wrongResult = $wrongModel->questionSearch(null, $userId, null, $pages->pageSize, null, null, null, null, null);
//       $wrongQuestion= SeWrongQuestionBookInfo::find()->orderBy(['createTime' => SORT_DESC])->limit($pages->pageSize)->select('questionId')->column();
//       $testQuestion=ShTestquestion::find()->where(['in','id',$wrongQuestion])->all();

        $testQuestion=SeWrongQuestionBookInfo::find()->where(["userId"=>$userId,'isDetete'=>0])->orderBy(['createTime' => SORT_DESC])->limit($pages->pageSize)->all();

        \Yii::info('学生中心 '.(microtime()-$proFirstime),'service');
        return $this->render('myCenter',
			[
				'pages' => $pages,
				'resultNum'=>$resultNum,
				'classId'=>$classId,
				'noticeResult'=>$noticeResult->data,
				"taskResult" => $taskResult,
				'practiceResult'=>$practiceResult->data->list,
				'answerResult' => $answerResult,
				"testQuestion" => $testQuestion,
				'studentMember' => $studentMember,
			]);
	}

	/**
	 * 通知
	 */

	public function actionMyCenterNotice()
	{
		$noticeModel = new pos_MessageSentService();
		$noticeResult = $noticeModel->readerQuerySentMessageInfo(user()->id, '507', "507201", null, 3);
        return $this->renderPartial('_my_center_notice',['noticeResult'=>$noticeResult->data]);
	}

	/**
	 * 系统消息
	 */
	public function actionMyCenterSysMsg()
	{
        $classId = loginUser()->getClassInfo()[0]->classID;
		$data = new pos_MessageSentService();
		$result = $data->readerQuerySentMessageInfo(user()->id, '508', null, null, 3);
        return $this->renderPartial('_my_center_sysMsg',['result'=>$result->data, "classId"=>$classId]);
	}

	/**
	 * @throws CException
	 * 私信
	 */
	public function actionLetter()
	{

        $pages=new Pagination();
        $pages->pageSize=3;
        $query=SeInbox::find()->where(["senderId"=>user()->id, 'senderDel'=>'0'])->orWhere(["receiverId"=>user()->id, 'receiverDel'=>'0']);
        $pages->totalCount=3;
        $result=$query->offset($pages->getOffset())->limit($pages->getLimit())->orderBy('updateTime desc')->all();

		if (app()->request->isAjax) {
            return $this->renderPartial('//publicView/messagebox/_messageItems', array('data' => $result, 'pages' => $pages));

		}

        return $this->render('//publicView/messagebox/messageList', array('data' => $result, 'pages' => $pages, 'count' => $result));
	}

	/**
	 * @throws CException
	 * 我的作业
	 *
	 */
	public function actionWorkManage(){

		$pages = new Pagination();
		$pages->validatePage=false;
		$pages->pageSize = 2;

		$classInfo = loginUser()->getClassInfo();

		if(!empty($classInfo)){
			$classId = $classInfo[0]->classID;
		}else{
			$classId = null;
		}

		$subjectId = app()->request->getQueryParam('type','');

		$studentMember = SeClassMembers::find()->where(['classID'=>$classId, 'identity'=>20403])->count();
		$query = SeHomeworkRel::find()->active()->where(['classID'=>$classId]);

		if($subjectId != '')
		{
			$query->andWhere('homeworkId in (select id from se_homework_teacher where subjectId=:subjectId ) ',[':subjectId'=>$subjectId]);
		}
		$taskResult = $query->orderBy("createTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();

        return $this->renderPartial("_my_center_task", array( "taskResult" => $taskResult,'studentMember' => $studentMember));

	}

    /*
     *新错题集
     */
    public function actionWroTopForItem(){
        $pages = new Pagination();
        $pages->validatePage=false;
        $pages->pageSize = 2;
        $subjectId=app()->request->get('type');
        $userId = user()->id;
        $wrongSubject=SeWrongQuestionBookSubject::find()->where(['userId'=>$userId,'subjectId'=>$subjectId])->select('wrongSubjectId')->one();
           if(empty($wrongSubject)){
               $testQuestion=null;
           }else{
               $testQuestion=SeWrongQuestionBookInfo::find()->where(["userId"=>$userId,'wrongSubjectId'=>$wrongSubject->wrongSubjectId,'isDetete'=>0])->orderBy(['createTime' => SORT_DESC])->limit($pages->pageSize)->all();
           }
        return $this->renderPartial('//publicView/wrong/_wrong_question_list',['wrongQuestion'=>$testQuestion,'pages' => $pages]);
    }

	/**
	 * @throws CException
	 * 错题集
	 */
//	public function actionWroTopForItem()
//	{
//		$pages = new Pagination();$pages->validatePage=false;
//		$pages->pageSize = 2;
//
//		$subjectId = app()->request->getQueryParam('type', null);
//		$userId = user()->id;
//		$gradeId = app()->request->getQueryParam('grade', null);
//		$orderType = app()->request->getQueryParam('orderType', null);
//		$obj = new pos_WrongTopicService();
//		$list = $obj->questionSearch('', $userId, null, $pages->pageSize, $gradeId, $subjectId, $orderType, '', '');
//		$subject = SubjectModel::model()->getSubjectName($subjectId);
//
//        return $this->renderPartial('//publicView/wrong/_new_wrong_list',array(
//			"model" => $list,
//			"subject" => $subject,
//			'pages'=>$pages
//		));
//	}
}