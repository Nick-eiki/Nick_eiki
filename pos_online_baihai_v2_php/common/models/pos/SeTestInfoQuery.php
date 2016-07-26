<?php

namespace common\models\pos;

/**
 * This is the ActiveQuery class for [[SeTestInfo]].
 *
 * @see SeTestInfo
 */
class SeTestInfoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SeTestInfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SeTestInfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}