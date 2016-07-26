<?php
namespace frontend\models;

use yii\base\Model;

class TeachPlanForm extends Model
{
    public $lName;
    public $provience;
    public $city;
    public $county;
    public $grade;
    public $subject;
    public $version;
    public $type;
    public $knowledgePoint;
    public $school;
    public $tags;
    public $url;
    public $introduction;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['lName', 'provience', 'city', 'county', 'grade', 'subject', 'version', 'type', 'knowledgePoint', 'school', 'tags', 'url', 'introduction'], 'required'],
            [['lName', 'provience', 'city', 'county', 'grade', 'subject', 'version', 'type', 'knowledgePoint', 'school', 'tags', 'url', 'introduction'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            "lName" => "lName",
            "provience" => "provience",
            "city" => "city",
            "county" => "county",
            "grade" => "grade",
            "subject" => "subject",
            "version" => "version",
            "type" => "type",
            "knowledgePoint" => "knowledgePoint",
            "school" => "school",
            "tags" => "tags",
            "url" => "url",
            "introduction" => "introduction"
        );
    }

}
