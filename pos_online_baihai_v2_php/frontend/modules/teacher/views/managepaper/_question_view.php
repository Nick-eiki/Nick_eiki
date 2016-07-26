<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 14-10-24
 * Time: 上午10:57
 */
use frontend\components\helper\LetterHelper;

?>
<?php foreach($model as $key=>$val){?>
<div class="torso">1.<?php echo $val->content;  ?></div>
<ol>
    <?php if($val->showTypeId == '1'){?>
        <?php
        $option = json_decode($val->answerOption);
        $option=is_array($option)?$option:array();
        foreach($option as $option_res){
            echo '<li><input type="radio" class="radio" name="aaa">';
            echo '<i>'.LetterHelper::getLetter($option_res->id).'&nbsp;&nbsp;</i>'.$option_res->content.'</li>';
        }
        ?>
    <?php }elseif($val->showTypeId == '2'){?>
        <?php
        $option = json_decode($val->answerOption);
        $option=is_array($option)?$option:array();
        foreach($option as $option_res){
            echo '<li><input type="checkbox" class="checkbox" name="aaa">';
            echo '<i>'.LetterHelper::getLetter($option_res->id).'&nbsp;&nbsp;</i>'.$option_res->content.'</li>';
        }
        ?>
    <?php }?>
</ol>
<p>[答案]</p>
<span><?php echo $val->answerContent;?></span>
<p class="analyze clearfix"><span class=" fl j">[解析]</span><span class="fr n">难易程度：<i><?php echo $val->complexityText;?></i></span></p>
<div class="st">
    <?php echo $val->analytical;?>。
</div>
<?php }?>



