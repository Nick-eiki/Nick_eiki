<?php
namespace frontend\models;

use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-9-17
 * Time: 上午11:48
 */
class VideoForm extends Model
{
    public $name;
    public $provience;
    public $city;
    public $country;
    public $gradeID;
    public $subjectID;
    public $version;
    public $classId;
    public $teacherId;
    public $url;
    public $content;


    public function rules()
    {
        return [
            [['name', 'provience', 'city', 'country', 'gradeID', 'subjectID', 'version', 'classId', 'url', 'content'], "required"],
            [['name'], 'length', 'max' => 50],
            [['url'], 'length', 'max' => 100],
            [['provience', 'city', 'country', 'gradeID', 'subjectID', 'version', 'classId'], 'numerical'],
            [['teacherId', 'name', 'schoolLevel', 'year', 'content'], "safe", "on" => "search"],
        ];
    }
} 