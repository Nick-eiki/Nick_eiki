<?php

namespace common\models\pos;

/**
 * This is the ActiveQuery class for [[SeTeacherMaterial]].
 *
 * @see SeTeacherMaterial
 */
class SeTeacherMaterialQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SeTeacherMaterial[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SeTeacherMaterial|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}