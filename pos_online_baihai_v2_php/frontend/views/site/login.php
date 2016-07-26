<?php
/* @var $this yii\web\View */
use frontend\components\CHtmlExt;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = "登录";
$backend_asset = publicResources_new();
?>

<!doctype html>
<html id="html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=7;IE=9;IE=8;IE=10;IE=11;IE=Edge">
    <meta name="keywords" content="班海,班海网,班海平台,banhai,学校,教师,学生,老师,当周问题,当周解决" />
    <meta name="description" content="班海网专注K12中小学在线教育，是基于移动互联网技术、云技术、语言交互技术而创建的最专业的中小学教学管理平台，力求当周问题当周解决，为全国5000多所学校提供全方位的教学管理解决方案。" />

    <title>班海网_当周问题当周解决_最专业的中小学教学管理平台</title>

    <link rel="icon" href="/favicon.ico" mce_href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" mce_href="/favicon.ico" type="image/x-icon">
    <link href="<?php echo publicResources_new2() ?>/css/base.css<?= RESOURCES_VER?>" rel="stylesheet" type="text/css">
    <link href="<?php echo publicResources_new2() ?>/css/jquery-ui.css<?= RESOURCES_VER?>" rel="stylesheet" type="text/css">
    <link href="<?php echo publicResources_new2() ?>/css/popBox.css<?= RESOURCES_VER?>" rel="stylesheet" type="text/css">
    <link href="<?php echo publicResources_new2() ?>/css/index.css<?= RESOURCES_VER?>" rel="stylesheet" type="text/css">
    <?=Html::jsFile($backend_asset . "/js/jquery-1.7.1.min.js".RESOURCES_VER) ?>
    <?=Html::jsFile($backend_asset .  "/js/jquery-ui.min.js".RESOURCES_VER) ?>

    <?=Html::jsFile($backend_asset . "/js/jquery.validationEngine.min.js") ?>
    <?=Html::jsFile($backend_asset .  "/js/jquery.validationEngine-zh_CN.js") ?>

    <script>
        $(function () {
            $('.mark').click(function () {
                $(this).toggleClass('chked')
            });

        });

    </script>

    <style>



        /* Z-INDEX */
        .formError { z-index: 990; }
        .formError .formErrorContent { z-index: 991; }
        .formError .formErrorArrow { z-index: 996; }

        .formErrorInsideDialog.formError { z-index: 5000; }
        .formErrorInsideDialog.formError .formErrorContent { z-index: 5001; }
        .formErrorInsideDialog.formError .formErrorArrow { z-index: 5006; }




        .inputContainer {
            position: relative;
            float: left;
        }

        .formError {
            position: absolute;
            top: 300px;
            left: 300px;
            display: block;
            cursor: pointer;
        }

        .ajaxSubmit {
            padding: 20px;
            background: #55ea55;
            border: 1px solid #999;
            display: none
        }

        .formError .formErrorContent {
            width: 100%;
            background: #ee0101;
            position:relative;
            color: #fff;
            width: 150px;
            font-size: 11px;
            border: 2px solid #ddd;
            box-shadow: 0 0 6px #000;
            -moz-box-shadow: 0 0 6px #000;
            -webkit-box-shadow: 0 0 6px #000;
            padding: 4px 10px 4px 10px;
            border-radius: 6px;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
        }

        .greenPopup .formErrorContent {
            background: #33be40;
        }

        .blackPopup .formErrorContent {
            background: #393939;
            color: #FFF;
        }

        .formError .formErrorArrow {
            width: 15px;
            margin: -2px 0 0 13px;
            position:relative;
        }
        body[dir='rtl'] .formError .formErrorArrow,
        body.rtl .formError .formErrorArrow {
            margin: -2px 13px 0 0;
        }

        .formError .formErrorArrowBottom {
            box-shadow: none;
            -moz-box-shadow: none;
            -webkit-box-shadow: none;
            margin: 0px 0 0 12px;
            top:2px;
        }

        .formError .formErrorArrow div {
            border-left: 2px solid #ddd;
            border-right: 2px solid #ddd;
            box-shadow: 0 2px 3px #444;
            -moz-box-shadow: 0 2px 3px #444;
            -webkit-box-shadow: 0 2px 3px #444;
            font-size: 0px;
            height: 1px;
            background: #ee0101;
            margin: 0 auto;
            line-height: 0;
            font-size: 0;
            display: block;
        }

        .formError .formErrorArrowBottom div {
            box-shadow: none;
            -moz-box-shadow: none;
            -webkit-box-shadow: none;
        }

        .greenPopup .formErrorArrow div {
            background: #33be40;
        }

        .blackPopup .formErrorArrow div {
            background: #393939;
            color: #FFF;
        }

        .formError .formErrorArrow .line10 {
            width: 15px;
            border: none;
        }

        .formError .formErrorArrow .line9 {
            width: 13px;
            border: none;
        }

        .formError .formErrorArrow .line8 {
            width: 11px;
        }

        .formError .formErrorArrow .line7 {
            width: 9px;
        }

        .formError .formErrorArrow .line6 {
            width: 7px;
        }

        .formError .formErrorArrow .line5 {
            width: 5px;
        }

        .formError .formErrorArrow .line4 {
            width: 3px;
        }

        .formError .formErrorArrow .line3 {
            width: 1px;
            border-left: 2px solid #ddd;
            border-right: 2px solid #ddd;
            border-bottom: 0 solid #ddd;
        }

        .formError .formErrorArrow .line2 {
            width: 3px;
            border: none;
            background: #ddd;
        }

        .formError .formErrorArrow .line1 {
            width: 1px;
            border: none;
            background: #ddd;
        }
    </style>
