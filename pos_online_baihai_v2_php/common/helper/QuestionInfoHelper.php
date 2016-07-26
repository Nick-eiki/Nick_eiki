<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/19
 * Time: 18:56
 */

namespace common\helper;


use common\models\sanhai\SeDateDictionary;
use common\models\sanhai\ShTestquestion;
use frontend\components\WebDataCache;
use frontend\components\WebDataKey;
use Yii;

class QuestionInfoHelper {

    /**
     * 题目详细
     * @param $id
     * @return array|ShTestquestion|null
     */
    public static function Info($id){
        $questionInfo = ShTestquestion::find()->where(['id'=>$id])->one();

        return $questionInfo;
    }

    /**
     * 题目详细缓存
     * @param $id
     * @return array|ShTestquestion|mixed|null
     */
    public static function InfoCache($id){
        $cache = Yii::$app->cache;
        $key = WebDataKey::HOMEWORK_GET_QUESTION_DATA_BY_ID_KEY . $id;
        $data = $cache->get($key);
        if($data===false) {
               $data=self::Info($id);
            $cache->set($key,$data,600);
        }
        return $data;

    }


    /**
     * 根据题目tqtid 查询展示类型
     * @param $id
     * @return array|string
     */
    public static  function  getQuestionShowtype($id){

        $date = SeDateDictionary::find()->where(['secondCode'=>$id])->one();


        return $date->reserve1;
    }

    /**
     * 获取题目类型名字
     * @param $id
     * @return array|string
     */
    public static function getQuestionTypename($id){

        $data = WebDataCache::getDictionaryName($id);
        return $data;
    }



} 