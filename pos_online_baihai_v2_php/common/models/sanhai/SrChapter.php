<?php

namespace common\models\sanhai;

use Yii;

/**
 * This is the model class for table "sr_chapter".
 *
 * @property integer $cid
 * @property string $pid
 * @property string $subject
 * @property string $grade
 * @property string $version
 * @property string $chaptername
 * @property string $isDelete
 * @property string $schoolLevel
 * @property string $schoolLength
 * @property string $remark
 * @property string $session
 * @property string $bookAtt
 */
class SrChapter extends SanhaiActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sr_chapter';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_sanku');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cid'], 'required'],
            [['cid'], 'integer'],
            [['pid', 'subject', 'grade', 'version', 'schoolLevel', 'schoolLength', 'session', 'bookAtt'], 'string', 'max' => 20],
            [['chaptername'], 'string', 'max' => 100],
            [['isDelete'], 'string', 'max' => 2],
            [['remark'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cid' => '章节id',
            'pid' => '父id',
            'subject' => '科目',
            'grade' => '年级',
            'version' => '教材版本',
            'chaptername' => '章节名称',
            'isDelete' => '是否删除，0未删除，1已删除',
            'schoolLevel' => '学部',
            'schoolLength' => '学制',
            'remark' => '备注',
            'session' => '学期，21301上学期，21302下学期',
            'bookAtt' => '教材属性',
        ];
    }

    /**
     * @inheritdoc
     * @return SrChapterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SrChapterQuery(get_called_class());
    }
}
