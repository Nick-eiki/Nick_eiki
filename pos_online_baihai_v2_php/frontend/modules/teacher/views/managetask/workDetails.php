<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-17
 * Time: 下午1:21
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = '教师--作业管理-作业详情';
?>
<script>
	$(function () {
//全选
		$('#chkAll').newCheckAll($('.stu_item .chkLabel'));
		//添加作业内容
		$('#popBox').dialog({
			autoOpen: false,
			width: 500,
			modal: true,
			resizable: false,
			close: function () {
				$(this).dialog("close")
			}
		});
		$('.notice').click(function () {
			$("#popBox").dialog("open");
		});

		$('.studentBth').live('click', function (data) {
			var homeworkid = "<?php echo $homeworkId;?>";
			$.post("<?php echo url('teacher/managetask/send-message-by-object-id');?>", {homeworkid: homeworkid}, function (data) {
				if (data.success) {
					popBox.successBox();
					location.reload();
				} else {
					popBox.errorBox();
					return false;
				}
			})
		});
		//查询未批改
		$('.checked-time').click(function(){
			var checkTime = $(this).attr('checked-time');
            var type=$(".tabList .ac").attr("type");
            $.get("<?php url::to('/teacher/mamagetask/work-details')?>",{classhworkid:<?=$result->id?>,checkTime:checkTime,type:type},function(result){
                $("#work_id").html(result);
            })
        });
		//查询已批改
		$('.marked-checked-time').click(function(){
			var checkTime = $(this).attr('checked-time');
            var type=$(".tabList .ac").attr("type");
            $.get("<?php url::to('/teacher/mamagetask/work-details')?>",{classhworkid:<?=$result->id?>,checkTime:checkTime,type:type},function(result){
                $('#fixwork_id').html(result);
            })
		})
	})
</script>

<!--主体-->

