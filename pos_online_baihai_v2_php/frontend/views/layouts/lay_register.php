<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-8-26
 * Time: 上午11:02
 */

?>

<!doctype html>
<html id="html">
<head>
    <meta charset="utf-8">
    <script src="<?php echo publicResources() ?>/js/jquery-1.7.1.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo publicResources() ?>/js/jquery.validationEngine.min.js"></script>
    <script type="text/javascript" src="<?php echo publicResources() ?>/js/jquery.validationEngine-zh_CN.js"></script>
    <link type="text/css" rel="stylesheet" href="<?php echo publicResources() ?>/css/register.css">
    <link type="text/css" rel="stylesheet" href="<?php echo publicResources() ?>/css/base.css">
    <link type="text/css" rel="stylesheet" href="<?php echo publicResources() ?>/css/popBox.css">
    <link type="text/css" rel="stylesheet" href="<?php echo publicResources() ?>/css/jquery-ui.css">
    <script type="text/javascript" src="<?php echo publicResources() ?>/js/jquery-ui.min.js"></script>
    <script src="<?php echo publicResources() ?>/js/base.js" type="text/javascript"></script>
    <script src="<?php echo publicResources() ?>/js/main.js" type="text/javascript"></script>
    <script src="<?php echo publicResources() ?>/js/register.js"></script>

    <title><?php echo $this->title; ?></title>

</head>

<body style="background-color:#f4f4f4;">

<!--顶部开始-->
<?php
echo $this->render('//layouts/_site_header_regist');
?>

<!--顶部结束-->



<!--中间内容开始-->
<?php echo $content ?>
<!--中间内容结束-->



<!--footer-->
<div>
    <?php
    echo $this->render('application.views.layouts._user_footer');
    ?>
</div>

<!--footEnd-->
</body>
</html>



