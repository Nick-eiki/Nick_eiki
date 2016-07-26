<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-18
 * Time: 上午11:58
 */
/*
49  209	题型显示	1	单选题	1
50	209	题型显示	2	多选题	1
51	209	题型显示	3	填空题	1
52	209	题型显示	4	问答题	1
53	209	题型显示	5	应用题	1
96	209	题型显示	7	阅读理解	1
95	209	题型显示	6	完形填空	1
*/

use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\LetterHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

if (!isset($no)) {
    $no = '';
}
?>
<?php if ($item->showTypeId == 1 || $item->showTypeId == 2) { ?>
    <div class="<?php echo $item->answerRight==0?'fork':'check_mark'?>">
	    <?php
	    if(isset($no) && !empty($no)){
		    echo '<p>小题'.$no .'：'. $item->content.'</p>';
	    }elseif(isset($item->questionId) && !empty($item->questionId)){
		    echo '<h5>题'.$item->questionId.'</h5>';
		    echo '<h6>【'. $item->year.'年】'. $item->provenanceName . $item->questiontypename .'</h6>';
		    echo '<p>'.$item->content.'</p>';
	    } ?>
    <div class="checkArea" >
        <?php
        $showTypeId = $item->showTypeId;
        $select = array(
            '0'=>array('id'=>'0','content'=>'A'),
            '1'=>array('id'=>'1','content'=>'B'),
            '2'=>array('id'=>'2','content'=>'C'),
            '3'=>array('id'=>'3','content'=>'D')
        );
        if($item->answerOption == ''){?>
            <?php

            if ($showTypeId == 1) {
                echo Html::radioList('item[' . $item->questionId . ']',$item->userAnswerOption ,ArrayHelper::map($select,"id","content"), array('separator' => '<br />','class'=>'radio','encode'=>false));
            } else {
                echo Html::checkboxList('item[' . $item->questionId . ']', $item->userAnswerOption, ArrayHelper::map($select,"id","content"), array('separator' => '<br />','class'=>'checkbox','encode'=>false));
            }

            ?>
        <?php }elseif($item->answerOption == null){?>
            <?php

            if ($showTypeId == 1) {
                echo Html::radioList('item[' . $item->questionId . ']',$item->userAnswerOption ,ArrayHelper::map($select,"id","content"), array('separator' => '<br />','class'=>'radio','encode'=>false));
            } else {
                echo Html::checkboxList('item[' . $item->questionId . ']', $item->userAnswerOption, ArrayHelper::map($select,"id","content"), array('separator' => '<br />','class'=>'checkbox','encode'=>false));
            }

            ?>
        <?php }elseif($item->answerOption == '[]'){?>
            <?php

            if ($showTypeId == 1) {
                echo Html::radioList('item[' . $item->questionId . ']',$item->userAnswerOption ,ArrayHelper::map($select,"id","content"), array('separator' => '<br />','class'=>'radio','encode'=>false));
            } else {
                echo Html::checkboxList('item[' . $item->questionId . ']', $item->userAnswerOption, ArrayHelper::map($select,"id","content"), array('separator' => '<br />','class'=>'checkbox','encode'=>false));
            }

            ?>
        <?php }else{?>
            <?php
            $result = json_decode($item->answerOption);
            $select = (from($result)->select(function ($v) {
                return '<em>'.LetterHelper::getLetter($v->id) . '</em>&nbsp;<p>' . $v->content.'</p>';
            }, '$k')->toArray());
            if ($item->showTypeId == 1) {
                echo Html::radioList('item[' . $item->questionId . ']',$item->userAnswerOption , $select, array('separator' => '','class'=>'radio','encode'=>false));
            } else {
                echo Html::checkboxList('item[' . $item->questionId . ']', $item->userAnswerOption, $select, array('separator' => '','class'=>'checkbox','encode'=>false));
            }
            ?>
        <?php }?>
    </div>
        </div>
<?php } ?>

<?php if ($item->showTypeId == 3) { ?>
	<?php
	if(isset($no) && !empty($no)){
		echo '<p>小题'.$no .'：'. $item->content.'</p>';
	}elseif(isset($item->questionId) && !empty($item->questionId)){
		echo '<h5>题'.$item->questionId.'</h5>';
		echo '<h6>【'. $item->year.'年】'. $item->provenanceName . $item->questiontypename .'</h6>';
		echo '<p>'.$item->content.'</p>';
	} ?>

    <div class="checkArea">

        <?php if (empty($item->childQues)) { ?>
            <p><label></label>
            <ul class="addPicUl addImage clearfix sub_Q_List">
                <?php foreach($item->picList as $v){?>

		        <li>
			        <?php if(!empty($item->picList)){ ?>
				        <div class="checkArea" style="margin:0 ">
					        <div class="oneself clearfix">
						        <p class="onese">
							        <span style="display: inline">我的答案：</span>
							        <?php foreach($item->picList as $v){?>
								        <a href="<?php echo url('student/exam/view-correct',array('questionId'=>$item->questionId,"testAnswerID"=>$testAnswerID))?>">  <img width="50" height="48" alt="" src="<?php echo $v->picUrl ?>"></a>
							        <?php } ?>
						        </p>
						        <p class="correct" style="clear: none">
<!--							        <span>批改情况：</span>-->
							        <!--				<img src="../../images/answer.png" alt="">-->
						        </p>
					        </div>
				        </div>

			        <?php }?>
		        </li>
                <?php } ?>
            </ul>
            </p>
        <?php } else {
//            if($item->thisQuestionIsAnswer){
            foreach ($item->childQues as $key => $i) {
                ?>
                <p><label><?php echo $key+1 ?>、<?php echo $i->content ?> </label>
                <ul class="addPicUl addImage clearfix sub_Q_List">
                   <?php foreach($i->picList as $v){?>
		            <li>
			            <?php if(!empty($item->picList)){ ?>
				            <div class="checkArea" style="margin:0 ">
					            <div class="oneself clearfix">
						            <p class="onese">
							            <span style="display: inline">我的答案：</span>
							            <?php foreach($item->picList as $v){?>
								            <a href="<?php echo url('student/exam/view-correct',array('questionId'=>$item->questionId,"testAnswerID"=>$testAnswerID))?>">  <img width="50" height="48" alt="" src="<?php echo $v->picUrl ?>"></a>
							            <?php } ?>
						            </p>
						            <p class="correct" style="clear: none">
<!--							            <span>批改情况：</span>-->
							            <!--				<img src="../../images/answer.png" alt="">-->
						            </p>
					            </div>
				            </div>

			            <?php }?>
		            </li>

                    <?php }?>
                </ul>
                </p>
            <?php }
//        }
        }
        ?>
    </div>
<?php } ?>

