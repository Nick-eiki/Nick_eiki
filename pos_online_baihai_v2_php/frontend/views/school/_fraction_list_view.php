<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/27
 * Time: 14:01
 */
?>
<h5 class="font16">高中部</h5>
<table>
	<colgroup>
		<col width="250px">
		<col width="250px">
		<col width="250px">
		<col width="250px">
		<?php if ($teacherLine) { ?>
		<col width="113px">
		<?php } ?>
	</colgroup>
	<thead>
	<th>年份</th>
	<th>录取</th>
	<th>择校</th>
	<th>住宿</th>
	<?php if ($teacherLine) { ?>
	<th>操作</th>
	<?php } ?>
	</thead>
	<tbody>
	<?php foreach($arr as $arrVal){ ?>
		<tr>
			<td><?php echo $arrVal->year; ?></td>
			<td><?php echo $arrVal->admissionLine; ?></td>
			<td><?php echo $arrVal->seclectSchoolLine; ?></td>
			<td><?php echo $arrVal->residentialLine; ?></td>
			<?php if ($teacherLine) { ?>
			<input type="hidden" pl="<?php echo $arrVal->pointLineID; ?>" class="plId" />
			<td><span class="updateBtnJs"></span></td>
			<?php } ?>
		</tr>

	<?php } ?>
	</tbody>
</table>
