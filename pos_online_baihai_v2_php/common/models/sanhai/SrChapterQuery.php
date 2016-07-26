<?php

namespace common\models\sanhai;

/**
 * This is the ActiveQuery class for [[SrChapter]].
 *
 * @see SrChapter
 */
class SrChapterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SrChapter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SrChapter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}