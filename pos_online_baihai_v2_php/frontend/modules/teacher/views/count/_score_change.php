<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-7
 * Time: 下午5:16
 */
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\Html;

?>
<script>
    //折线图
    require(
        [
            'echarts',
            'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init( document.getElementById('echarts02') );
            var option={
                tooltip:{
                    show:true,
                    trigger: 'axis',
//                    formatter: "{c}分"
                    formatter: function (params,ticket,callback) {
                        var res = params[0].value+'分';
                        position= params[0].name;
                        $(".word_list p").each(function(index,el){
                            if($(el).attr('data-value')==position){
                                $(el).addClass("ac");
                            }else{
                                $(el).removeClass("ac");
                            }
                        });
                        return res;
                    }
                },
                color:['#09f','#f00'],
                legend:{
                    data:<?php echo  $subject?>
                },
                toolbox: {
                    show: true,
                    feature: {
                        restore: {show: true},
                        saveAsImage: {show: true}
                    }
                },
//                dataZoom : {
//                    show : true,
//                    realtime : true,
//                    orient: 'vertical',   // 'horizontal'
//                    x: 0,
//                    y: 36,
//                    width: 400,
//                    height: 20,
//                    backgroundColor: 'rgba(221,160,221,0.5)',
//                    dataBackgroundColor: 'rgba(138,43,226,0.5)',
//                    fillerColor: 'rgba(38,143,26,0.6)',
//                    handleColor: 'rgba(128,43,16,0.8)',
//                    xAxisIndex:[],
//                    yAxisIndex:[],
//                    start : 40,
//                    end : 60.
//
//                },
                xAxis:[
                    {
                        type : 'category',
                        axisLabel:{
                            show:true,
                            formatter:function(value){
                if(value.length>8){
                    return value.substr(0,8)+'.....';
                }else{
                    return value;
                }
            }
        },
                        data : <?php echo $exam?>
                    }
                ],
                yAxis : [
                    {
                        type : 'value', name:'分数',min:0,max:<?=$fullScore?>
                    }
                ],
                series : [
                    {
                        "name":"",
                        "type":"line",
                        "data":<?php echo $data?>
                    }

                ]
            };

            // 为echarts对象加载数据
            myChart.setOption(option);
        }
    );
    classID="<?=app()->request->getParam('classID')?>";
    $("#subjectScore").change(function(){
        year=$('.yearList .ac').attr('data-value');
        subjectID=$(this).val();
        $.post("<?php echo url('teacher/count/score-change')?>",{"userID":userID,"subjectID":subjectID,"classID":classID,"year":year},function(result){
            if(result.success){
                $(".subjectScore").html(result.data);
            }else{
                popBox.errorBox(result.message);
            }
        })
    });
    $(".yearList li").click(function(){
        subjectID=$("#subjectScore").val();
        year=$(this).attr("data-value");
        $.post("<?php echo url('teacher/count/score-change')?>",{"userID":userID,"subjectID":subjectID,"classID":classID,year:year},function(result){
            if(result.success){
                $(".subjectScore").html(result.data);
            }else{
                popBox.errorBox(result.message);
            }
        })
    });
    $("#echarts02").mouseout(function(){
        $(".word_list p").removeClass("ac");
    })
</script>

<ul class="yearList">
    <li class="<?=$year==3?'ac':''?>"  data-value="3">三年</li>
    <li class="<?=$year==2?'ac':''?>" data-value="2">一年</li>
    <li class="<?=$year==1?'ac':''?>" data-value="1">半年</li>
</ul>
<div class="selcteBox">
    <?php

    echo Html::dropDownList("",$subjectID
        ,
        SubjectModel::model()->getListData(),
        array(
            "prompt" => "总分",
            "id" => "subjectScore"
        ));
    ?>
</div>
<div class="word_list">
    <?php foreach($examNameArray as $k=>$v){?>
    <p data-value="<?=$k+1?>"><?=($k+1).'.'.$v ?></p>

    <?php }?>
</div>
