<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 14-11-19
 * Time: 下午5:21
 */
use frontend\components\helper\LetterHelper;
use frontend\components\helper\StringHelper;
use frontend\models\dicmodels\KnowledgePointModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


/* @var $this yii\web\View */  $this->title="完成答题";
$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js'.RESOURCES_VER, [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . "/js/jquery.validationEngine-zh_CN.js".RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/jquery.validationEngine.min.js".RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/register.js".RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/ztree/jquery.ztree.all-3.5.min.js".RESOURCES_VER);
?>
<div class="grid_19 main_r">
    <!--<div class="crumbs noticeB"> <a href="#">试卷管理</a> >> <a href="#">试卷预览</a></div>-->
    <div class="main_cont online_answer">
        <div class="title">
            <a href="<?=url('/student/managepaper/topic-push'); ?>" class="txtBtn backBtn"></a>
            <h4><?= Html::encode($model->questionTeamName);?></h4>

        <div class="title_r">
            <span>组卷人：<?=$model->creatorName;?> &nbsp;&nbsp;组织时间：<?php echo $model->createTime; ?></span>
        </div>
        </div>
    <div class="testPaperView pr">
        <div class="paperArea">
            <div class="finish ">
                <div class="finish_top"><?php /*echo $model->gradename; */?><!--&nbsp;<?php /*echo $model->subjectname; */?>&nbsp;自定义标签：&nbsp;--><?php /*echo Html::encode($model->labelName); */?></div>
                <div class="finish_bottom clearfix">
                    <div class="finish_b_l">
                        <p>1、考察知识点：<?php
                            if (isset($model->connetID)) {
                                foreach (KnowledgePointModel::findKnowledge($model->connetID) as $key => $item) {
                                    echo '<span>' . $item->name . '&nbsp;&nbsp;</span>';
                                }
                            } ?></p>
                        <p>2、本试卷共包含<?php echo $model->countSize; ?>道题目，其中单选<?php echo $model->single;?>题</p><!--,多选--><?php /*echo $model->multi;*/?>
                    </div>
                    <div class="finish_b_r">
                        <p>
                            <span class="g">答对：<?php echo $model->rightCnt; ?>题</span>
                            <span class="r">答错：<?php echo $model->wrongCnt; ?>题</span>
                        </p>
                    </div>
                </div>

            </div>
            <?php foreach($model->questionList as $key=>$val){?>
            <div class="paper"><!--选择题-->

                <h5>题目<?php echo $key+1;?>:</h5>
                <h6><?php if(isset($val->year) && !empty($val->year)){echo '【'.$val->year.'年】';}?>  <?php echo $val->provenanceName?>  选择题</h6>
                <p><?php echo StringHelper::htmlPurifier($val->content);?></p>
                <?php if($val->isright == 0){?>
                    <div class="wrong_right"><em class="wrong"></em></div>
                <?php }elseif($val->isright == 1){?>
                    <div class="wrong_right"><em class="right"></em></div>
                <?php }?>
                <div class="checkArea">
                    <?php if($val->answerOption == ''){
                        $op_list = json_decode($val->answerOption);
                        $op_list=is_array($op_list)?$op_list:array();


                        $showTypeId = $val->showTypeId;
                        $op_list = array(
                            '0'=>array('id'=>'0','content'=>'A'),
                            '1'=>array('id'=>'1','content'=>'B'),
                            '2'=>array('id'=>'2','content'=>'C'),
                            '3'=>array('id'=>'3','content'=>'D')
                        );

                        if($val->isright == 0){
                            $color = 'red';
                        }else{
                            $color = '#6CD685';
                        }
                        //学生自己的答案，显示红色
                        foreach($op_list as $key=> &$option){
                            $arr = explode(',',$val->stuAnswer);
                            if($option['id'] == $val->stuAnswer || in_array($option['id'],$arr)){
                                $option['content']= '<em style="color:'.$color.'">'.$option['content'].'&nbsp;&nbsp;</em>';
                            }
                        }

                        if($showTypeId == '1'){
                            echo Html::radioList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'separator'=>'&nbsp;','encode'=>false]);
                        }elseif($showTypeId == '2'){
                            echo Html::checkboxList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'separator'=>'&nbsp;','encode'=>false]);
                        }
                    }elseif($val->answerOption == null){
                        $op_list = json_decode($val->answerOption);
                        $op_list=is_array($op_list)?$op_list:array();


                        $showTypeId = $val->showTypeId;
                        $op_list = array(
                            '0'=>array('id'=>'0','content'=>'A'),
                            '1'=>array('id'=>'1','content'=>'B'),
                            '2'=>array('id'=>'2','content'=>'C'),
                            '3'=>array('id'=>'3','content'=>'D')
                        );

                        if($val->isright == 0){
                            $color = 'red';
                        }else{
                            $color = '#6CD685';
                        }
                        //学生自己的答案，显示红色
                        foreach($op_list as $key=> &$option){
                            $arr = explode(',',$val->stuAnswer);
                            if($option['id'] == $val->stuAnswer || in_array($option['id'],$arr)){
                                $option['content']= '<em style="color:'.$color.'">'.$option['content'].'&nbsp;&nbsp;</em>';
                            }
                        }

                        if($showTypeId == '1'){
                            echo Html::radioList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'separator'=>'&nbsp;','encode'=>false]);
                        }elseif($showTypeId == '2'){
                            echo Html::checkboxList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'separator'=>'&nbsp;','encode'=>false]);
                        }
                    }elseif($val->answerOption == '[]'){
                        $op_list = json_decode($val->answerOption);
                        $op_list=is_array($op_list)?$op_list:array();


                        $showTypeId = $val->showTypeId;
                        $op_list = array(
                            '0'=>array('id'=>'0','content'=>'A'),
                            '1'=>array('id'=>'1','content'=>'B'),
                            '2'=>array('id'=>'2','content'=>'C'),
                            '3'=>array('id'=>'3','content'=>'D')
                        );

                        if($val->isright == 0){
                            $color = 'red';
                        }else{
                            $color = '#6CD685';
                        }
                        //学生自己的答案，显示红色
                        foreach($op_list as $key=> &$option){
                            $arr = explode(',',$val->stuAnswer);
                            if($option['id'] == $val->stuAnswer || in_array($option['id'],$arr)){
                                $option['content']= '<em style="color:'.$color.'">'.$option['content'].'&nbsp;&nbsp;</em>';
                            }
                        }

                        if($showTypeId == '1'){
                            echo Html::radioList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'separator'=>'&nbsp;','encode'=>false]);
                        }elseif($showTypeId == '2'){
                            echo Html::checkboxList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'separator'=>'&nbsp;','encode'=>false]);
                        }
                    }else{
                        $op_list = json_decode($val->answerOption);
                        $op_list=is_array($op_list)?$op_list:array();
                        $showTypeId = $val->showTypeId;
                        if($val->isright == 0){
                            $color = 'red';
                        }else{
                            $color = '#6CD685';
                        }
                        //学生自己的答案，显示红色
                        foreach($op_list as $option){
                            $arr = explode(',',$val->stuAnswer);
                            if($option->id == $val->stuAnswer || in_array($option->id,$arr)){
                                $option->content= '<em style="color:'.$color.'">'.LetterHelper::getLetter($option->id).'&nbsp;&nbsp;</em>'.$option->content.'&nbsp;&nbsp;';
                            }else{
                                $option->content= '<em>'.LetterHelper::getLetter($option->id).'&nbsp;&nbsp;</em>'.$option->content.'&nbsp;&nbsp;';
                            }
                        }

                        if($showTypeId == '1'){
                            echo Html::radioList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'separator'=>'&nbsp;','encode'=>false]);
                        }elseif($showTypeId == '2'){
                            echo Html::checkboxList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'separator'=>'&nbsp;','encode'=>false]);
                        }

                    }?>

                </div>


                <div class="btnArea clearfix">
                    <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span>
                    <span class="r_btnArea fr">难度:<em><?php echo $val->complexityName;?></em>&nbsp;&nbsp;&nbsp;录入:<?php echo $val->operaterName;?></span>
                </div>
                <div class="answerArea hide">
                    <p><em>答案:</em><span>
                        <?php
                        if($showTypeId == '1'){
                            if($val->answerContent==='0' || $val->answerContent === '1' || $val->answerContent ==='2' || $val->answerContent ==='3'){
                                echo LetterHelper::getLetter($val->answerContent);
                            }else{
                                echo $val->answerContent;
                            }

                        }elseif($showTypeId == '2'){
                            $arr = explode(',',$val->answerContent);
                            $array = array();
                            foreach($arr as $opt){
                                $array[] = LetterHelper::getLetter($opt);
                            }
                            $str = implode(',',$array);
                            echo $str;
                        }
                        ?>
                        </span></p>
                    <p><em>解析:</em><?php echo StringHelper::htmlPurifier($val->analytical);?></p>
                </div>
            </div>
            <hr>
            <?php }?>

        </div>
    </div>
    </div>
</div>

<!--主体内容结束-->

<script type="text/javascript">
    /*//查看答案与解析
    $('.openAnswerBtn').click(function(){
        $(this).parents('.testpaper').children('.answerArea').toggle();
    })*/
    //查看答案与解析
    $('.openAnswerBtn.fl').click(function(){
        $(this).children('i').toggleClass('close');
        $(this).parents('.paper').find('.answerArea').toggle();
    })
</script>




