<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-20
 * Time: 下午4:51
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="学生设置-收藏详情";
?>
<!--主体内容开始-->
<script type="text/javascript">
    $(function(){
        $('#downNum').click(function(){
            var id= '<?php echo $model->id;?>';
            $.post("<?php echo url('student/collection/get-down-num')?>",{id:id},function(data){
                if(data.success){
                    $("#downNum i").html(data.data);
                }else{
                    popBox.alertBox(data.message);
                }
            })
        })
    })
</script>

    <div class="currentRight grid_16 push_2 stu_Detail_div stu_data">
        <h3>讲义名称</h3>
        <hr/>
        <div class="wd_details">
            <h4><?php echo $model->name;?></h4>
            <ul class="wd_keywords_list clearfix">
                <li>
                    <p><?php echo $model->subjectname;?></p>
                </li>
                <li>
                    <p><?php echo $model->gradename;?></p>
                </li>
                <li>
                    <p><?php echo $model->versionname;?></p>
                </li>
                <li class="wd_source">
                    <p class="sou_btn"><a style="color: #0000ff;" href="<?php echo url('school/index',array('schoolId'=>$model->school));?>"><?php echo $model->schoolName;?></a></p>

                </li>
            </ul>
            <ul class="wd_introduce_list ">
                <li><em>适用于:</em><?php echo AreaHelper::getAreaName($model->provience);?> &nbsp;<?php echo AreaHelper::getAreaName($model->city);?>&nbsp;<?php echo AreaHelper::getAreaName($model->country);?></li>
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
                <li><em>讲义介绍：</em><?php echo $model->matDescribe;?></li>
            </ul>

            <button class="bg_blue dataBtn" type="button" id="downNum">下载教案<span>(<i><?php echo $model->downNum;?></i>)</span></button>

        </div>
    </div>

<!--主体内容结束-->
