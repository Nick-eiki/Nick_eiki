<?php

namespace common\models\pos;

/**
 * This is the ActiveQuery class for [[SeMAccounts]].
 *
 * @see SeMAccounts
 */
class SeMAccountsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SeMAccounts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SeMAccounts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}