<div class="grid_19 main_r">
	<div class="main_cont test work_detail">
		<div class="title">
			<a href="<?= url('teacher/resources/collect-work-manage', array('classid' => $result->classID)) ?>"
			   class="txtBtn backBtn"></a>
			<h4>作业详情</h4>
		</div>
		<div class="workInfo pr">
			<div class="title item_title noBorder">
				<h4><?php echo Html::encode($homeWorkTeacher->name); ?></h4>
			</div>
			<div class="form_list no_padding_form_list">
				<div class="row">
					<?php if(!empty($homeWorkTeacher->provience) && !empty($homeWorkTeacher->city) && !empty($homeWorkTeacher->country)){?>
					<div class="formL">
						<label>地区:</label>
					</div>
					<?php } ?>
					<div class="formR">
						<?php if(!empty($homeworkData->provience) && !empty($homeworkData->city) && !empty($homeworkData->country)){ ?>
                        <span>
	                        <?php echo AreaHelper::getAreaName($homeWorkTeacher->provience); ?>
	                        &nbsp;<?php echo AreaHelper::getAreaName($homeWorkTeacher->city); ?>
	                        &nbsp;<?php echo AreaHelper::getAreaName($homeWorkTeacher->country); ?>
                        </span>
						<?php }
						if(!empty($homeWorkTeacher->subjectId)){
						?>
						<span>科目:<?php echo SubjectModel::model()->getSubjectName($homeWorkTeacher->subjectId); ?></span>
						<?php }
						if(!empty($homeWorkTeacher->version)){
						?>
						<span>版本: <?php echo EditionModel::model()->getEditionName($homeWorkTeacher->version); ?>   </span>
						<?php } ?>
					</div>
				</div>
				<?php if ($homeWorkTeacher->knowledgeId !== null && $homeWorkTeacher->knowledgeId !== '') { ?>
					<div class="row">

						<div class="formL">
							<label>知识点:</label>
						</div>
						<div class="formR">
                        <span><?php
	                        echo KnowledgePointModel::findKnowledgeStr($homeWorkTeacher->knowledgeId);
	                        ?>
                        </span>
						</div>

					</div>
				<?php } ?>
				<?php if ($homeWorkTeacher->homeworkDescribe !== null && $homeWorkTeacher->homeworkDescribe !== '') { ?>
					<div class="row">
						<div class="formL">
							<label>简介:</label>
						</div>
						<div class="formR">
							<?php echo cut_str(Html::encode($homeWorkTeacher->homeworkDescribe), 300); ?>
						</div>
					</div>
				<?php } ?>

				<?php echo $this->render('_notice_student', ['getType' => $homeWorkTeacher->getType, 'result' => $homeWorkTeacher, 'homeworkId' => $homeworkId,'classhworkid' => $classhworkid]); ?>

				<div class="row " style="padding-top:20px">
                    <span class="progress">已答：未答<sub><b style="width: <?php
		                    if (isset($studentMember) && $studentMember != 0) {
			                    echo ($answerNumber / $studentMember) * 100;
		                    } else {
			                    echo '0';
		                    }
		                    ?>%"></b><?= $answerNumber ?>/<?= $studentMember - $answerNumber; ?></sub></span>
                        <span class="progress">已批改：未批改<sub><b style="width: <?php
		                        if (isset($answerNumber) && $answerNumber != 0) {
			                        echo ($isCorrections / $answerNumber) * 100;
		                        } else {
			                        echo '0';
		                        }
		                        ?>%"></b><?= $isCorrections; ?>/<?= $answerNumber - $isCorrections ?></sub></span>
				</div>

			</div>

		</div>
		<br>
		<hr>

		<h3 class="anser">作答情况</h3>

		<div class="tab fl tabList_border">
			<ul class="tabList clearfix ">
				<li><a href="javascript:;" class="ac" type="1">未批改</a></li>
				<li><a href="javascript:;">未提交</a></li>
				<li><a href="javascript:;" type="3">已批改</a></li>
			</ul>
			<div class="tabCont work_detailTab">
				<!--未批改-->
				<div class="tabItem">
					<div class="">

						<ul class="resultList testClsList">
							<li checked-time="1" class="checked-time ac">
								<a href="javascript:;">全部（<?php echo $noCorrections; ?>人）</a>
							</li>
							<li checked-time="2" class="checked-time">
								<a href="javascript:;">按时提交（<?php echo $onTimeNumber; ?>人）</a>
							</li>
							<li checked-time="3" class="checked-time">
								<a href="javascript:;">未按时提交（<?php echo $overtime; ?>）</a>
							</li>
						</ul>
						<div  id="work_id">
							<?php echo $this->render('_workList_view', ['answer' => $answer, 'page' => $page,'homeWorkTeacher'=>$homeWorkTeacher]) ?>
						</div>
					</div>

				</div>

				<!--未提交-->
				<div class="tabItem hide">
					<div class="">
						<div class="title item_title noBorder">
							<h4 style="color:#6cd685; font-size:16px;">
								未作答学生(共<?php echo $studentMember-$answerNumber; ?>人)
							</h4>
                    </div>
						<table class="work_detailtabBox table no_tablebg" cellpadding="0" cellspacing="0">
							<tbody>

							<?php
							$i = 0;
							if(empty($answerStuList)){
								foreach ($studentList as $k => $stuVal) {
									if ($i % 4 == 0) echo '<tr>';
									echo '<td>';
									echo '<a title='.$stuVal->memName.' href="' . url('teacher/count/new-bear-child', ['classID' => $result->classID]) . '"><span>' . $stuVal->memName . '</span> </a>';
									echo '</td>';
									$i++;
									if ($i % 4 == 0) echo '</tr>'; //注意这里
								}
							}else {
//                                获取未答作业的学生
                                $answerStuArray=array();
                                foreach($answerStuList as $v){
                                    array_push($answerStuArray,$v->studentID);
                                }
                                $unAnswerStuList=array();
                                foreach($studentList as $v){
                                     if(!in_array($v->userID,$answerStuArray)){
                                         array_push($unAnswerStuList,$v);
                                     }
                                }
                                foreach($unAnswerStuList as $stuVal){
                                    if ($i % 4 == 0) echo '<tr>';
                                    echo '<td>';
                                    echo '<a title=' . $stuVal->memName . ' href="' . url('teacher/count/new-bear-child', ['classID' => $result->classID]) . '"><span>' . $stuVal->memName . '</span> </a>';
                                    echo '</td>';
                                    $i++;
                                    if ($i % 4 == 0) echo '</tr>'; //注意这里
                                }
//
                            }
							?>

							</tbody>
						</table>
					</div>
				</div>
				<!--已批改-->
				<div class="tabItem hide">
					<div class="">
						<ul class="resultList testClsList">
							<li checked-time="1" class="marked-checked-time ac">
								<a href="javascript:;">全部（<?php echo $isCorrections; ?>人）</a>
							</li>
							<li checked-time="2" class="marked-checked-time">
								<a href="javascript:;">按时提交（<?php echo $markedOnTimeNumber; ?>人）</a>
							</li>
							<li checked-time="3" class="marked-checked-time">
								<a href="javascript:;">未按时提交（<?php echo $markedOvertime; ?>）</a>
							</li>
						</ul>

						<div id="fixwork_id">
							<?php echo $this->render('_fixworkList_view', ['answerCorrected' => $answerCorrected,'homeWorkTeacher'=>$homeWorkTeacher, 'pagesCorrected' => $pagesCorrected]) ?>
						</div>

					</div>
				</div>

			</div>
		</div>

	</div>
</div>
<!--主体end-->
<!--添加作业内容-->
<div id="popBox" class="popBox hide " title="添加作业内容">
	<div class="popCont add_work">
		<div class="conet">
			<?php echo cut_str($homeWorkTeacher->name, 30); ?>
		</div>
		<div class="conet">
			需要您添加一些作业内容
		</div>
	</div>
	<div class="popBtnArea">
		<a href="<?= url('teacher/managetask/new-update-work', ['homeworkid' => $homeworkId]); ?>"
		   class="okBtn w100 a_button" style="margin-right: 10px">上传作业</a>
		<a href="<?= url('teacher/managetask/new-preview-organize-paper', ['homeworkid' => $homeworkId]); ?>"
		   class="a_button w100 okBtn">组织作业</a>
	</div>
</div>