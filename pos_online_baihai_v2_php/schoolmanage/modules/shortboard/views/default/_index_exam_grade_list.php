<?php
use yii\helpers\Url;

?>
<?php
$yDate = date('Y',time());//年
$mDate = date("m",time());//月
$no = 0;
foreach ($gradeModel as $key=>$item) {
	$no = count($gradeModel)-$key;
	?>
<li>
	<a class="<?php echo app()->request->getParam('gradeId', '') == $item['gradeId'] ? 'cur' : ''; ?>"
	   href="<?php echo Url::to(array_merge(['/shortboard/default/index'], $searchArr, ['gradeId'=>$item['gradeId']]))?>">
		<?php echo $item['gradeName'];
		if($mDate>=7){ ?>
			（<?php echo $yDate-$no+1 ?>级）
			<?php }else{?>
			（<?php echo $yDate-$no ?>级）
		<?php } ?>
	</a>
</li>
<?php } ?>
