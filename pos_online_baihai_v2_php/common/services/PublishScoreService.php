<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 2015/10/22
 * Time: 19:18
 */
namespace common\services;

use Httpful\Mime;
use Httpful\Request;
use Yii;

class PublishScoreService
{
    public $url = '';

    function __construct()
    {
        $this->url = Yii::$app->params['scoreStatistics'];
    }

    public function publishScore($examId)
    {
        $result = Request::get($this->url . 'databusiness/examReport/addSchoolExamToReport.do?schoolExamId=' . $examId)
            ->contentType('')
            ->sendsType(Mime::FORM)
            ->send();
        if ($result->body->resCode == '000') {
            return $result->body->resMsg;
        } else {
            return array();
        }

    }

    public static function model()
    {
        return new self();
    }
}