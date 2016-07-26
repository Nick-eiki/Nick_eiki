<?php

namespace common\models\pos;

use Yii;

/**
 * This is the model class for table "se_paperQuesTypeRlts".
 *
 * @property integer $id
 * @property string $schoolLevelId
 * @property string $schoolLevel
 * @property string $subjectId
 * @property string $subject
 * @property string $paperQuesTypeId
 * @property string $paperQuesType
 * @property string $paperSection
 * @property string $isDelete
 */
class SePaperQuesTypeRlts extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_paperQuesTypeRlts';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_school');
    }

    /**
     * @inheritdoc
     * @return SePaperQuesTypeRltsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SePaperQuesTypeRltsQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['schoolLevelId', 'subjectId', 'paperQuesTypeId'], 'string', 'max' => 50],
            [['schoolLevel', 'subject', 'paperSection', 'isDelete'], 'string', 'max' => 20],
            [['paperQuesType'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'schoolLevelId' => 'School Level ID',
            'schoolLevel' => '学段',
            'subjectId' => 'Subject ID',
            'subject' => '科目',
            'paperQuesTypeId' => '试卷题型型id',
            'paperQuesType' => '试卷题型名称',
            'paperSection' => '分卷 1 2',
            'isDelete' => 'Is Delete',
        ];
    }

    /**
     * 根据学段科目展示题型
     */
    public function questionType($departments,$subjectid)
    {
        return SePaperQuesTypeRlts::find()->where(['schoolLevelId'=>$departments,'subjectId'=>$subjectid])->all();
    }
}
