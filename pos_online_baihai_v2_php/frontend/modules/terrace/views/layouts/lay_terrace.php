<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/25
 * Time: 19:53
 */
Yii::$app->clientScript->scriptMap['jquery.js'] = false;
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<script src="<?php echo publicResources_new() ?>/js/jquery-1.7.1.min.js<?=RESOURCES_VER ?>" type="text/javascript"></script>

	<script src="<?php echo publicResources_new() ?>/js/base.js<?=RESOURCES_VER ?>" type="text/javascript"></script>
	<script src='<?php echo publicResources_new() ?>/js/jquery-ui.min.js<?=RESOURCES_VER ?>' type="text/javascript"></script>
	<link href="<?php echo publicResources_new() ?>/css/base.css<?=RESOURCES_VER ?>" type="text/css" rel="stylesheet">
	<link href="<?php echo publicResources_new() ?>/css/jquery-ui.css<?=RESOURCES_VER ?>" type="text/css" rel="stylesheet">
	<link href="<?php echo publicResources_new() ?>/css/popBox.css<?=RESOURCES_VER ?>" type="text/css" rel="stylesheet">
	<link href="<?php echo publicResources_new() ?>/css/platform.css<?=RESOURCES_VER ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo publicResources_new() ?>/js/ztree/zTreeStyle/zTreeStyle.css<?=RESOURCES_VER ?>" rel="stylesheet" type="text/css">
	<script src="<?php echo publicResources_new() ?>/js/jquery.validationEngine.min.js" type="text/javascript"></script>
	<script src="<?php echo publicResources_new() ?>/js/jquery.validationEngine-zh_CN.js" type="text/javascript"></script>
	<script src="<?php echo publicResources_new() ?>/js/ztree/jquery.ztree.all-3.5.min.js<?=RESOURCES_VER ?>" type="text/javascript"></script>
	<title><?php echo $this->getPageTitle(); ?></title>
</head>

<body id="body">

<!--顶部开始-->

<?php echo $this->render("application.modules.terrace.views.layouts._terrace_header")?>
<!--顶部结束-->
<div class="cont24">
	<div class="grid24 main">
	<?php echo $content; ?>
	</div>
</div>
<!--底部开始-->
<?php //echo $this->render("_ku_footer")?>

<!--底部结束-->

<!--返回顶部-->
<div class="r_fixBox"><a href="javascript:" title="回到顶部" class="backTop">回到顶部</a></div>
</body>
</html>