<?php if ($item->showTypeId == 4) { ?>
	<?php
	if(isset($no) && !empty($no)){
		echo '<p>小题'.$no .'：'. $item->content.'</p>';
	}elseif(isset($item->questionId) && !empty($item->questionId)){
		echo '<h5>题'.$item->questionId.'</h5>';
		echo '<h6>【'. $item->year.'年】'. $item->provenanceName . $item->questiontypename .'</h6>';
		echo '<p>'.$item->content.'</p>';
	} ?>

    <div class="checkArea">
        <p>
        <ul class="addPicUl addImage clearfix sub_Q_List">
            <?php foreach($item->picList as $v){?>
	            <li>
		            <?php if(!empty($item->picList)){ ?>
			            <div class="checkArea" style="margin:0 ">
				            <div class="oneself clearfix">
					            <p class="onese">
						            <span style="display: inline">我的答案：</span>
						            <?php foreach($item->picList as $v){?>
							            <a href="<?php echo url('student/exam/view-correct',array('questionId'=>$item->questionId,"testAnswerID"=>$testAnswerID))?>">  <img width="50" height="48" alt="" src="<?php echo $v->picUrl ?>"></a>
						            <?php } ?>
					            </p>
					            <p class="correct" style="clear: none">
					            </p>
				            </div>
			            </div>

		            <?php }?>
	            </li>
            <?php }?>
        </ul>
        </p>
    </div>
<?php } ?>
<?php if ($item->showTypeId == 5 || $item->showTypeId == 6 || $item->showTypeId == 7) { ?>
	<?php
	if(isset($no) && !empty($no)){
		echo '<p>小题'.$no .'：'. $item->content.'</p>';
	}elseif(isset($item->questionId) && !empty($item->questionId)){
		echo '<h5>题'.$item->questionId.'</h5>';
		echo '<h6>【'. $item->year.'年】'. $item->provenanceName . $item->questiontypename .'</h6>';
		echo '<p>'.$item->content.'</p>';
	} ?>

	<ul class="sub_Q_List ">
		<li>
			<?php if(!empty($item->picList)){ ?>

				<div class="checkArea" style="margin:0 ">
					<div class="oneself clearfix">
						<p class="onese">
							<span style="display: inline">我的答案：</span>
							<?php foreach($item->picList as $v){?>
								<a href="<?php echo url('student/exam/view-correct',array('questionId'=>$item->questionId,"testAnswerID"=>$testAnswerID))?>">  <img width="50" height="48" alt="" src="<?php echo $v->picUrl ?>"></a>
							<?php } ?>
						</p>
						<p class="correct" style="clear: none">
<!--							<span>批改情况：</span>-->
							<!--				<img src="../../images/answer.png" alt="">-->
						</p>
					</div>
				</div>

			<?php }else{
				foreach ($item->childQues as $key => $i) {
					echo $this->render('//publicView/onlineTest/_itemChildProblemType', array('item' => $i, 'no' => $key+1,'testAnswerID'=>$testAnswerID));
				}}
			?>
		</li>
	</ul>

<?php } ?>

<?php if ($item->showTypeId == 8) { ?>
	<?php
	if(isset($no) && !empty($no)){
		echo '<p>小题'.$no.'</p>';
	}elseif(isset($item->questionId) && !empty($item->questionId)){
		echo '<h5>题'.$item->questionId.'</h5>';
		echo '<h6>【'. $item->year.'年】'. $item->provenanceName . $item->questiontypename .'</h6>';
	} ?>
	<?php
	$imgArr = ImagePathHelper::getPicUrlArray($item->content);
	foreach($imgArr as $imgVal){
		echo '<img src="'.$imgVal.'" width="810px" alt="">';
	}
	?>
	<div class="checkArea">
		<label>回答:</label>
		<?php
		foreach ($item->picList as $picVal) {
			$picArr = ImagePathHelper::getPicUrlArray($picVal->picUrl);
			foreach($picArr as $picUrl){?>
				<a href="<?php echo url('student/exam/view-correct',array('questionId'=>$item->questionId,"testAnswerID"=>$testAnswerID))?>">
					<img src='<?php echo $picUrl ;?>' width='50' height='48' alt>
				</a>
			<?php }
		}

		?>
	</div>
<?php } ?>