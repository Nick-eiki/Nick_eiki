<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-26
 * Time: 下午4:10
 */
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */
$this->title="平台--小学";

?>

    <script type="text/javascript">
        $(function(){
//搜索按钮切换
            $('.terrace_btn_js span').bind('click',function(){
                $(this).addClass('s_btn').siblings('span').removeClass('s_btn');

            })
        })
    </script>

<!--主体内容开始-->

        <!--参照网址http://wiki.ciwong.com/wiki-->
        <div class="class_c grid_24 clearfix tch replace">
            <div class="currentLeft grid_16 primary">
                <?php foreach( $subject as $key=>$item){
                    ?>
                <div class="primary_list">
                    <div class="primary_t"><h3 class="fl"><?php echo $item->secondCodeValue;?>知识点分类</h3>
                        <a href="<?php echo url('ku/questions/search-knowledge-point',array('department'=>$department,'subjectid'=>$item->secondCode))?>" class="fr">&gt;&gt;</a></div>
                    <ul>
                        <?php
                        $searchKnowledge=KnowledgePointModel::searchLevelKnowledgePointToTree($item->subjectId, $department);
                        foreach($searchKnowledge as $key=>$val){?>

                            <li><a href="<?php echo url('ku/questions/search-knowledge-point',array('kid'=> $val->id,'department'=>$department,'subjectid'=>$item->subjectId))?>" id=""><?php echo $val->name; ?></a></li>
                    <?php    }?>


                    </ul>
                </div>
                <?php    }?>
            </div>
            <div class="centRight">
                <div class="centRightT">
                    <a href="classHandsin.html" class=" outAdd_btn B_btn120">设置手拉手班级</a> </div>
                <div class="centRightT clearfix">
                    <p class="title titleLeft"> <span>手拉手班级</span><i></i> </p>
                    <hr>
                    <dl class="list_dl clearfix">
                        <dt><img src="../images/pic.png" alt="" width="90" height="90"></dt>
                        <dd>
                            <h3>177班</h3>
                        </dd>
                        <dd><span>学校：</span>北京人大附中</dd>

                        <dd><span>成员：</span>30名学生</dd>
                    </dl>
                </div>
                <div class="centRightT">

                    <ul class="class_list clearfix">
                        <li><a href="#"><img src="../images/user_s.jpg" alt="" title="北京"></a></li>
                        <li><a href="#"><img src="../images/user_s.jpg" alt="" title="北京"></a></li>
                        <li><a href="#"><img src="../images/user_s.jpg" alt="" title="北京"></a></li>
                    </ul>
                </div>
                <div class="centRightT">
                    <h3 class="clearfix">推荐视频</h3>
                    <hr>
                    <h4>资料名称资料名称资料名称资料名称......</h4>
                    <dl class="y_list">
                        <dt><a href="#"><img src="../images/teacher_m.jpg"></a></dt>
                        <dd>
                            <span>简介：</span>简介简介简介简介简介简介简介简介简介简介简介简介简介简介简介
                        </dd>

                    </dl>
                    <ul class="info_list">
                        <li><a href="#">资料名称资料名称料名称资料名称料名称资料名称资料名称资料名称</a></li>
                        <li><a href="#">资料名称资料名称料名称资料名称料名称资料名称资料名称资料名称</a></li>
                        <li><a href="#">资料名称资料名称料名称资料名称料名称资料名称资料名称资料名称</a></li>
                        <li><a href="#">资料名称资料名称料名称资料名称料名称资料名称资料名称资料名称</a></li>
                    </ul>
                </div>
            </div>
        </div>

<!--主体内容结束-->

