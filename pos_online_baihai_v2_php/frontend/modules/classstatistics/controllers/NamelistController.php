<?php
namespace frontend\modules\classstatistics\controllers;

use common\models\pos\SeExamSchool;
use common\models\pos\SeViweExamReportExamPersonalScoreRankSearch;
use frontend\components\ClassesBaseController;
use Yii;
use yii\data\ActiveDataProvider;

class NamelistController extends ClassesBaseController
{
    public $layout = '@app/views/layouts/lay_new_classstatistic_v2';


    /**
     * @return string
     * 名单
     */
    public function actionIndex($examId, $classId,$selClassId=0, $subjectId = 0, $level = 0)
    {
        $proFirstime = microtime();
        $this->getClassModel($classId);
        //$seExamSchoolModel = $this->getSeExamSchoolModel($examId);
        $seExamSchoolModel = SeExamSchool::find()->where('schoolExamId=:schoolExamId', [':schoolExamId' => $examId])->one();

        if (!$seExamSchoolModel) {
            $this->notFound('不存在考试');
            return $seExamSchoolModel;
        }

        $classesList = $seExamSchoolModel->getClasses();
        $examsList = $seExamSchoolModel->getExamSubject()->all();

        $query = SeViweExamReportExamPersonalScoreRankSearch::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ]

        ]);



        $query->andWhere(['schoolExamId' => $examId]);

        if (empty($subjectId)) {
            $query->orderBy('totalScore desc');
        } else {
            $query->orderBy("sub$subjectId desc");
        }

        if (!empty($selClassId)) {
            $query->andWhere([
                'classId' => $selClassId,
            ]);
        }

        if (!empty($level)) {
            $totalScore = 0;
            $min = 0;
            $max = 0;
            if (empty($subjectId)) {
                $totalScore = $seExamSchoolModel->getTotalScore();

            } else {
                $totalScore = $seExamSchoolModel->getSubjectScoreById($subjectId);
            }

            switch ($level) {
                //高分
                case 1:
                    $min = $totalScore * 0.8;
                    $max = $totalScore+1;
                    break;
                //及格
                case 2:
                    $min = 0;
                    $max = $totalScore * 0.6;
                    break;
                //低分
                case 3:
                    $min = 0;
                    $max = $totalScore * 0.4;
                    break;

            }

            if (empty($subjectId)) {
                $query->totalBetween($min, $max);
            } else {
                $query->subjectIdBetween($subjectId, $min, $max);
            }


            //总分
        }

        \Yii::info('名单列表 '.(microtime()-$proFirstime),'service');
        if (yii::$app->request->getIsAjax()) {
            return $this->renderPartial('_namelist', ['examId' => $examId, 'classesList' => $classesList, 'examsList' => $examsList, 'dataProvider' => $dataProvider]);
        }

        return $this->render('index', ['examId' => $examId, 'classId' => $classId,'selClassId' => $selClassId,'seExamSchoolModel'=>$seExamSchoolModel, 'classesList' => $classesList, 'examsList' => $examsList, 'dataProvider' => $dataProvider]);

    }


}