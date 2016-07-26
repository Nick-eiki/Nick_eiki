<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-8
 * Time: 上午10:54
 */
use yii\web\View;

$this->registerJsFile(publicResources_new() . '/js/echarts/echarts.js',["position"=>View::POS_HEAD]);
$this->registerJsFile(publicResources_new() . '/js/echarts/html5shiv.min.js',["position"=>View::POS_HEAD]);
$this->registerJsFile(publicResources_new() . '/js/echarts/respond.min.js',["position"=>View::POS_HEAD]);
/* @var $this yii\web\View */  $this->title="班级统计";
?>
<div class="grid_19 main_r">
    <div class="main_cont statistClass class_statist">
        <div class="title">
            <h4>班级统计</h4>
        </div>

        <div class="statistics parsonal">
            <div class="statistics_t">
                <div class="tab">
                    <ul class="tabList clearfix">
<!--                        <li><a href="javascript:;" class="ac">作业统计数据</a></li>-->
                        <li><a href="javascript:;">考试统计数据</a></li>
                    </ul>
                    <div class="tabCont">
<!--                        <div class="tabItem">-->
<!--                            <ul class="resultList testClsList clearfix">-->
<!--                                <li type="1">作业完成程度</li>-->
<!--                                <li type="2">作业分布</li>-->
<!--                                <li type="3">单科作业完成度</li>-->
<!--                            </ul>-->
<!---->
<!--                        </div>-->
                        <div class="tabItem ">
                            <ul class="resultList  clearfix" >
<!--                                <li type="4">学生考试利用程度</li>-->
<!--                                <li type="5">各科成绩录入占比图</li>-->
                                <li type="6">成绩曲线</li>
<!--                                <li type="7">考试成绩分布图</li>-->
                                <li type="8">三率统计</li>
                            </ul>
<!--                            --><?php
//
//                            echo Html::dropDownList("",""
//                                ,
//                                $examArray,
//                                array(
//                                    "prompt" => "请选择",
//                                    "id" => "exam"
//                                ));
//                            ?>
                            <div class="subject">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="statis_chart">
                <div id="echarts04" style="width: 80;height: 80" ></div>
                <div id="echarts01"  ></div>

                <div class="sttic_chart">
                <div id="echarts02" ></div>
                    <div class="curveSubjectSelect stticBox"></div>
                    </div>
                <div id="echarts03" ></div>
                <div class="threeChance"></div>
                <div id="echarts05" ></div>
            </div>

        </div>

    </div>
</div>
<script>
    $(function(){
//        var oLi=$('.statistics_t ul li');
//        var aForm=$('.statistics_list ol');
//
//        for(var i=0; i<oLi.length; i++)
//        {
//            oLi[i].index=i;
//            oLi[i].onclick=function(){
//                for(var i=0; i<oLi.length; i++)
//                {
//                    oLi[i].className='';
//                    aForm[i].style.display='none';
//                }
//                this.className='this';
//                aForm[this.index].style.display='block';
//            }
//        }
        $(".resultList").find("li").click(function(){
            var classID=<?php echo app()->request->getParam("classID")?>;
            if($(this).attr("type")==1){
                $("#echarts04").addClass("echarts");
                $.getScript("<?php echo url('teacher/count/class-com-rate')?>?classID="+classID,function(result){

                })
            }
            if($(this).attr("type")==3){
                $("#echarts01").addClass("echarts");
                $.getScript("<?php echo url('teacher/count/class-hom-sub-rate')?>?classID="+classID,function(result){

                })
            }
            if($(this).attr("type")==6){
                $("#echarts02").addClass("echarts");
                $.post("<?php echo url('teacher/count/exam-score-curve')?>",{"classID":classID},function(result){
                    if(result.success){
                        $(".curveSubjectSelect").html(result.data);
                    }else{
                        popBox.errorBox(result.message);
                    }

                })
            }
            if($(this).attr("type")==8){
                $("#echarts02").addClass("echarts");
                $.post("<?=url('teacher/count/three-chance')?>",{classID:classID},function(result){
                    if(result.success){
                        $(".curveSubjectSelect").html(result.data);
                    }else{
                        popBox.errorBox(result.message);
                    }

                })
            }
        });
        $("#exam").change(function(){
            examID=$(this).val();
            $.post("<?php echo url('teacher/count/exam-subject')?>",{"examID":examID},function(result){
                $(".subject").html(result);
            })
        })


    })

</script>
<script>
    // 路径配置
    require.config({
        paths: {echarts: '<?php echo publicResources_new()?>'+'/js/echarts'}
    });
</script>
<style>
    .echarts{ width:100%; height:300px; margin-bottom:30px; padding-bottom:20px; border-bottom:1px solid #ccc}
</style>