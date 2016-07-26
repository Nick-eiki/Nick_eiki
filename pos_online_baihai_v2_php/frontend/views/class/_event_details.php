<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2016/1/4
 * Time: 9:55
 */
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\ViewHelper;

if($eventDetail!=null){
?>

<div class="memorabilia_detail_cont">
    <h5><?=$eventDetail->eventName?></h5>
   <?php $url=$eventDetail->eventPic;    if(!empty($url)){  ?>
     <div id="slide" class="slide slide830">
        <div class="slidePaperWrap mc">
            <ul id="slidePaperList" class="slidePaperList ">
                <?php

                    foreach($url as $v){
                    ?>
                <li name="111"><img src="<?=$v->picUrl?>"  alt=""/> <span></span></li>
                <?php  }?>
            </ul>
            <a href="javascript:;" id="prevBtn" class="slidePaperPrev">上一页</a> <a href="javascript:;" id="nextBtn" class="slidePaperNext">下一页</a> </div>
         <?php if(!empty($url)){?>
             <div class="slidClip">
                 <span class="ClipPrevBtn" onselectstart="return false">prev</span>
                 <div class="slidClipWrap">
                     <div class="slidClipArea">
                         <?php foreach($url as $v){?>
                             <a class="">
                                 <img src="<?=ImagePathHelper::imgThumbnail($v->picUrl,166,106)?>">
                             </a>
                         <?php  }?>
                     </div>

                 </div>
                 <span class="ClipNextBtn" onselectstart="return false">next</span>

             </div>
         <?php }?>
    </div>

    <?php } ?>
    <div>
        <?=$eventDetail->briefOfEvent  ?>
    </div>
</div>
<?php }else{
   ViewHelper::emptyView();
}?>
    <script>
        require(['app/classes/classes_memorabilia'],function(classes_memorabilia){

            classes_memorabilia.slide_rotating();

        })
    </script>

