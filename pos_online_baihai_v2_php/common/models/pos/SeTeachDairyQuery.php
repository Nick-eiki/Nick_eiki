<?php

namespace common\models\pos;

/**
 * This is the ActiveQuery class for [[SeTeachDairy]].
 *
 * @see SeTeachDairy
 */
class SeTeachDairyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SeTeachDairy[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SeTeachDairy|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}