<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/14
 * Time: 17:58
 */
use frontend\models\dicmodels\NewTypeModel;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */  $this->title="发布资讯";
?>

    <script>
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
            <a href="#">首页</a>&gt;&gt;<a href="#">发布咨询</a>
        </div>
        <div class="class_c grid_24 clearfix tch">

            <div class="centLeft grid_17">
                <div class="notice information">
                    <div class="noticeH noticeB clearfix">
                        <h3 class="h3L">发布资讯</h3>

                        <hr>

                    </div>
                    <?php
                    /** @var $form CActiveForm */
                    $form = ActiveForm::begin( array('enableClientScript' => false, ))
                    ?>
                        <ul class="form_list">
                            <li>
                                <div class="formL">
                                    <label for="name"><i></i>标题：</label>
                                </div>
                                <div class="formR">
                                    <input id="name" type="text" class="text" name="<?php echo Html::getInputName($model, 'informationTitle') ?>">
                                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'informationTitle') ?>
                                </div>
                            </li>
                            <li>
                                <div class="formL">
                                    <label for="name"><i></i>资讯类型：</label>
                                </div>
                                <div class="formR">
                                    <?php
                                    echo Html::dropDownList(Html::getInputName($model, 'informationType'),
                                        $model->informationType,
                                        NewTypeModel::model()->getListData(),
                                        array('id' => Html::getInputId($model, 'informationType'),
                                            'data-validation-engine' => 'validate[required,custom[number]]'
                                        ));
                                    ?>
                                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'informationType') ?>
                                </div>
                            </li>
                            <li>
                                <div class="formL">
                                    <label for="name"><i></i>关键词：</label>
                                </div>
                                <div class="formR">
                                    <input id="name" type="text" class="text" name="<?php echo Html::getInputName($model, 'informationKeyWord') ?>">
                                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'informationKeyWord') ?>
                                    <span class="textc">词语之间请用“|”隔开</span>
                                </div>
                            </li>
                            <li>
                                <div class="formL">
                                    <label for="name"><i></i>新闻内容：</label>
                                </div>
                                <div class="formR">
                                    <div style="width:489px; height:259px;">
                                        <?php
                                        echo \frontend\widgets\ueditor\MiniUEditor::widget(
                                            array(
                                                'id'=>'editor',
                                                'model'=>$model,
                                                'attribute'=>'informationContent',
                                                'UEDITOR_CONFIG'=>array(
                                                ),
                                            ));
                                        ?>
                                    </div>
                                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'informationContent') ?>
                                </div>
                            </li>
                        </ul>

                        <div class="btn_div"><button type="submit" class="mini_btn btn">确  定</button></div>
                    <?php ActiveForm::end(); ?>
                    <div class="add_seek hide">
                        <div class="add_seek_text">幼升小  新闻   新闻标题    已经发布成功，等待平台编辑审核......</div>
                        <div class="add_seek_btn">
                            <button type="button" class="mini_btn">继续发布</button>
                            <button type="button" class="mini_btn">阅读其他资讯</button>
                        </div>
                    </div>
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

