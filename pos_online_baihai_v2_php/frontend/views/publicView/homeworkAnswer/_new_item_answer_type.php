<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/17
 * Time: 15:27
 *    49  209    题型显示    1    单选题    1
 * 50    209    题型显示    2    多选题    1
 * 51    209    题型显示    3    填空题    1
 * 52    209    题型显示    4    问答题    1
 * 53    209    题型显示    5    应用题    1
 * 96    209    题型显示    7    阅读理解    1
 * 95    209    题型显示    6    完形填空    1
 */
use common\helper\QuestionInfoHelper;
use common\models\sanhai\ShTestquestion;
use frontend\components\helper\AreaHelper;
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\ViewHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>
<?php /** @var ShTestquestion $item */
$showType= $item->getQuestionShowType();
if ($showType == null) {
    ViewHelper::emptyView();
}



if (!isset($number)) {
    $number = '';
}


?>

<div class="paper">
    <input type="hidden" class="bigType" value="<?php echo $showType ?>">
    <input type="hidden" class="bigTitleID" name="answer[<?php echo $item->id ?>]" value=""/>
    <h5>题 <?php echo $homeworkResult->getQuestionNo($item->id); ?> </h5>

    <h6>
	    <?php if(!empty($item->year)){ ?>
	    【<?php echo $item->year ?>年】
	    <?php } ?>
	    <?php echo AreaHelper::getAreaName($item->provenance) ?>  <?php echo QuestionInfoHelper::getQuestionTypename($item->tqtid) ?></h6>

    <?php if ($showType == 1 || $showType == 2) { ?>
        <p><?php echo \frontend\components\helper\StringHelper::htmlPurifier($item->content) ?></p>

        <?php $isMaster = $item->getQuestionChildCache();
        if (!empty($isMaster)) {
            echo $this->render('//publicView/homeworkAnswer/_haschild_item_answer', ['childList' => $isMaster, 'mainId' => $item->id,'homeworkResult'=>$homeworkResult]);
        } else {
            ?>
            <div class="checkArea">
                <?php
                echo getHomeworkMainQuestionOption($item);
                ?>
            </div>
        <?php } ?>

    <?php } ?>

    <?php if ($showType == 3 || $showType == 4  || $showType == 5 || $showType == 6 || $showType == 7) { ?>
        <p><?php echo $item->content ?></p>
        <?php $isMaster = $item->getQuestionChildCache();
        echo $this->render('//publicView/homeworkAnswer/_haschild_item_answer', ['childList' => $isMaster, 'mainId' => $item->id,'homeworkResult'=>$homeworkResult]);
        ?>
    <?php } ?>


    <?php if ($showType == 8) { ?>
        <p><?php
            $imgArr = ImagePathHelper::getPicUrlArray($item->content);
            foreach ($imgArr as $imgVal) {
                echo '<img src="' . $imgVal . '" width="810px" alt="">';
            }?>
        </p>

    <?php } ?>
    <?php if ($showType == 9) { ?>
        <p><?php echo $item->content ?></p>

        <?php
        $isMaster = $item->getQuestionChildCache();
        if (!empty($isMaster)) {
            echo $this->render('//publicView/homeworkAnswer/_haschild_item_answer', ['childList' => $isMaster, 'mainId' => $item->id, 'homeworkResult' => $homeworkResult]);
        }else{
            $op_list = array(
                '0' => array('id' => '0', 'content' => '错'),
                '1' => array('id' => '1', 'content' => '对')
            );
            echo '<div class="checkArea">' . Html::radioList("answer[$item->id]", '', ArrayHelper::map($op_list, 'id', 'content'),
                    ['class' => "radio alternative", 'qid' => $item->id, 'tpid' => $showType, 'separator' => '&nbsp;', 'encode' => false]) . '</div>';
        }?>
    <?php } ?>
    <?php if( !empty($isAnswered)){?>
    <div class="btnArea clearfix"><span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span>
    </div>
    <div class="answerArea hide">
        <p><em>答案:</em>
            <span><?php echo getNewAnswerContent($item); ?></span>
        </p>
        <?php if($showType!= 8){?>
        <p><em>解析:</em>
            <?php echo $item->analytical; ?>
        </p>
        <?php }?>
    </div>
    <?php }?>
</div>
<hr>


