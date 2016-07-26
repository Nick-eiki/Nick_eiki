<?php

namespace common\models\pos;

/**
 * This is the ActiveQuery class for [[SeWorkFile]].
 *
 * @see SeWorkFile
 */
class SeWorkFileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SeWorkFile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SeWorkFile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}