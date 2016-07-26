<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/14
 * Time: 16:16
 */
use frontend\components\CHtmlExt;
use frontend\models\dicmodels\NewTypeModel;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="资讯列表";
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
            <div class="currentRight  currentRight_new  grid_16 push_2">
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
                            <?php echo CHtmlExt::validationEngineError($dataBag, 'informationType') ?>
                        	<a href="<?php echo url ("/teacher/information/add-information") ?>" class="new_bj B_btn120">发布信息</a>
                        </div>
                    </div>
                    <hr>
                    <?php  echo $this->render('_informationList', array('data' => $data, 'pages' => $pages))?>
                </div>
            </div>
<!--主体内容结束-->


