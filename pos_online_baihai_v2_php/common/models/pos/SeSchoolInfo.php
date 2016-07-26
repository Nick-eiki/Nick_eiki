<?php

namespace common\models\pos;

use frontend\components\WebDataKey;
use Yii;

/**
 * This is the model class for table "se_schoolInfo".
 *
 * @property integer $schoolID
 * @property string $schoolName
 * @property string $nickName
 * @property string $department
 * @property string $lengthOfSchooling
 * @property string $createTime
 * @property string $updateTime
 * @property string $isDelete
 * @property string $schoolAddress
 * @property string $brief
 * @property string $provience
 * @property string $city
 * @property string $country
 * @property string $ispass
 * @property string $reason
 * @property string $creatorID
 * @property string $trainingSchool
 * @property string $logoUrl
 * @property string $newLenOfSch
 * @property string $newLenOfSchDate
 * @property string $disabled
 * @property string $newDepartment
 * @property string $isNeedReviewDepartment
 */
class SeSchoolInfo extends PosActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'se_schoolInfo';
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
     * @return SeSchoolInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeSchoolInfoQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['schoolID'], 'required'],
            [['schoolID'], 'integer'],
            [['brief'], 'string'],
            [['schoolName', 'nickName', 'schoolAddress', 'reason'], 'string', 'max' => 300],
            [['department', 'lengthOfSchooling', 'provience', 'city', 'country'], 'string', 'max' => 50],
            [['createTime', 'updateTime', 'creatorID', 'trainingSchool', 'newLenOfSch', 'newLenOfSchDate'], 'string', 'max' => 20],
            [['isDelete', 'ispass', 'disabled', 'isNeedReviewDepartment'], 'string', 'max' => 2],
            [['logoUrl'], 'string', 'max' => 200],
            [['newDepartment'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'schoolID' => '学校id',
            'schoolName' => '学校名称',
            'nickName' => '学校别名',
            'department' => '学部分布，可包含多个（小学，初中，高中等）',
            'lengthOfSchooling' => '学制分布，当包含小学，初中时，才会出现学制',
            'createTime' => '创建时间',
            'updateTime' => '最后一次修改时间',
            'isDelete' => '是否被删除，0：表示未删除，1：表示已删除，默认：0',
            'schoolAddress' => '学校地址',
            'brief' => '简介',
            'provience' => '省',
            'city' => '城市',
            'country' => '区县',
            'ispass' => '是否通过审核 0:未审核，1：以审核，2：审核未通过',
            'reason' => '未通过审核原因',
            'creatorID' => '创建人',
            'trainingSchool' => '是否是教培机构，0:普通学校，1：是教培机构',
            'logoUrl' => '学校logoUrl',
            'newLenOfSch' => '新的学校学制 20501六三学制 ;20502五四学制;02503五三学制',
            'newLenOfSchDate' => '新学制开始时间',
            'disabled' => '是否已经禁用 0：未禁用/激活/解禁/审核通过  1：已经禁用',
            'newDepartment' => '待审核的新学部（删除）',
            'isNeedReviewDepartment' => '是否需要审核学部，0不需要审核，1需要审核',
        ];
    }


    /**
     * @param $schoolId
     * @return array|SeSchoolInfo|null
     */
    public static function getOne($schoolId)
    {
       return self::find()->where(['schoolID' => $schoolId])->one();
    }

    /**
     * @param $schoolId
     * @return array|SeSchoolInfo|mixed|null
     */
    public static function getOneCache($schoolId)
    {
        if (intval($schoolId) <= 0) {
            return null;
        }

        $cache = Yii::$app->cache;
        $key = WebDataKey::SCHOOL_CACHE_KEY . $schoolId;
        $data = $cache->get($key);
        if ($data == false) {
            $data = self::getOne($schoolId);
            if ($data != null) {
                $cache->set($key, $data, 6000);
            }
        }
        return $data;
    }

}
