<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/5/23
 * Time: 14:39
 */
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\Html;

?>
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
        [
            "prompt" => "总分",
            "id" => "subjectChance"
        ]);
    ?>
</div>
<div class="word_list">
    <?php foreach($examNameArray as $k=>$v){?>
        <p data-value="<?=$k+1?>"><?=($k+1).'.'.$v ?></p>

    <?php }?>
</div>
<script>
    $(function(){
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
                        formatter: function (params,ticket,callback) {
                            var res = '';
                            for (var i = 0, l = params.length; i < l; i++) {
                                res +=   params[i].seriesName + ' : ' + params[i].value+'%<br/>';
                            }
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
                    color:['#f00','#ffc20e','#1d953f'],
                    legend:{
                        data:['低分率','高分率','及格率']
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            restore: {show: true},
                            saveAsImage: {show: true}
                        }
                    },
//                    dataZoom : {
//                        show : true,
//                        realtime : true,
//                        //orient: 'vertical',   // 'horizontal'
//                        //x: 0,
////                    y: 36,
//                        //width: 400,
//                        height: 20,
////                        showDetail:true,
////                    backgroundColor: 'rgba(221,160,221,0.5)',
////                    dataBackgroundColor: 'rgba(138,43,226,0.5)',
////                    fillerColor: 'rgba(38,143,26,0.6)',
//                        handleColor: 'rgba(128,43,16,0.8)',
//                        //xAxisIndex:[],
//                        //yAxisIndex:[],
//                        start : 40,
//                        end : 60.
//
//                    },
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
                            type : 'value', name:'%',min:0,max:100
                        }
                    ],
                    series : [
                        {
                            "name":"低分率",
                            "type":"line",
                            "data":<?php echo $lowRate?>
                        },
                        {
                            "name":"高分率",
                            "type":"line",
                            "data":<?php echo $highRate?>
                        },
                        {
                            "name":"及格率",
                            "type":"line",
                            "data":<?php echo $passRate?>
                        }

                    ]
                };

                // 为echarts对象加载数据
                myChart.setOption(option);
            }
        );
        $("#subjectChance").change(function(){
            year=$('.yearList .ac').attr('data-value');
            subjectID=$(this).val();
            classID=<?php echo app()->request->getParam("classID")?>;
            $.post("<?php echo url('teacher/count/three-chance')?>",{"classID":classID,"subjectID":subjectID,'year':year},function(result){
                if(result.success){
                    $(".curveSubjectSelect").html(result.data);
                }else{
                    popBox.errorBox(result.message);
                }
            })
        });
        $(".yearList li").click(function(){
            subjectID=$("#subjectChance").val();
            year=$(this).attr('data-value');
            classID=<?php echo app()->request->getParam("classID")?>;
            $.post("<?php echo url('teacher/count/three-chance')?>",{"classID":classID,"subjectID":subjectID,'year':year},function(result){
                if(result.success){
                    $(".curveSubjectSelect").html(result.data);
                }else{
                    popBox.errorBox(result.message);
                }
            })
        });
        $("#echarts02").mouseout(function(){
            $(".word_list p").removeClass("ac");
        })
    })
</script>