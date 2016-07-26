<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-5
 * Time: 下午4:01
 */
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="学生-收藏夹-视频详情";
?>
<script type="text/javascript">
    $(function(){
        $('.del').bind('click',function(){
           var  $this = $(this);
            var collectID=$this.attr("collectID");
            $.post("<?php echo url("student/default/del-collection")?>", {collectID: collectID}, function (data) {
                if (data.success) {
                    location.reload();
                }else{
                    popBox.alertBox(data.message);
                }
            });
        });
        $('.add').bind('click',function(){
           var $this =$(this);
           var collectID=$this.attr("collectID");
            $.post("<?php echo url("student/default/add-collection")?>", {collectID: collectID}, function (data) {
                if (data.success) {
                    location.reload();
                }else{
                    popBox.alertBox(data.message);
                }
            });
        })
    })
</script>


<!--主体内容开始-->

    <div class="centLeft">

        <div class="data_title pr">
            <h3>视频详情</h3>
            <div>
                <?php
                if($model->isFavorite ==1){?>
                    <button type="button" class="a_button bg_red del" collectID="<?php echo $model->collectID;?>">取消收藏</button>
              <?php   }else{ ?>
                    <button type="button" class="a_button bg_orenge add" collectID="<?php echo $model->lid;?>">收藏</button>
              <?php   }?>

            </div>
        </div>
        <div class=" item video2">
            <div class="diary_text">
                <h4><?php echo $model->videoName ?></h4>

                <ul class="course_list clearfix">
                    <li><?php echo $model->subjectName; ?></li>
                    <li><?php echo $model->gradeName; ?></li>
                    <li><?php echo $model->versionName; ?></li>
                    <li class="blue">
                        <span><a href="<?php echo url('school/index',array('schoolId'=>$model->schoolID));?>"><?php echo $model->schoolName;?></a></span>


                    </li>
                </ul>
                <h5>讲义介绍：</h5>
                <p style=""><?php echo $model->introduce; ?></p>
                <h6>课时安排：</h6>
                <?php foreach ($model->lessoninfo as $key => $val) {
                ?>
                <div class="video clearfix">
                    <span>第<?php echo $val->cNum; ?>堂课</span>
                    <ul class="clearfix video_li">
                        <li><img src="<?php echo publicResources() . $val->videoUrl; ?>" alt=""></li>
                    </ul>
                    <ul class="clearfix video_ul">
                        <li><?php echo $val->cName; ?></li>
                        <li> <?php if (isset($val->kcid)) {
                                if ($val->type == 0) {
                                    ?>
                                    知识难点：
                                    <?php
                                    foreach (KnowledgePointModel::findKnowledge($val->kcid) as $key => $item) {
                                        echo $item->name . "&nbsp;";
                                    }
                                } else {
                                    ?> 章节难点：<?php
                                    foreach (ChapterInfoModel::findChapter($val->kcid) as $key => $item) {
                                        echo $item->chaptername . "&nbsp;";
                                    }
                                }
                            } ?></li>
                        <li><em><a href="#"  id="<?php echo $val->teachMaterialID; ?>">讲义名称:<?php echo $val->teachMaterialName; ?></a></em><i></i></li>
                    </ul>
                    <div class="video_mix">
                        视频播放器插件
                    </div>
                </div>
                <?php } ?>
            </div>

        </div>

    </div>
    <div class="centRight">
        <div class="item Ta_teacher">
            <h4>Ta的老师</h4>
            <a class="more" href="#">更多</a>
            <ul class="teacherList">
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
            </ul>


        </div>
    </div>

<!--主体内容结束-->


<!--弹出框pop--------------------->
<!--班级荣誉-->
<div id="honour" class="add_honour popoBox hide " title="班级荣誉">
    <div class="impBox">
        <form>
            <div class="honourT clearfix">
                <label>荣誉：</label>
                <div class="our">
                    <input type="text" class="text text_tt">
                    <i class="add">aaaaaaa</i>
                    <span class="a_button bg_red add_btn">添加</span>
                </div>
            </div>
            <ul class="imp_list">
                <li>
                    <div class="m">
                        <strong class="tt">我班在学籍运动会上跳绳比赛中勇获第一名</strong>
                        <a href="javascript:" class="edit">编辑</a><a href="javascript:" class="del">删除</a>
                    </div>
                    <div class="b" style="display:none;">
                        <input type="text" class="text text_js">
                        <a href="javascript:;" class="a_button bg_red ok">确定</a><a href="javascript:" class="a_button bg_gray no">取消</a>
                    </div>
                </li>
            </ul>
        </form>
    </div>
</div>
