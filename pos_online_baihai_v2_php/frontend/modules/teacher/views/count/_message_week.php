<?php
/**
 * Created by PhpStorm.
 * User: gaoli_000
 * Date: 2015/6/19
 * Time: 16:53
 */
?>


<div class="tabox message">
	<table class="unfinished_list tb" cellpadding="0"
	       cellspacing="0">

		<thead>
		<tr height="40" class="">
			<th width="145">姓名</th>
			<?php foreach ($result->listHead as $subVal) { ?>
				<th width="145"><?php echo $subVal->subjectName ?>
					<em class="em_ico_top "></em></th>
			<?php } ?>

			<th width="145">总计</th>

			<th width="145">发送人</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($result->list as $val) { ?>
			<tr height="40" class="">
				<td width="145" id="user_name"><?php echo $val->stuUserName; ?></td>

				<?php $subjectArray = (array)$val;
				$size = count((array)$val);
				?>
				<?php $lev = 0;
				foreach ($subjectArray as $key => $value) {
					$lev++;
					if ($lev > 9 && $lev < $size) {
						?>
						<td width="145"><?= $subjectArray["$key"] ?></td>
					<?php }
				} ?>
				<td width="145"><?= $val->sumCnt ?></td>
				<td width="145"><?php echo $val->senderName; ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
</div>

<!--															<div class="answerBox_list">-->
<!--																<div class="rattancent_cue_btm clearfix">-->
<!--																	<span class="fl rattancent_l">成绩排名(单位：名次)</span>-->
<!---->
<!--																	</span>-->
<!--																</div>-->
<!--																<div class="tabox">-->
<!--																	<table class="unfinished_list" cellpadding="0" cellspacing="0">-->
<!---->
<!--																		<thead>-->
<!--																		<tr height="40">-->
<!--																			<th width="112">学号</th>-->
<!--																			<th width="112">姓名-->
<!--																			</th>-->
<!--																			<th width="112">语文<em class="em_ico_top  "></em>-->
<!--																			</th>-->
<!--																			<th width="112">数学<em class="em_ico "></em>-->
<!--																			</th>-->
<!--																			<th width="112">英语<em class="em_ico "></em>-->
<!--																			</th>-->
<!--																			<th width="112">物理<em class="em_ico "></em>-->
<!--																			</th>-->
<!--																			<th width="112">总计<em class="em_ico "></em>-->
<!--																			</th>-->
<!--																			<th width="112">发送人</th>-->
<!--																		</tr>-->
<!--																		</thead>-->
<!--																		<tbody>-->
<!--																		<tr height="40">-->
<!---->
<!--																			<td width="112">-->
<!--																						<span>-->
<!---->
<!---->
<!---->
<!--                                                               		<label for="chk01">123456</label>-->
<!---->
<!--                                                                </span>-->
<!--																			</td>-->
<!--																			<td width="112">贾宝玉</td>-->
<!--																			<td width="112">1</td>-->
<!--																			<td width="112">12</td>-->
<!--																			<td width="112">6</td>-->
<!--																			<td width="112">60</td>-->
<!--																			<td width="112">79</td>-->
<!--																			<td width="112">系统</td>-->
<!--																		</tr>-->
<!--																		<tr height="40">-->
<!---->
<!--																			<td width="112">-->
<!--																						<span>-->
<!---->
<!---->
<!---->
<!--                                                               		<label for="chk01">123456</label>-->
<!---->
<!--                                                                </span>-->
<!--																			</td>-->
<!--																			<td width="112">贾宝玉</td>-->
<!--																			<td width="112">2</td>-->
<!--																			<td width="112">10</td>-->
<!--																			<td width="112">4</td>-->
<!--																			<td width="112">55</td>-->
<!--																			<td width="112">71</td>-->
<!--																			<td width="112">张三</td>-->
<!--																		</tr>-->
<!--																		<tr height="40">-->
<!---->
<!--																			<td width="112">-->
<!--																						<span>-->
<!---->
<!---->
<!---->
<!--                                                               		<label for="chk03">123456</label>-->
<!---->
<!--                                                                </span>-->
<!--																			</td>-->
<!--																			<td width="112">贾宝玉</td>-->
<!--																			<td width="112">3</td>-->
<!--																			<td width="112">8</td>-->
<!--																			<td width="112">3</td>-->
<!--																			<td width="112">20</td>-->
<!--																			<td width="112">34</td>-->
<!--																			<td width="112">李四</td>-->
<!--																		</tr>-->
<!---->
<!--																		</tbody>-->
<!--																	</table>-->
<!--																</div>-->
<!---->
<!--															</div>-->


<!--隐藏end-->
<script type="text/javascript">
	$(function () {
		$('#user_name').each(function (col) {
			$('.tb').rowspan(col);
		});
	});
</script>