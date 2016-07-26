<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/14
 * Time: 16:16
 */
use frontend\models\dicmodels\NewTypeModel;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="资讯列表";
\yii\helpers\Url::to()
?>

<script>
    function search(obj){
        $.post($(obj).attr('url'), {getType: $(obj).val()}, function(result){
            $('#srchResult').replaceWith(result);
        })
    }
$(function(){
    $('h3.Signature i').editPlus();
//搜索按钮切换
    $('.terrace_btn_js span').bind('click',function(){
        $(this).addClass('s_btn').siblings('span').removeClass('s_btn');
    })
})
</script>

<!--主体内容开始-->
<div class="replace">
        <div class="crumbs grid_24">
            <a href="#">首页</a>&gt;&gt;<a href="#">资讯详情</a>
        </div>
        <div class="class_c grid_24 clearfix tch">
            <div class="centLeft grid_17">
                <div class="notice information">
                    <div class="noticeH noticeB clearfix">
                        <h3 class="h3L">资讯</h3>
                        <div class="new_not fr">
                            <?php
                                echo Html::dropDownList(Html::getInputName($dataBag, 'informationType'),
                                $dataBag->informationType,
                                NewTypeModel::model()->getListData(),
                                array(
                                    "prompt" => "全部",
                                    'id' => Html::getInputId($dataBag, 'informationType'),
                                    'onchange' => "search(this)", 'url' => app()->request->url,
                                ));
                            ?>
                            <?php echo frontend\components\CHtmlExt::validationEngineError($dataBag, 'informationType') ?>
							<?php if(loginUser()->isTeacher()){ ?>
							<a href="<?php echo url ("/ku/information/add-information") ?>" class="new_bj B_btn120">发布信息</a>
							<?php } ?>
                        </div>
                    </div>
                    <hr>
                    <?php  echo $this->render('_informationList', array('data' => $data, 'pages' => $pages))?>
                </div>
            </div>
            <div class="centRight">
                <div class="centRightT">
                    <a href="classHandsin.html" class=" outAdd_btn B_btn120">设置手拉手班级</a> </div>
                <div class="centRightT clearfix">
                    <p class="title titleLeft"> <span>手拉手班级</span><i></i> </p>
                    <hr>
                    <dl class="list_dl clearfix">
                        <dt><img src="<?php echo publicResources();?>/images/pic.png" alt="" width="90" height="90"></dt>
                        <dd>
                            <h3>177班</h3>
                        </dd>
                        <dd><span>学校：</span>北京人大附中</dd>

                        <dd><span>成员：</span>30名学生</dd>
                    </dl>
                </div>
                <div class="centRightT">

                    <ul class="class_list clearfix">
                        <li><a href="#"><img src="<?php echo publicResources();?>/images/user_s.jpg" alt="" title="北京"></a></li>
                        <li><a href="#"><img src="<?php echo publicResources();?>/images/user_s.jpg" alt="" title="北京"></a></li>
                        <li><a href="#"><img src="<?php echo publicResources();?>/images/user_s.jpg" alt="" title="北京"></a></li>
                    </ul>
                </div>
                <div class="centRightT">
                    <h3 class="clearfix">推荐视频</h3>
                    <hr>
                    <h4>资料名称资料名称资料名称资料名称......</h4>
                    <dl class="y_list">
                        <dt><a href="#"><img src="<?php echo publicResources();?>/images/teacher_m.jpg"></a></dt>
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
</div>
<!--主体内容结束-->


