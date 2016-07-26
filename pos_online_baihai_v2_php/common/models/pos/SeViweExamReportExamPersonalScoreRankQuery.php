<?php

namespace common\models\pos;

/**
 * This is the ActiveQuery class for [[SeViweExamReportExamPersonalScoreRank]].
 *
 * @see SeViweExamReportExamPersonalScoreRank
 */
class SeViweExamReportExamPersonalScoreRankQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    public function totalBetween($min, $max)
    {
        $this->andWhere(['between', 'totalScore', $min, $max])->andWhere(['<>',"totalScore",$max]);
        return $this;
    }

    public function subjectIdBetween($subJectId,  $min, $max)
    {
        $this->andWhere(['between', "sub$subJectId", $min, $max])->andWhere(['<>',"sub$subJectId",$max]);
        return $this;
    }


    /**
     * @inheritdoc
     * @return SeViweExamReportExamPersonalScoreRank[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SeViweExamReportExamPersonalScoreRank|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}