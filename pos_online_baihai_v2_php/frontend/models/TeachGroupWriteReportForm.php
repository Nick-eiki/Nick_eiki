<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/7/30
 * Time: 17:25
 */
namespace frontend\modules\teacher\models;
use yii\base\Model;

class TeachGroupWriteReportForm extends model
{
	public $report_title;      //标题
	public $report_conten;     //问题补充
	/*
	 * @return array
	 */
	public function rules()
	{

		return [
			[["report_title"], "required"],
			[["report_conten"], "safe"],
			[["detail"],"safe"],

		];
	}

	/*
	 * @return array
	 */
	public function attributeLabels(){
		return [
			"title" => "title",
			"detail" => "detail",
		];
	}
}