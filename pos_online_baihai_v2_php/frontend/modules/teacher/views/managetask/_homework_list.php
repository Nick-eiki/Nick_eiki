<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/9/17
 * Time: 16:51
 */
use frontend\components\CLinkPagerNormalExt;
use frontend\components\helper\ViewHelper;
use yii\helpers\Url;

?>
<div class="form_list">
    <div class="row">
        <div class="formR">
        </div>
    </div>
</div>
<!--<div class="test_paper_sort clearfix">
    <p class="fl font14"> <span>排序：&nbsp;&nbsp;发布时间</span><em class="down ac"></em> </p>
    <p class="fl font14" style="margin-left:56px;"> <span>热度</span>  <em class="down"></em> </p>

</div>-->
<ul class="teac_test_paper_list teacprepare_list teacprepare_listcon work_teacher clearfix ">
    <?php  if(!empty($homeworkResult)){foreach ($homeworkResult as $v) { ?>

        <li class="fl clearfix">
            <dl class="clearfix">
                <dt class="fl"><img src="<?=publicResources_new()?>/images/test_paper_img3.png" alt=""></dt>
                <dd class="fl">
                    <h4><a href="<?=url::to(['library-details','homeworkID'=>$v->id])?>"><?= $v->name ?></a></h4>
                </dd>
                <dd class="fl"><span class="number">来源:平台</span></dd>
            </dl>
            <div class="teac_r fr"><span class="read"> <a href="<?=url::to(['library-details','homeworkID'=>$v->id])?>"> <i class="y"></i> <em>预览</em></a> </span>
            </div>
        </li>
    <?php } }else{
        ViewHelper::emptyViewByPage($pages);
    }?>
</ul>

<?php
echo CLinkPagerNormalExt::widget(array(
        'firstPageLabel' => false,
        'lastPageLabel' => false,
        'pagination' => $pages,
        'updateId' => '#updateHomework',
        'maxButtonCount' => 8
    )
);
?>

