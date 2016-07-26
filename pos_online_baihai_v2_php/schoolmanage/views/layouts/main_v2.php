<?php
/* @var $this \yii\web\View */
use yii\helpers\ArrayHelper;

/* @var $content string */

?>


<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="utf-8">
        <meta name="renderer" content="webkit">
        <!--<meta http-equiv="X-UA-Compatible" content="IE=7;IE=9;IE=8;IE=10;IE=11;IE=Edge">-->

        <link href="<?= publicResources_new() ?>/css/base.css" rel="stylesheet" type="text/css">
        <link href="<?= publicResources_new() ?>/css/sUI.css" rel="stylesheet" type="text/css">
        <link href="<?= publicResources_new() ?>/css/popBox.css" rel="stylesheet" type="text/css">
        <link href="<?= publicResources_new() ?>/css/jquery-ui.css" rel="stylesheet" type="text/css">

        <script src="<?= publicResources_new() ?>/js/jquery.js" type="text/javascript"></script>

        <title><?php echo Yii::$app->name . '-' . $this->title; ?></title>

        <?php $this->head() ?>
    </head>

    <body class="<?= ArrayHelper::getValue($this->blocks, 'bodyclass'); ?>">
    <?php $this->beginBody() ?>
    <?php echo $this->render('_site_header'); ?>

    <?= $content ?>

    <!--主体end-->
    <div class="footWrap">
        <div class="foot col1200 pr">
            <?php echo $this->render('@app/views/layouts/_site_footer'); ?>
        </div>
    </div>

    <?php echo ArrayHelper::getValue($this->blocks, 'foot_html'); ?>

    <?php $this->endBody() ?>
    <script src="<?php echo publicResources_new() ?>/js/lib/lazyload/jquery.lazyload.js?v=1.9.1"></script>
    <script type="text/javascript" charset="utf-8">
        $(function () {
            require(['<?php echo publicResources_new() ?>/js/lib/jquery.blockUI.js'], function () {
                $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
            });

            $("img.lazy").lazyload({
                effect: "fadeIn"
            });
        });
    </script>
    </body>
    </html>
<?php $this->endPage() ?>