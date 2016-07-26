<?php
/**
 * Created by yangjie
 * User: Administrator
 * Date: 2015/8/17
 * Time: 14:02
 */
namespace console\controllers;

use common\models\pos\SeAnswerQuestion;
use common\models\pos\SeClassMembers;
use common\models\pos\SeSchoolInfo;
use common\models\pos\SeUserinfo;
use common\models\sanhai\ShTestquestion;
use common\models\search\Es_testQuestion;
use frontend\components\WebDataCache;
use yii\console\Controller;

class TestQuestionController extends Controller
{

    /**
     * 添加试题到索引
     */
    public function actionAdd()
    {

        Es_testQuestion::getDb()->createCommand()->deleteIndex(Es_testQuestion::index());

        $config = [
            "settings" =>
                ["analysis" =>
                    ["analyzer" =>
                        ["stem" =>
                            ["tokenizer" => "standard",
                                "filter" => ["standard", "lowercase", "stop", "porter_stem"]
                            ]
                        ]
                    ]
                ],
            "mappings" =>
                [ "test-question" =>
                    [ "dynamic" => true,
                        "_timestamp"=>[ "enabled"=> "true","store"=>"yes"],
                        "properties" =>
                            [ "content" =>
                                [ "type" => "string", "indexAnalyzer" => "ik", "searchAnalyzer"=>"ik" ]
                                ,
                                "answerContent" =>
                                    [ "type" => "string", "indexAnalyzer" => "ik", "searchAnalyzer"=>"ik" ]
                                ,
                                "tqName" =>
                                    [ "type" => "string", "indexAnalyzer" => "ik", "searchAnalyzer"=>"ik" ]
                            ],

                    ]
                ]
        ];


        Es_testQuestion::getDb()->createCommand()->createIndex(Es_testQuestion::index(), $config);


        foreach (ShTestquestion::find()->batch(10) as $customers) {
            /** @var ShTestquestion $item */
            foreach ($customers as $item) {

                $testQuestionModel = new  Es_testQuestion();
                $testQuestionModel->id = (int)$item->id;
                $testQuestionModel->provience = $item->provience;
                $testQuestionModel->city = $item->city;
                $testQuestionModel->country = $item->country;
                $testQuestionModel->gradeid = (int)$item->gradeid;
                $testQuestionModel->subjectid = (int)$item->subjectid;
                $testQuestionModel->versionid = (int)$item->versionid;
                $testQuestionModel->year = (int)$item->year;
                $testQuestionModel->school = $item->school;
                $testQuestionModel->complexity = $item->complexity;
                $testQuestionModel->capacity = $item->capacity;
                $testQuestionModel->Tags = $item->Tags;
                $testQuestionModel->operater = $item->operater;
                $testQuestionModel->createTime = (int)$item->createTime;
                $testQuestionModel->updateTime = (int)$item->updateTime;
                $testQuestionModel->tqName = $item->tqName;
                $testQuestionModel->content = $item->content;
                $testQuestionModel->answerOption = $item->answerOption;
                $testQuestionModel->answerContent = $item->answerContent;
                $testQuestionModel->analytical = $item->analytical;
                $testQuestionModel->childnum = (int)$item->childnum;
                $testQuestionModel->mainQusId = $item->mainQusId;
                $testQuestionModel->textContent = $item->textContent;
                $testQuestionModel->questionPrice = $item->questionPrice;
                $testQuestionModel->status = (int)$item->status;
                $testQuestionModel->isDelete = (int)$item->isDelete;
                $testQuestionModel->tqtid = $item->tqtid;
                $testQuestionModel->inputStatus = (int)$item->inputStatus;
                $testQuestionModel->quesLevel = (int)$item->quesLevel;
                $testQuestionModel->quesFrom = $item->quesFrom;
                $testQuestionModel->save();

            }

        }


    }

    public function  actionGetMap()
    {


        return Es_testQuestion::getDb()->createCommand()->getMapping();


    }


    public function  actionFind()
    {


        Es_testQuestion::find()->query(['bool' => ["should" => ["query_string" => [
            "default_field" => "_all",
            "query" => "去你妈"]]]])->andWhere(['provience'=>'001'])->all();


    }

