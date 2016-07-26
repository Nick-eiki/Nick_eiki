<?php
namespace common\models\search;
use common\elasticsearch\es_ActiveRecord;

/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2015/9/1
 * Time: 15:26
 */
class Material extends es_ActiveRecord
{
    public function attributes()
    {
        return [
            'id',
            'name',
            'matType',
            'provience',
            'city',
            'country',
            'gradeid',
            'subjectid',
            'versionid',
            'kid',
            'chapterId',
            'contentType',
            'school',
            'tags',
            'creator',
            'createTime',
            'updateTime',
            'matDescribe',
            'isDelete',
            'url',
            'disabled',
            'readNum',
            'downNum',
            'chapKids',
            'groupId',
            'access',
            'department',
            'isplatform'
        ];
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}