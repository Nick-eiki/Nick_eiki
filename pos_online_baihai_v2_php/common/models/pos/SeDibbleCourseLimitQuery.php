<?php

namespace common\models\pos;

/**
 * This is the ActiveQuery class for [[SeDibbleCourseLimit]].
 *
 * @see SeDibbleCourseLimit
 */
class SeDibbleCourseLimitQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SeDibbleCourseLimit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SeDibbleCourseLimit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}