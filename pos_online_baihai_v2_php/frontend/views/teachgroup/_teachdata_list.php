<?php
/**
 * Created by PhpStorm.
 * User: aaa
 * Date: 2015/7/7
 * Time: 16:09
 */
use common\helper\DateTimeHelper;
use common\models\pos\SeShareMaterial;
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\ViewHelper;
use frontend\components\WebDataCache;
use yii\helpers\Html;

?>
<script>
    $(function(){
        $('.file_list li').hover(
            function(){
                $(this).children('.mask_link').show();
            },
            function(){
                $(this).children('.mask_link').hide();
            }

        )

    })

</script>

    <ul class="file_list clearfix tch_mentlist ">
        <?php if($model):?>
            <?php foreach($model as $val){?>
                <li>
	                <img src="<?php echo ImagePathHelper::getFilePic($val->url);?>" width="57" height="57" alt=""/>
                    <h6>
	                    <a href="javascript:;" title="<?php if(!empty($val->name)){echo Html::encode($val->name);}?>">
		                    <?php  if(!empty($val->name)){echo Html::encode($val->name);}?>
	                    </a>
                    </h6>
                    <p>
	                    <em class="file_btn">
		                    <?php
                            if ($val->matType == 1) {
                                echo "教案";
                            } elseif ($val->matType == 7) {
                                echo "教学计划";
                            } elseif ($val->matType == 8) {
                                echo "课件";
                            } elseif ($val->matType == 6) {
                                echo "素材";
                            } elseif ($val->matType == 99) {
                                echo "其他";
                            }?>
	                    </em>
	                    <?php if(!empty($val->creator)){ echo cut_str(WebDataCache::getTrueName($val->creator),8);}?>
                        <span>
	                        <?php if (null !=($shareMaterail = SeShareMaterial::getShareMaterialInfo($val->id))) {
                                echo date('Y-m-d H:i', DateTimeHelper::timestampDiv1000($shareMaterail->createTime));
                            }?>
                        </span>
                    </p>
                    <div class="mask_link hide">
                        <div class="mask_link_BG"></div>
                        <div class="mask_link_cont">
	                        <a class="read" href="<?php if(!empty($val->id)){echo url('teachgroup/teach-data-details',array('groupId'=>$groupId,'id'=>$val->id,'type'=>$val->matType));}?>">
		                        <i></i>阅读
	                        </a>
	                        <em>
		                        <?php if(!empty($val->readNum) && $val->readNum > 0){echo $val->readNum;}else{echo '0';}?>人已阅读
	                        </em>
                        </div>
                    </div>
                </li>
            <?php }
        else:
            ViewHelper::emptyView();
        endif;
        ?>
    </ul>
<?php
 echo \frontend\components\CLinkPagerExt::widget( array(
       'pagination'=>$pages,
        'updateId' => '#teachdata',
        'maxButtonCount' => 5
    )
);
?>