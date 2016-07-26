<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-15
 * Time: 下午12:29
 */
use frontend\components\helper\StringHelper;
use frontend\components\helper\ViewHelper;

?>
<script>
    $(function(){

        $('.clearfix .font13').live('click',function(){
            if (!confirm('确定要删除吗？')) return false;
            var _this =$(this);
            var paperId  = _this.attr('paperId');
            $.post("<?php echo url('teacher/managepaper/delete-paper')?>",{paperId:paperId},function(data){
                 if(data.success){
                     location.reload();
                 }else{
                   alert('删除失败！');
                     return false;
                 }
            })
        });
        $('.teac_test_paper_list li').hover(function(){
            $(this).children('span').show();
            $(this).addClass('this');

        },function(){
            $(this).removeClass('this');
            $(this).children('span').show();
        })
    })
</script>

<ul class="teac_test_paper_list clearfix">
    <?php if(empty($data)){
        ViewHelper::emptyView();
    }else{
    foreach($data as $key=>$item){ ?>
    <li class="fl">
            <h4>
                    <a href="<?php echo url('teacher/exam/paper-preview',array('paperID'=>$item->paperId));?>"><?php echo $item->name;?></a>
               </h4>
    <dl class="clearfix">

        <dt class="fl">
			<?php if($item->getType==1){ ?>
			<a href="<?php echo url('/teacher/exam/paper-preview',array('paperID'=>$item->paperId));?>"> <img src="<?php echo publicResources_new(); ?>/images/test_paper_img.png" alt=""></a>
			<?php
			}elseif($item->getType==0){
				if(isset($item->imageUrls) && $item->imageUrls != null){
			?>
					<a href="<?php echo url('/teacher/exam/paper-preview',array('paperID'=>$item->paperId));?>"> <img src="<?php echo publicResources_new(); ?>/images/test_paper_img3.png" alt=""></a>
			<?php }else{ ?>
					<a href="<?php echo url('/teacher/exam/paper-preview',array('paperID'=>$item->paperId));?>"> <img src="<?php echo publicResources_new(); ?>/images/test_paper_img2.png" alt=""></a>
				<?php } }else{?>
				<a href="<?php echo url('/teacher/exam/paper-preview',array('paperID'=>$item->paperId));?>"> <img src="<?php echo publicResources_new(); ?>/images/test_paper_img2.png" alt=""></a>
			<?php } ?>
        </dt>
        <dd class="fl" >
            <strong>简介：</strong>
            <span title="<?php echo strip_tags($item->paperDescribe);?>">
			<?php
					echo StringHelper::cutStr(strip_tags($item->paperDescribe),40);
			?>
                </span>
        </dd>
        <dd class="fl">格式：
			<?php
			if($item->getType==1){
				echo '在线试卷';
			}elseif($item->getType==0){
				echo '图片';
			}else{
				echo 'Word';
			}
			?>
		</dd>
        <dd class="fl">创建时间：<?php echo date("Y-m-d H:i", strtotime($item->uploadTime));?></dd>
    </dl>
        <span class="txtDelBtn paper_txt font13" paperId="<?php echo $item->paperId;?>">删除</span>
    </li>
  <?php }  }?>
</ul>
    <?php
    if(isset($pages)){
         echo \frontend\components\CLinkPagerExt::widget( array(
               'pagination'=>$pages,
                'updateId' => '#update',
                'maxButtonCount' => 5
            )
        );
    }

    ?>