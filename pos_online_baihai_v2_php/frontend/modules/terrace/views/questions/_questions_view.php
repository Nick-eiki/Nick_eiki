<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-24
 * Time: 上午11:36
 */
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\dicmodels\LoadSubjectModel;

$subject = LoadSubjectModel::model()->getData($department,''); //查询科目

?>

<div class="terr_top">
    <span class="tab_title">
        <?php if ($department == '20201') { ?>
            小学
        <?php } elseif ($department == '20202') { ?>
            中学
        <?php } else { ?>
            高中
        <?php } ?>

    </span>
    <ul class="tabs tabs_js">
        <?php
        foreach ($subject as $key => $v) {            ?>
            <li class="<?php echo $key == 0 ? "curr" : "" ?>"><a subjectId="<?php echo $v->secondCode; ?>" >
                    <?php echo $v->secondCodeValue; ?></a></li>
        <?php } ?>
    </ul>
</div>
<div class="tere_b">
    <?php foreach ($subject as $key => $item) { ?>
        <dl class="con" style="display:<?php echo $key == 0 ? "block" : "none" ?>">
            <dd class="con_out">
                <ul class="li_fl clearfix">
                    <?php $searchKnowledgePoint = KnowledgePointModel::searchLevelKnowledgePointToTree($item->secondCode, $department);
                    ?>
                    <?php foreach ($searchKnowledgePoint as $val) { ?>
                        <li><a href="<?php echo url('ku/questions/search-knowledge-point',array('kid'=> $val->id,'department'=>$department,'subjectid'=>$item->subjectId))?>" id=""><?php echo $val->name; ?></a></li>
                    <?php } ?>
                </ul>
            </dd>
        </dl>
    <?php } ?>
</div>