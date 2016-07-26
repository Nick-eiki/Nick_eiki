<?php

namespace common\models\pos;

/**
 * This is the ActiveQuery class for [[SeMeetingOfParents]].
 *
 * @see SeMeetingOfParents
 */
class SeMeetingOfParentsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SeMeetingOfParents[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SeMeetingOfParents|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}