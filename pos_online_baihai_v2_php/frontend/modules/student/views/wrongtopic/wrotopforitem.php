<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/21
 * Time: 15:23
 */
use frontend\models\dicmodels\GradeModel;
use yii\helpers\Url;

/* @var $this yii\web\View */  $this->title="单科错题列表";
$this->registerJsFile(publicResources_new() . '/js/My97DatePicker/WdatePicker.js'.RESOURCES_VER, [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources_new();
;
$this->registerJsFile($backend_asset . "/js/jquery.validationEngine-zh_CN.js".RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/jquery.validationEngine.min.js".RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/register.js".RESOURCES_VER);
?>
<script type="text/javascript">

    $(function(){

        $(".grade_click").live('click',function(){
            var grade = $(this).attr('grade');
            var item = <?php echo app()->request->getQueryParam('item', null); ?>;
            var url = "<?php echo Url::to(['/student/wrongtopic/wro-top-for-item'])?>"+"?item="+item;
            $.get(url,{
                grade: grade,
                item: item
            },function(data){
                $("#wrong_list").html(data);
            })
        });

        $('.up').live("click",function(){
            $('.down').removeClass('ac');
            $(this).addClass('ac');

            var orderType = '0';
            var item = <?php echo app()->request->getQueryParam('item', null); ?>;
            var url = "<?php echo Url::to(['/student/wrongtopic/wro-top-for-item']) ?>"+"?item="+item;
            var grade = $("#grade_click").find('.ac').attr('grade');
            $.post(url,{
                orderType: orderType,
                item: item,
                grade:grade
            },function(data){
                $("#wrong_list").html(data);
            })
        });

        $('.down').live("click",function(){
            $('.up').removeClass('ac');
            $(this).addClass('ac');
            var orderType = '1';
            var item = <?php echo app()->request->getQueryParam('item', null); ?>;
            var url = "<?php echo Url::to(['/student/wrongtopic/wro-top-for-item']) ?>"+"?item="+item;
            var grade = $("#grade_click").find('.ac').attr('grade');
            $.post(url,{
                orderType: orderType,
                item: item,
                grade:grade
            },function(data){
                $("#wrong_list").html(data);
            })
        });

        $('.mistake_detail .clip_switch em').click(function(){
            $(this).addClass('ac').siblings('em').removeClass('ac');
        });
    })
</script>

<!--主体-->

<div class="grid_19 main_r">
    <div class="main_cont mistake_detail">
        <div class="title">
            <a href="<?php echo url('/student/wrongtopic/manage')?>" class="txtBtn backBtn"></a>
            <h4><?php echo $subject?>错题集</h4>
            <div class="title_r">
                <div class="problem_r_list">
                    <h5>增加题目<i></i></h5>
                    <ul class="hot" style="display:none;">
                        <li><a class="t_ico" href="javascript:;">增加题目<i></i></a></li>
                        <li class="list this "><a class="" href="<?php echo url('student/wrongtopic/wrong-enter')?>">录入新题</a></li>
                        <li class="list"><a class="" href="<?php echo url('/student/wrongtopic/take-photo-topic')?>">上传新题</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="form_list resulBox">
            <div class="row">
                <div class="formL">
                    <label>年级：</label>
                </div>
                <div class="formR" style=" width:92%">
                    <ul class="resultList testClsList  clearfix" id="grade_click">
                        <li class="grade_click ac " grade =""><a href="javascript:;" >全部</a></li>
                        <?php
                        $department = loginUser()->getModel()->department;
                        $grade = GradeModel::model()->getData($department, '');

                        foreach($grade as $val){ ?>
                            <li class="grade_click" grade='<?=$val['gradeId'] ?>'>
                                <a href="javascript:;" ><?= $val['gradeName']?></a>
                            </li>
                        <?php }?>

                    </ul>

                </div>
            </div>
        </div>
        <div class="test_paper_sort clearfix">
            <p class="fl font14">
                <span>排序：难度</span>
                <em class="up"></em>

                <em class="down"></em>
            </p>
        </div>
        <div id="wrong_list">
            <?php echo $this->render('//publicView/wrong/_new_wrong_list', array("model" => $model, "subject" => $subject, "pages" => $pages))?>
        </div>
    </div>
</div>