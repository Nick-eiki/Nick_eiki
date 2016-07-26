<?php
namespace frontend\models;

use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/10/31
 * Time: 16:36
 */
class stuQuestionPackForm extends Model
{
    public $title;      //标题
    public $detail;     //问题补充

    /*
     * @return array
     */
    public function rules()
    {
        return [
            [["title"], "required"],
            [["title"], "safe"],
            [["detail"], "safe"],

        ];
    }


    /*
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            "title" => "title",
            "detail" => "detail",
        );
    }
}


