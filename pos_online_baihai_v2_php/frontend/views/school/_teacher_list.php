<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-9-15
 * Time: 上午11:41
 */
use frontend\components\helper\PinYinHelper;
use frontend\components\helper\ViewHelper;
use frontend\components\WebDataCache;

?>
<ol class="school_t clearfix">
    <?php
    if(empty($teacherList)){
	    ViewHelper::emptyView();
    }
    foreach ($teacherList as $item) {

    ?>

	<li>
		<img data-type='header' onerror="userDefImg(this);" src="<?= \frontend\components\helper\ImagePathHelper::getFaceIcon($item->headImgUrl)?>" style="width: 160px;height: 160px;" alt="">
		<a href="<?php echo url('teacher/default/index',array('teacherId'=>$item->userID))?>"><h4 class="name_t"><?= $item->trueName ?></h4></a>

		<h5><a title="" href="javacsript:;">班级：</a></h5>
		<dl class="classesList">
	    <?php foreach ($item->classInfo as $i): ?>
			<dd>
				<span><?=  $i->joinYear  ?>级（第<?php echo $i->classNumber ?>班）&nbsp;&nbsp;<?= WebDataCache::getDictionaryName($i->identity) ?></span>
                <em class="<?= PinYinHelper::firstChineseToPin(WebDataCache::getSubjectName($item->userID)) ?>">
                    <?php if(isset($i->subjectNumber) && !empty($i->subjectNumber)){ echo mb_strcut(WebDataCache::getSubjectName($item->userID), 0, 3, 'utf-8');}?>
                </em>

			</dd>
	    <?php endforeach ?>
			<dd class="more"> <a href="javacsript:;">更多……</a> </dd>
		</dl>
		<h6><a href="javacsript:;" class=" w70 class_teac">教研组：</a></h6>
		<dl class="tch_group_list">
			<?php foreach ($item->groupInfo as $val) { ?>
			<dd> <span><?= $val->groupName; ?>&nbsp;&nbsp;<?= WebDataCache::getDictionaryName($val->identity); ?></span></dd>
			<?php } ?>
			<dd class="more"> <a href="javacsript:;">更多……</a> </dd>
		</dl>
	</li>
    <?php } ?>
</ol>
    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#teacherList',
            'maxButtonCount' => 5
        )
    );
    ?>
<script>
    $(function(){
        //显示更多
        $('.classesList,.tch_group_list').each(function(index, element) {
            var ddSize=$(this).children('dd').size();
            if(ddSize>4){
                $(this).addClass('addMore');
            }
        });
        //ie7
        $('.classesList,.tch_group_list').mousemove(
            function(){
                var pa=$(this).parent('li');
                pa.css('z-index',100).siblings().css('z-index',1);
            });

        $('.school_t li').live('mouseover',function(){
            $(this).addClass('this');
        });

        $('.school_t li').live('mouseout',function(){
            $(this).removeClass('this');
        });

    })
</script>