</head>
<body>
<div class="warp">
    <div class="header">客服热线 : 400-8986-838</div>
    <div class="gnn_container">
        <div class="content">
            <div class="loginbar">
                <?php $form = ActiveForm::begin(array(
                    'enableClientScript' => false,
                    'id' => 'form_id'
                )) ?>
                <h1>欢迎登录</h1>
                <div class="gnn_userName">
                    <label></label>
                    <input type="text" class="text validate[required]"
                           id="<?php echo Html::getInputId($model, 'userName') ?>"
                           name="<?php echo Html::getInputName($model, 'userName') ?>"
                           value="<?php echo $model->userName ?>"
                           data-validation-engine="validate[required]"
                           data-errormessage-value-missing="用户名不能为空"
                    />
                    <?php echo CHtmlExt::validationEngineError($model, 'userName') ?>


                </div>
                <div class="gnn_password">
                    <label></label>
                    <input type="password" class="text validate[required]"
                           id="<?php echo Html::getInputId($model, 'passwd') ?>"
                           name="<?php echo Html::getInputName($model, 'passwd') ?>"
                           data-validation-engine="validate[required,minSize[6],maxSize[20]]"
                           data-errormessage-value-missing="密码不能为空"
                    />
                    <?php echo CHtmlExt::validationEngineError($model, 'passwd') ?>
                </div>
                <div class="nameRelevant">
                    <div class="nameReleLeft">
                        <input type="checkbox" checked id="mark_chkbox"
                               name="<?php echo Html::getInputName($model, 'rememberMe') ?>"
                               value="1"  class="mark" >记住账号

                    </div>

                    <a href="<?php echo url('site/recover-password'); ?>">忘记登录密码?</a>
                </div>
                <button type="submit" class="loginButton" onclick="return load(this);">
                    登录
                </button>
                <?php \yii\widgets\ActiveForm::end() ?>
                <div class="flowLayer" id="flowLayer"></div>
                <div class="gnn_QRCode pop" id="gnn_QRCode">
                    <h2>教师∣学生∣家长 客户端扫码安装</h2>
                    <img src="<?php echo publicResources_new2()?>/images/gnn_QRCode_03.jpg">
                    <hr />
                    <h2>校长客户端扫码安装</h2>
                    <img src="<?php echo publicResources_new2()?>/images/gnn_QRCode_06.jpg">
                    <div class="triangle"> </div>
                    <div class="gnn_text" id="gnn_text"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="chapter">班海平台入驻<strong>4950所</strong>学校、<strong>418845位</strong>教师、<strong>6621308位</strong>学生<br>有<strong>22000个</strong>微课、<strong>110万</strong>课件、<strong>280万</strong>试题 ……</div>
        <div class="address">
            <div class="weChat">
                官方微信
                <div class="weChatIcon" id="weChatIcon">
                    <div class="weChatBigIcon pop" id="weChatBigIcon">
                        <h1>扫一扫 关注班海微信</h1>
                        <img src="<?php echo publicResources_new2()?>/images/gnn_QRCode_09.jpg">
                    </div>
                </div>
            </div>
            <div class="copyright">北京三海教育科技有限公司  ©版权所有    <a class="icpCode" href="http://bcainfo.miitbeian.gov.cn/state/outPortal/loginPortal.action;jsessionid=59A2EACE6ECB3C9C9494C2DB117B742F">京ICP备14022510号</a>   京公网安备11010802017286号</div>
        </div>
    </div>
</div>

<script>
    $(document).bind("mouseup",function(e){var target=$(e.target);if(target.closest(".pop").length==0)$(".pop").hide()});
    $(function(){
        $("#flowLayer").click(function () {
            $("#gnn_QRCode").stop(true,true).animate({bottom:0,right:0}).show();
            return false;
        });
        $("#gnn_text").click(function () {
            $("#gnn_QRCode").stop(true,true).animate({bottom: "-400px",right: "-360px"});
        });
        $("#weChatIcon").click(function () {
            $("#weChatBigIcon").show();
            return false;
        })
    })
</script>
</body>
</html>