<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/17
 * Time: 17:53
 */

use frontend\models\dicmodels\NewTypeModel;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="修改资讯";
?>
<!--主体内容开始-->
<div class="currentRight grid_16 push_2 make_testpaper">
    <div class="notice information">
        <div class="noticeH noticeB clearfix">
            <h3 class="h3L">修改资讯</h3>

            <hr>

        </div>
        <?php
        /** @var $form CActiveForm */
        $form =\yii\widgets\ActiveForm::begin( array('enableClientScript' => false, ))
        ?>
        <ul class="form_list">
            <li>
                <!--创建id-->
                <input type="hidden" value="<?php echo $model->informationID; ?>" name="<?php echo Html::getInputName($model, 'informationID') ?>">
                <div class="formL">
                    <label><i></i>标题：</label>
                </div>
                <div class="formR">

                    <input id="name" type="text" class="text" name="<?php echo Html::getInputName($model, 'informationTitle') ?>" value="<?php echo $model->informationTitle; ?>" >
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'informationTitle') ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>资讯类型：</label>
                </div>
                <div class="formR">
                    <?php
                    echo Html::dropDownList(Html::getInputName($model, 'informationType'),
                        $model->informationType,
                        NewTypeModel::model()->getListData(),
                        array( 'id' => Html::getInputId($model, 'informationType'),

                            'data-validation-engine' => 'validate[required,custom[number]]'
                        ));
                    ?>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'informationType') ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>关键词：</label>
                </div>
                <div class="formR">
                    <input id="name" type="text" class="text" value="<?php echo $model->informationKeyWord; ?>" name="<?php echo Html::getInputName($model, 'informationKeyWord') ?>">
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'informationKeyWord') ?>
                    <span class="textc">词语之间请用“|”隔开</span>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>新闻内容：</label>
                </div>
                <div class="formR">
                    <div>
                        <?php
                        echo \frontend\widgets\ueditor\MiniUEditor::widget(
                            array(
                                'id'=>'editor',
                                'model'=>$model,
                                'attribute'=>'informationContent',
                                'UEDITOR_CONFIG'=>array(
                                    'initialContent'=>$model->informationContent,
									'initialFrameHeight' => '120',
									'initialFrameWidth' => '480',
                                ),
                            ));
                        ?>

                    </div>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'informationContent') ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label></label>
                </div>
                <div class="formR">
                <button type="submit" class="bg_red_d w120">确认修改</button>
                </div>
                </li>
            
        </ul>
        <?php \yii\widgets\ActiveForm::end(); ?>
        <div class="add_seek hide">
            <div class="add_seek_text">幼升小  新闻   新闻标题    已经发布成功，等待平台编辑审核......</div>
            <div class="add_seek_btn">
                <button type="button" class="bg_green">继续发布</button>
                <button type="button" class="bg_blue">阅读其他资讯</button>
            </div>
        </div>
    </div>
</div>

<!--主体内容结束-->

