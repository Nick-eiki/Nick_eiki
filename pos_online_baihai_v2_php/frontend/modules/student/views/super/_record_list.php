<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2015/6/25
 * Time: 17:29
 */
use yii\helpers\Html;

$lastTime=isset($lastTime)?$lastTime:null;
?>
<?php  foreach($superResult->diaryInfo as $v){

    ?>
    <dl class="QA_list">
        <?php if($v->firstTime!=$lastTime){ ?>
        <dt><em><?=substr($v->firstTime,0,4)?></em><?=substr($v->firstTime,5,7)?></dt>
             <?php } $lastTime=$v->firstTime; ?>
        <?php foreach($v->diaryList as $value){?>
            <dd>
                <div class="dot_line first_dot_line"><i></i></div>
                <div class="QA_cont">
                    <span class="arrow"></span>
                    <h5>[ <span><?=$value->subjectName.$value->typeName?></span> ] <?=Html::encode($value->title)?></h5>
                    <p class="gray_d time"><?=$value->createTime?></p>
                    <div class="form_list">
                        <?php if(!empty($value->content)){?>
                        <div class="row">
                            <div class="formL">
                                <label>详情:</label>
                            </div>
                            <div class="formR">
                                <?php $urlArray=explode(",",$value->content);
                                foreach($urlArray as $vv){?>
                                    <a class="fancybox" href="<?=$vv?>">
                                    <img src="<?=$vv?>" alt=" ">
                                    </a>
                                <?php }?>
                            </div>
                        </div>
                        <?php }?>
                        <div class="row row_font">
                            <div class="formL">
                                <label>总结:</label>
                            </div>
                            <div class="formR">
                                <span class="gray_555"><?=Html::encode($value->summary)?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </dd>
        <?php }?>
    </dl>
<?php }  ?>
<?php if ($pages->getPageCount() > $pages->getPage() + 1) { ?>
<a class="moreQA" onclick="return getRecordList('<?=$lastTime?>',<?php echo $pages->getPage() + 2 ?>);">更多</a>
<?php }?>
<script>
  var getRecordList=function(lastTime,page){
      $.get("<?=url('student/super/get-record-list')?>",{page:page,lastTime:lastTime},function(result){
          $(".moreQA").replaceWith(result);
      })
  };
  $(".fancybox").fancybox();
</script>