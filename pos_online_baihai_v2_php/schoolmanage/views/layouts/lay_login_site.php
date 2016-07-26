<?php
/* @var $this \yii\web\View */
/* @var $content string */

?>


<?php $this->beginPage() ?>
	<!DOCTYPE html>
	<html lang="<?= Yii::$app->language ?>">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=7;IE=9;IE=8;IE=10;IE=11;IE=Edge">

		<link href="<?= publicResources_new() ?>/css/base.css" rel="stylesheet" type="text/css">
		<link href="<?= publicResources_new() ?>/css/sUI.css" rel="stylesheet" type="text/css">
		<link href="<?= publicResources_new() ?>/css/popBox.css" rel="stylesheet" type="text/css">
		<link href="<?= publicResources_new() ?>/css/jquery-ui.css" rel="stylesheet" type="text/css">
		<script src="<?= publicResources_new() ?>/js/jquery.js" type="text/javascript"></script>
		<script src="<?= publicResources_new() ?>/js/jquery-ui.js" type="text/javascript"></script>
		<script type="text/javascript" data-main="<?= publicResources_new() ?>/js/main" src="<?= publicResources_new() ?>/js/require.js"></script>

		<title><?php echo Yii::$app->name.'-'.$this->title; ?></title>

	</head>
	<body class="login_body">
	<?php $this->beginBody() ?>

	<?= $content ?>

	<?php $this->endBody() ?>
	</body>
	</html>
<?php $this->endPage() ?>