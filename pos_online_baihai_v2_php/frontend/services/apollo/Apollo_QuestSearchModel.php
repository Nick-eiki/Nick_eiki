<?php
namespace frontend\services\apollo;

/**
 * Created by PhpStorm.
 * User: yang
 * Date: 14-11-4
 * Time: 下午3:18
 */
class Apollo_QuestSearchModel
{
    public $provience='';
    public $city='';
    public $country='';
    public $schoolLevel='';
    public $gradeid='';
    public $subjectid='';
    public $versionid='';
    public $kid='';
    public $typeId='';
    public $provenance='';
    public $year='';
    public $school='';
    public $complexity='';
    public $capacity='';
    public $tags='';
    public $name='';
    public $content='';
    public $textContent='';
    public $answerOptionJson='';
    public $answerContent='';
    public $analytical='';
    public $questionPrice='';
    public $childQuesJson='';
    public $showTypeId='';
    public $operater='';
    public $userID ='';
    public $pageSize=10;
    public $currPage=1;

    /**
     * @return string
     */
    public function getKid()
    {
        return $this->kid;
    }

    /**
     * @param string $kid
     */
    public function setKid($kid)
    {
        if ($kid==0)
        {
            $this->kid='';
        }
        $this->kid = $kid;
    }


}