<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/8/5
 * Time: 9:56
 */
namespace frontend\controllers\answer;

use common\helper\KeyWordsHelper;
use common\models\pos\SeAnswerQuestion;
use common\models\pos\SeSchoolInfo;
use common\services\JfManageService;
use frontend\modules\teacher\models\teaQuestionPackForm;
use yii\base\Action;

class CreateAnswerAction extends Action
{

	/**
	 * 提问问题
	 */
	public function run()
	{
		$userid = user()->id;
		$subjectID = app()->request->getParam('type', '');
		$moreIdea = app()->request->getParam('more_idea', '0');
		if (isset($_POST['imgurls']) && !empty($_POST['imgurls'])) {
			$picurls = implode(',', $_POST['imgurls']);
		} else {
			$picurls = "";
		}

		$model = new SeAnswerQuestion();
		$dataBag = new teaQuestionPackForm();
		$classList = loginUser()->getClassAndMember();
		if (!empty($classList)) {
			$classID = $classList[0]->classID;
		} else {
			$classID = null;
		}
		//查询当天提问数
		$selectAnswer = $model->checkAnswerNum($userid);

		if ($selectAnswer < 2) {
			if (isset($_POST['teaQuestionPackForm'])) {
				$dataBag->attributes = $_POST['teaQuestionPackForm'];
				$model->creatorID = $userid;
				if ($dataBag->validate()) {
					$schoolID = loginUser()->schoolID;
					$schoolInfo =SeSchoolInfo::getOneCache($schoolID);
					//保存答疑
					$saveAnswer = $model->addAnswer($schoolInfo,$classID,$schoolID,$dataBag,$subjectID,$moreIdea,$picurls,$userid);

					if ($saveAnswer) {
//                    提出答疑增加积分
						$jfHelper = new JfManageService;
						$jfHelper->myAccount("pos-question", user()->id);
						return $this->controller->redirect(['answer-questions']);

					}
				}
			}
		}
		return $this->controller->render('@app/views/publicView/answer/addquestion', array('model' => $dataBag));
	}


}