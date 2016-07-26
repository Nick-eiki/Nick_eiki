<?php

namespace common\models\pos;

/**
 * This is the ActiveQuery class for [[TestTEST]].
 *
 * @see TestTEST
 */
class TestTESTQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TestTEST[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TestTEST|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}