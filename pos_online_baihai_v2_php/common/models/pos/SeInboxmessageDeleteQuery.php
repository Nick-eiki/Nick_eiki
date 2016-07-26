<?php

namespace common\models\pos;

/**
 * This is the ActiveQuery class for [[SeInboxmessageDelete]].
 *
 * @see SeInboxmessageDelete
 */
class SeInboxmessageDeleteQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SeInboxmessageDelete[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SeInboxmessageDelete|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}