    /**
     * 重建索引
     */
    public function  actionRebuildIndex()
    {

        Es_testQuestion::getDb()->createCommand()->deleteIndex(Es_testQuestion::index());

        $config = [
            "settings" =>
                ["analysis" =>
                    ["analyzer" =>
                        ["stem" =>
                            ["tokenizer" => "standard",
                                "filter" => ["standard", "lowercase", "stop", "porter_stem"]
                            ]
                        ]
                    ]
                ],
            "mappings" =>
                [ "test-question" =>
                    [ "dynamic" => true,
                        "_timestamp"=>[ "enabled"=> "true","store"=>"yes"],
                        "properties" =>
                            [ "content" =>
                                [ "type" => "string", "indexAnalyzer" => "ik", "searchAnalyzer"=>"ik" ]
                            ,
                             "answerContent" =>
                                [ "type" => "string", "indexAnalyzer" => "ik", "searchAnalyzer"=>"ik" ]
                            ,
                         "tqName" =>
                            [ "type" => "string", "indexAnalyzer" => "ik", "searchAnalyzer"=>"ik" ]
                        ],

                    ]
                ]
        ];


        Es_testQuestion::getDb()->createCommand()->createIndex(Es_testQuestion::index(), $config);
    //    TestQuestion::getDb()->createCommand()->setMapping('test-questions', $config);



        foreach (ShTestquestion::find()->where(['mainQusId'=>0])->batch(10) as $customers) {

            $testQuestionModelList=[];
            /** @var ShTestquestion $item */
            foreach ($customers as $item) {

                $testQuestionModel = $this->getItem($item);
//                $testQuestionModel->items=[];
//                $items=[];
//
//                $array=  ShTestquestion::find()->where(['mainQusId'=>$testQuestionModel->id])->all();
//                foreach($array as $i)
//                {
//                  $items[]=$this->getItem($i);
//
//                }
//                $testQuestionModel->items=$items;
//


                $testQuestionModelList[]=$testQuestionModel;
            }

            Es_testQuestion::InsertAll($testQuestionModelList);

        }

    }

    /**
     * @param $item
     * @return Es_testQuestion
     */
    public function getItem($item)
    {
        $testQuestionModel = new  Es_testQuestion();
        $testQuestionModel->id = (int)$item->id;
        $testQuestionModel->provience = $item->provience;
        $testQuestionModel->city = $item->city;
        $testQuestionModel->country = $item->country;
        $testQuestionModel->gradeid = (int)$item->gradeid;
        $testQuestionModel->subjectid = (int)$item->subjectid;
        $testQuestionModel->versionid = (int)$item->versionid;
        $testQuestionModel->year = (int)$item->year;
        $testQuestionModel->school = $item->school;
        $testQuestionModel->complexity = $item->complexity;
        $testQuestionModel->capacity = $item->capacity;
        $testQuestionModel->Tags = $item->Tags;
        $testQuestionModel->operater = $item->operater;
        $testQuestionModel->createTime = (int)$item->createTime;
        $testQuestionModel->updateTime = (int)$item->updateTime;
        $testQuestionModel->tqName = $item->tqName;
        $testQuestionModel->content = $item->content;
        $testQuestionModel->answerOption = $item->answerOption;
        $testQuestionModel->answerContent = $item->answerContent;
        $testQuestionModel->analytical = $item->analytical;
        $testQuestionModel->childnum = (int)$item->childnum;
        $testQuestionModel->mainQusId = $item->mainQusId;
        $testQuestionModel->questionPrice = $item->questionPrice;
        $testQuestionModel->status = (int)$item->status;
        $testQuestionModel->isDelete = (int)$item->isDelete;
        $testQuestionModel->inputStatus = (int)$item->inputStatus;
        $testQuestionModel->quesLevel = (int)$item->quesLevel;
        $testQuestionModel->tqtid=$item->tqtid;
        $testQuestionModel->provenance=$item->provenance;
        $testQuestionModel->quesFrom = $item->quesFrom;
        return $testQuestionModel;
    }


    public function  actionDelete($id)
    {

        Es_testQuestion::findOne(['id'=>$id])->delete();

    }

    public function  actionTimeout()
    {



    }


}