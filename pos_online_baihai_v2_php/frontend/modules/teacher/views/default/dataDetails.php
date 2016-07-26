<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-10
 * Time: 上午10:05
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="教师-资料详情";
?>

    <script type="text/javascript">
        $(function(){
            $('.dataBtn').click(function(){
                $(this).children('i').addClass('ico2')
            })
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
                    <p><?php echo $model->subjectname;?></p>
                </li>
                <li>
                    <p><?php echo $model->gradename;?></p>
                </li>
                <li>
                    <p><?php echo $model->versionname;?></p>
                </li>
                <li class="data_source">
                    <p class="sou_btn sou_btn_js"><a style="color: #0000ff;" href="<?php echo url('school/index',array('schoolId'=>$model->school));?>"><?php echo $model->schoolName;?></a></p>
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
                                echo $item->chaptername."&nbsp;";
                            } } }else{?>
                        <em>知识点讲解：</em>
                        <?php
                        if(isset($model->chapKids)){
                            foreach(KnowledgePointModel::findKnowledge($model->chapKids) as $key=>$item){
                                echo $item->name."&nbsp;";
                            }  }  } ?>
                </li>
                <li>
                    <dl class="introduce_list">
                        <dt><em>资料介绍：</em></dt>
                        <dd><?php echo $model->matDescribe;?></dd>
                    </dl>
                </li>
            </ul>
            <button type="button" class="bg_blue"  id="downNum">下载教案<span>(<i class="downNum"><?php echo $model->downNum;?></i>)</span></button>
            <button type="button" class="bg_orenge dataBtn"><i class="ico"></i>收藏讲义<span></span></button>
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
