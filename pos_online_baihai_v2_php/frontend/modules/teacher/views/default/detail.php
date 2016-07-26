<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-1
 * Time: 下午5:08
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="教师-资料详情";
?>
<script type="text/javascript">
    $(function(){
        $('.dataBtn').click(function(){
            var $_this = $(this);
            var id = $_this.attr('collectID');
            var type =$_this.attr('typeId');
            var action = $_this.attr('action');
            $.post("<?php echo url('teacher/default/add-material')?>", {id: id,type:type,action:action}, function (data) {
                if (data.success) {
                    if (action==1){

                        $_this.attr('action',0).text('取消收藏');
                        $_this.prepend('<i class="ico2"></i>');
                                   }
                    else {
                        $_this.attr('action',1).text('收藏');
                        $_this.prepend('<i class="ico"></i>');
                    }
                } else {
                    popBox.alertBox(data.message);

                }
            });

        });
        $('#downNum').click(function(){
            var id= '<?php echo $model->id;?>';
            $.post("<?php echo url('teacher/default/get-down-num')?>",{id:id},function(data){
                if(data.success){
                    $("#downNum i").html(data.data);
                }else{
                    alert('错误');
                }
            })
        });
    })


</script>
<!--主体内容开始-->
    <div class="centLeft">
        <div class="data_title pr">
            <h3>资料详情</h3>
        </div>
        <div class="data_details details_d">
            <h4><?php echo $model->name;?></h4>
            <ul class="data_keywords_list clearfix">
                <li>
                    <p<?php echo $model->subjectname;?></p>
                </li>
                <li>
                    <p><?php echo $model->gradename;?></p>
                </li>
                <li>
                    <p><?php echo $model->versionname; ?></p>
                </li>
                <li class="data_source">
                    <p class="sou_btn"><a style="color: #0000ff;" href="<?php echo url('school/index',array('schoolId'=>$model->school));?>"><?php echo $model->schoolName;?></a></p>

                </li>
            </ul>
            <ul class="data_introduce_list ">
                <li><em>适用于:　</em><?php echo AreaHelper::getAreaName($model->provience);?> &nbsp;<?php echo AreaHelper::getAreaName($model->city);?>&nbsp;<?php echo AreaHelper::getAreaName($model->country);?></li>
                <li>
                    <?php if($model->contentType==2){?>
                        <em>章节讲解：</em>
                        <?php
                        if(isset($model->chapKids)){
                            foreach(ChapterInfoModel::findChapter($model->chapKids) as $key=>$item){
                                echo $item->chaptername;
                            } } }else{?>
                        <em>知识点讲解：</em>
                        <?php
                        if(isset($model->chapKids)){
                            foreach(KnowledgePointModel::findKnowledge($model->chapKids) as $key=>$item){
                                echo $item->name;
                            }  }  } ?>
                </li>
                <li>
                    <dl class="introduce_list">
                        <dt><em>教案介绍：</em></dt>
                        <dd><?php echo $model->matDescribe;?></dd>
                    </dl>
                </li>
            </ul>
            <button type="button" class="btn"  id="downNum">下载文件<span>(<i><?php echo $model->downNum; ?></i>)</span></button>
            <?php if($model->isCollected==1){ ?>
                <button class="dataBtn" type="button" action="0" collectID="<?php echo $model->id;?>" typeId="<?php echo $model->matType;?>"><i class="ico2"></i>取消收藏<span></span></button>
        <?php    }else{?>
                <button class="dataBtn" type="button" action="1" collectID="<?php echo $model->id;?>" typeId="<?php echo $model->matType;?>"><i class="ico"></i>收藏<span></span></button>
        <?php    }?>

        </div>
    </div>
    <div class="centRight">
        <div class="item Ta_teacher">
            <h4>Ta的老师</h4>
            <a class="more" href="#">更多</a>
            <ul class="teacherList">
                <li> <img src="../images/user_m.jpg"> 张三丰 </li>
                <li> <img src="../images/user_m.jpg"> 张三丰 </li>
                <li> <img src="../images/user_m.jpg"> 张三丰 </li>
                <li> <img src="../images/user_m.jpg"> 张三丰 </li>
                <li> <img src="../images/user_m.jpg"> 张三丰 </li>
                <li> <img src="../images/user_m.jpg"> 张三丰 </li>
                <li> <img src="../images/user_m.jpg"> 张三丰 </li>
                <li> <img src="../images/user_m.jpg"> 张三丰 </li>
                <li> <img src="../images/user_m.jpg"> 张三丰 </li>
            </ul>
        </div>
    </div>

<!--主体内容结束-->
<!--弹出框pop--------------------->

