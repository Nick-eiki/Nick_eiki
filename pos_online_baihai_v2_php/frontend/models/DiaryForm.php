<?php
namespace frontend\models;

use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-9-17
 * Time: 上午11:48
 */
class DiaryForm extends Model
{
    public $name;
    public $type;
    public $tingkeTitle;
    public $ketiTitle;
    public $content;


    public function rules()
    {
        return [
            [["name", "type", "tingkeTitle", "ketiTitle", "content"], "required",],
            [["name"], 'length', 'max' => 50,],
            [["type", "tingkeTitle", "ketiTitle"], 'numerical',],
            [["name", "type", "tingkeTitle", "ketiTitle", "content"], "safe", "on" => "search",]
        ];
    }
} 