<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 2015/4/22
 * Time: 19:49
 */

?>

<script>

    //显示题目样式
    $(function(){
        $('.title_box h4,.topic_detail').hover(
            function(){
                $(this).parents('.title_box').children('.topic_detail').show();
            },
            function(){
                $(this).parents('.title_box').children('.topic_detail').hide();
            }
        );
        $('.fruit_list .topic_detail:odd').addClass('topic_detail_r');
    });


    render = function (id,data) {
        $(function(){
            // 基于准备好的dom，初始化echarts图表
            var myChart = echarts.init(document.getElementById(id));
            var option = {
                tooltip: {
                    show: true,
                    formatter: "{b} : {c}人"
                },
                grid:{
                    x:40,
                    y:40,
                    width:210,
                    height:100
                },
                color: ['#f00'],
                legend: {
                    data: ['']
                },
                xAxis: [
                    {
                        type: 'category',
                        data: data.text
                    }
                ],
                yAxis: [
                    {
                        type: 'value', name: ''
                    }
                ],
                series: [
                    {
                        "name": "分数统计",
                        "type": "bar",
                        "barWidth": "25",
                        "data":data.data,
                        "itemStyle": {
                            normal: {
                                color: function(params) {
                                    // build a color map as your need.
                                    var colorList = [
                                        '#58c5bf','#B5C334','#85d075','#dbad71','#e87e90',
                                        '#889ba8','#4ebbff','#ff9999','#94a1e2','#abc241',
                                        '#2b90cb'];
                                    return colorList[params.dataIndex]
                                }
                            }
                        }
                    }
                ]
            };

            // 为echarts对象加载数据
            myChart.setOption(option);
        });



    }
</script>
<ul class="itemList fruit_list">
    <li class="clearfix">
        <div class="title item_title noBorder clearfix" style="height:auto;">
            <?php foreach ($obj as $k => $v) { ?>
                <div class="title_box fl">
                    <div class="fl fruit_listL">
                        <h4 title="题目:<?= $v->questionID; ?>">题目:<?= $v->questionID; ?>:</h4>

                        <p>正确答案：<br>
                            <?= $v->answerContent ?>
                        </p>
                    </div>
                    <div class="fr fruit_listR">
                        <div id="k<?= $k ?>"   class="echarts"></div>
                    </div>
                    <div class="topic_detail hide">
                        <i class="arrow"></i>
                        <div class="topic_detail_cont"><?= $v->content;?></div>
                    </div>
                </div>
                <script type="text/javascript">

                    <?php

                   $text=    from($v->answer)->select(function($v){  return  $v->answerOption;})->toList();
                   $data=    from($v->answer)->select(function($v){  return  $v->cnt;})->toList();


                     $d=['text'=>$text,'data'=>$data];

                    ?>

                    var data=<?=json_encode($d) ?>;
                        render('k<?= $k ?>',data);
                </script>

            <?php } ?>

        </div>

    </li>

</ul>

    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#topicpushlist',
            'maxButtonCount' => 3
        )
    );
    ?>


<style type="text/css">
    .popo {
        display: none;
    }

    .echarts {
        width: 270px;
        height: 190px;
    }
</style>