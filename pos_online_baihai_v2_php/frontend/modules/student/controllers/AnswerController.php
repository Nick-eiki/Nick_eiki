<?php
namespace frontend\modules\student\controllers;
use common\models\pos\SeAnswerQuestion;
use frontend\components\StudentBaseController;
use yii\data\Pagination;

/**
 * Created by Unizk.
 * User: ysd
 * Date: 2014/10/31
 * Time: 11:56
 */
class AnswerController extends StudentBaseController
{
	public $layout = "lay_user";
    public function actions()
    {
        //部分
        return ['add-question' => [
            'class' => 'frontend\controllers\answer\CreateAnswerAction'
        ],
            'update-question' => [
                'class' => 'frontend\controllers\answer\UpdateAnswerAction'
            ]
        ];
    }

	/**
	 * 学生问题列表
	 * @return string
	 */

	public function actionAnswerQuestions()
	{
		$pages = new Pagination();
		$pages->validatePage=false;
		$pages->pageSize =5;
		$keyWord = app()->request->getParam('keyWord', '');
		$questionQuery = SeAnswerQuestion::find()->active()->andWhere(['creatorID'=>user()->id]);

		if(!empty($keyWord)){
			$questionQuery->andWhere(['like','aqName',$keyWord]);
		}

		$pages->totalCount = $questionQuery->count();
		$questionList = $questionQuery->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();

		if (app()->request->isAjax) {
			return $this->renderPartial('//publicView/answer/_answer_list', array('modelList'=>$questionList,'pages' => $pages));
		}
		return $this->render('answerQuestions', array('modelList' => $questionList, 'pages' => $pages));
	}

}
