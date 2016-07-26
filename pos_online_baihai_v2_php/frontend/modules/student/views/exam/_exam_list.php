<?php
/**
 *
 * @var #VexamTypeArr|? $examTypeArr
 */
use frontend\components\helper\PinYinHelper;
use frontend\components\helper\StringHelper;
use frontend\components\helper\ViewHelper;
use frontend\models\dicmodels\ExamTypeModel;
use yii\helpers\Url;

/** @var Controller $this */
?>

<ul class="itemList">
    <?php if(!empty($examList)){foreach ($examList as $item){
        ?>
        <li>
            <div class="title item_title noBorder">
                <h4><a href="<?=Url::to(['test-detail','examID'=>$item->examID])?>"><?= $item->examName ?></a></h4>
                <div class="title_r">
                    <span class="gray"><?=$item->examTime?></span>
                    <span><a href="javascript:;" class="txtBtn gray_d <?=empty($item->examSubList)?'dis_viewDetail':'viewDetail'?>">查看详情<i></i></a></span>
                                <span class=" font16 test_score">总成绩<em
                                        class=" font30"><?= intval(from($item->examSubList)->sum('intval($v->stuSubScore)')) ?></em></span>
                </div>

            </div>
            <div class="testListWrap">
                <?php if(!empty($item->examSubList)){?>
                    <i class="arrow"></i>
                    <div class="objClip">
                        <?php foreach($item->examSubList as $value){?>
                            <em class="<?php echo PinYinHelper::firstChineseToPin($value->subjectName) ?>"><?=StringHelper::cutStr($value->subjectName,"1","")?></em>

                        <?php }?>
                    </div>
                <?php }?>
            <ul class="clearfix testList">
                <?php foreach ($item->examSubList as $i):?>
                    <li class="<?= PinYinHelper::firstChineseToPin($i->subjectName) ?>"><i></i>
                        <h5><?= $i->teacherName ?></h5>

                        <p>

                            <a href="<?=Url::to(['test-detail','examID'=>$item->examID])?>" class="a_button w80 bg_blue_l alpha">
								<?php
								if (ExamTypeModel::model()->isBigExam($item->type)) {
									if ($i->isHaveScore == 1) {
										echo "我的答卷";
									} else
										echo "上传答卷";

								} else {
									if ($i->isHaveScore == 1) {
										echo "我的答卷";
									} else
										echo "开始答题";

								}
								?>
								</a>
                            <a href="<?=Url::to(['test-detail','examID'=>$item->examID])?>" class="a_button w80 bg_blue_l alpha">科目总评</a>

                        </p>
                        <span class=" font16 status">
                            <?php if ($i->isHaveScore) {
                                echo $i->stuSubScore;
                            } else if (ExamTypeModel::model()->isBigExam($item->type)) {
                                if ($i->isUploadAnswer == 1) {
                                    echo "已上传";
                                } else
                                    echo "未上传";

                            } else {
                                if ($i->isUploadAnswer == 1) {
                                    echo "已答";
                                } else
                                    echo "未答";

                            }
                           ?>
                            </span>
                    </li>
                <?php endforeach ?>
            </ul>
                </div>
            <div class="clsSetupBox hide">
                <i class="arrow"></i>
                <span class="closeBtn"></span>

                <div class="form_list">
                    <div class="row">
                        <div class="formL">
                            <label>考试科目</label>
                        </div>
                        <div class="formR">
                            <ul class="multi_resultList testObjList">
                                <li id="1" data-score="140">语文</li>
                                <li id="2" data-score="100">数学</li>
                                <li id="3" data-score="100">英语</li>
                                <li id="4" data-score="100">历史</li>
                                <li id="5" data-score="100">政治</li>
                                <li id="6" data-score="100">历史</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row objScore hide">
                        <div class="formL">
                            <label>科目满分</label>
                        </div>
                        <div class="formR">
                            <ul class="objScoreList">
                                <!--<li>语文 <input type="text" class="text w30"></li>
                                <li>数学 <input type="text" class="text w30"></li>-->
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formL">
                            <label>考试时间</label>
                        </div>
                        <div class="formR">
                            <input type="text" class="text w150">
                            <button type="button" class="bg_blue_l w100">确定</button>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    <?php } }else{
     ViewHelper::emptyView();
    }?>


</ul>

    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
            'updateId' => '#upview',
            'pagination' => $pages,
            'maxButtonCount' => 5,
            'showjump'=>true
        )
    );
    ?>
<script>
    /*查看详情*/
    $('.viewDetail').click(function(){
        var pa=$(this).parents('li');
        pa.siblings('li').find('.testListWrap').show().removeClass('showTestList');
        pa.find('.testListWrap').show().toggleClass('showTestList');
        pa.siblings('li').find('.clsSetupBox').hide();
        pa.find('.clsSetupBox').hide();
    });
</script>
