<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-2
 * Time: 下午5:15
 */
use frontend\components\helper\ImagePathHelper;

?>
<div class="docPack pr">
    <ul class="docBagDetail_list clearfix">
        <?php foreach($model as $key=>$item){
            ?>
            <li class="pr"><img src="<?php echo  ImagePathHelper::getPicUrl($item->url) ?>" alt="上传图片" />
                <h4> <?php if($item->detailType==1){?>
                        [教案]
                    <?php   }elseif($item->detailType==2){ ?>
                        [讲义]
                      <?php       }elseif($item->detailType==7){ ?>
                        [教学计划]
                    <?php  } ?><a href="<?php echo url('teacher/briefcase/detail',array('id'=>$item->infoId))?>" class="paper_name"><?php echo $item->name;?></a></h4>


                <p>简介： <?php echo $item->brief;?></p>


                <p>上传时间:<?php echo $item->uploadTime;?></p>
                <p><a  class="a_button bg_blue viewBtn" href="<?php echo url('teacher/briefcase/detail',array('id'=>$item->infoId))?>">预览</a>
                    <a class="a_button bg_blue viewBtn changeBtn" href="<?php echo url('teacher/briefcase/update-briefcase',array('id'=>$id,'infoId'=>$item->infoId));?>">修改</a></p>
            </li>
        <?php } ?>
    </ul>

        <?php
         echo \frontend\components\CLinkPagerExt::widget( array(
               'pagination'=>$pages,
                 'updateId' => '#briefcase',
                'maxButtonCount' => 5
            )
        );
        ?>


</div>