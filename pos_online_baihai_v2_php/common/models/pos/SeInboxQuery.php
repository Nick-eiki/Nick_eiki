<?php

namespace common\models\pos;

/**
 * This is the ActiveQuery class for [[SeInbox]].
 *
 * @see SeInbox
 */
class SeInboxQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SeInbox[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SeInbox|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}