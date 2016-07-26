<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 2015/7/7
 * Time: 12:00
 */
use frontend\components\CHtmlExt;
use frontend\components\helper\ImagePathHelper;
use frontend\components\WebDataCache;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title='教研组—资料详情';

$arr= ImagePathHelper::resImage($model->url);
$subjectName = WebDataCache::getSubjectNameById($model->subjectid);
$gradeName = WebDataCache::getGradeName($model->gradeid);
$versionName = \frontend\models\dicmodels\EditionModel::model()->getEditionName($model->versionid);
$creator = WebDataCache::getTrueName($model->creator);
?>
<script>
    $(function(){
        $('.correctPaper').testpaperSlider();
    });
</script>
<div class="main_cont">

    <div class="title">
        <a href="javascript:history.go(-1)" class="txtBtn backBtn"></a>
        <h4>资料详情</h4>

    </div>

    <div>

        <div class="title item_title noBorder">
            <?php if(!empty($model->name)):?>
            <h4><?php echo CHtmlExt::encode($model->name);?></h4>
            <?php endif;?>
            <div class="title_r">
									<span class="gray_d">
                            <?php if(!empty($model->createTime)){echo date('Y-m-d H:i',strtotime($model->createTime));}?>
									</span>

            </div>
        </div>
        <div class="form_list sub_details_main">
            <div class="row">
                <?php if(!empty($subjectName)||!empty($gradeName)||!empty($versionName)):?>
                <p><?php echo $subjectName;?> &nbsp;<?php echo $gradeName;?>  &nbsp;<?php echo $versionName;?></p>
                <?php endif;?>
                <?php if(!empty($model->creator)):?>
                <p>作者： <a href="<?= url('teacher/default/index',  ['teacherId' => $model->creator]) ?>" class="underline blue"><?php echo $creator?></a> &nbsp;
                    <a href="<?php echo url('school/index', array('schoolId' => \common\helper\UserInfoHelper::getSchoolName($model->creator)[0])) ?>" class="underline blue"><?php echo \common\helper\UserInfoHelper::getSchoolName($model->creator)[1]?></a></p>
                <?php endif;?>
                <?php if(!empty($model->chapKids)): ?>
                <p>知识点:<?php echo KnowledgePointModel::getNamebyId($model->chapKids).ChapterInfoModel::getNamebyId($model->chapKids);?></p>
                <?php endif;?>
            </div>
            <?php if(!empty($model->matDescribe)){?>
            <div class="row">
                <div class="formL">
                    <label>资料描述：</label>
                </div>
                <div class="formR" style="width: 660px"> <span><?php echo CHtmlExt::encode($model->matDescribe); ?></span> </div>
            </div>
            <?php }?>
            <div class="sub_details_bottom">
                <a class="btn w90 bg_blue white" href="<?php echo url('ajax/download-file',array('id'=>$model->id));?>">下载</a>
                <?php if (count($arr) == 0) { ?>
                <a target="_blank" href="http://officeweb365.com/o/?i=5362&furl=<?=urlencode(ImagePathHelper::resUrl1($model->url)); ?>"
                   class="btn w90 bg_blue white">预 览</a>
                <?php } ?>
                <a href="javascript:;" class="btn white btn w90 bg_blue <?php if($isCollected ==0){echo 'collectionbtn';}?>">
                    <?php
                    if($isCollected ==1){
                        echo '已收藏';
                    }elseif($isCollected ==0){
                        echo '收藏';
                    }
                    ?>
                </a>

            </div>
            <br>
            <?php if(!empty($arr)): ?>
                <div class=" font16 tr pageCount" style="padding-right:10px"></div>
                <div class="correctPaper" style="border:1px solid #ddd; padding:30px">
                    <div class="testPaperWrap mc pr" style="width:650px">
                        <ul class="testPaperSlideList slid" style="width:auto">
                            <?php   foreach($arr  as  $i): ?>
                                <li><img src="<?=$i ?>"  /></li>
                            <?php endforeach ?>

                        </ul>
                        <a href="javascript:;" id="prevBtn" class="correctPaperPrev">上一页</a>
                        <a href="javascript:;" id="nextBtn" class="correctPaperNext">下一页</a>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('.collectionbtn').click(function(){
            var _this = $(this);
            var id ="<?= app()->request->getParam('id'); ?>";
            var type = "<?= app()->request->getParam('type');?>";

            $.post("<?= url('ajax/collect');?>",{id:id,type:type},function(data){
                if(data.success){
                    popBox.successBox(data.message);
                    location.reload();
                }else{
                    popBox.errorBox(data.message);
                }

            });
        });
    })
</script>