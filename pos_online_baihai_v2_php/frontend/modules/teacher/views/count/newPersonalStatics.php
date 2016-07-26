<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-7
 * Time: 下午1:13
 */
use yii\web\View;

/* @var $this yii\web\View */
$this->title="个人统计";
$this->registerJsFile(publicResources_new().'/js/echarts/echarts.js',["position"=> View::POS_HEAD]);
$this->registerJsFile(publicResources_new().'/js/echarts/html5shiv.min.js',["position"=> View::POS_HEAD]);
$this->registerJsFile(publicResources_new().'/js/echarts/respond.min.js',["position"=> View::POS_HEAD]);

?>
<div class="grid_19 main_r">
    <div class="main_cont statistClass">
        <div class="title">
            <!--            <a href="#" class="txtBtn backBtn"></a>-->
            <h4>个人统计</h4>
        </div>
        <?php foreach(loginUser()->getClassInfo() as $v){
            if($v->classID==app()->request->getParam("classID")){
                $className=$v->className;
            }
        }
        ?>
        <div class="statistics parsonal">
            <div class="statistics_t">
                <div class="nameList2">
                    <h3><?php echo $className ?>学生名单</h3>
                    <table class="naList n_list table" width="100%" cellpadding="0" cellspacing="0">
                        <tbody>
                        <?php $resultArr = array_chunk($classResult, 6);
                        foreach ($resultArr as $items) {
                            echo '<tr>';
                            foreach ($items as $i => $v) { ?>
                                <td><span userID="<?php echo $v->userID ?>"
                                          title=" <?php echo $v->memName ?>"> <?php echo $v->memName ?></span></td>
                            <?php }

                            $count = count($items);
                            if ($count > 0) {
                                for ($j = 0; $j < 6 - $count; $j++) { ?>
                                    <td></td>
                                <?php }
                            }


                            echo '</tr>';
                        } ?>
                        </tbody>
                    </table>
                </div>


                <div class="name_box">
                    <div class="nameList">
                        <h3>140501学生1</h3>

                        <dl class="naList clearfix naListtye">
                            <dt>统计类型：</dt>
                            <!--                            <dd type="1"><span>作业完成度</span></dd>-->
                            <!--                            <dd type="2"><span>单科作业完成度</span></dd>-->
                            <dd type="3"><span>成绩曲线</span></dd>
                            <dd type="4"><span>排名曲线</span></dd>
                        </dl>
                    </div>


                </div>




            </div>
            <div class="statis_chart">
                <div id="echarts04" ></div>
                <div id="echarts01" ></div>
                <div class="sttic_chart">
                <div id="echarts02" ></div>
                <div class="subjectScore  stticBox"></div>
                    </div>
                <div class="subjectRanking"></div>
                <div id="echarts05" ></div>
            </div>

        </div>

    </div>
</div>
<script>
    $(function(){
        var oLi=$('.statistics_t ul li');
        var aForm=$('.statistics_list ol');

        for(var i=0; i<oLi.length; i++)
        {
            oLi[i].index=i;
            oLi[i].onclick=function(){
                for(var i=0; i<oLi.length; i++)
                {
                    oLi[i].className='';
                    aForm[i].style.display='none';
                }
                this.className='this';
                aForm[this.index].style.display='block';
            }
        }

    })

</script>
<script>
    // 路径配置
    require.config({
        paths: {echarts: '<?php echo publicResources_new()?>'+'/js/echarts'}
    });


    $(function(){
        $('h2').click(function(){
            require(
                [
                    'echarts',
                    'echarts/chart/pie' // 使用柱状图就加载bar模块，按需加载
                ],
                function (ec) {
                    // 基于准备好的dom，初始化echarts图表
                    var myChart = ec.init( document.getElementById('echarts04') );
                    // 为echarts对象加载数据
                    myChart.setOption(opts.option2);
                }
            );

        })


    })

</script>
<script>
    $(function(){

        $('.nameList2 .n_list span').live('click',function(){
            var _this=this;
            userID=$(this).attr("userID");
            if(userID){
                $('.naList span').removeClass('this');

                $(_this).addClass('this');
                var this_text=$(this).text();
                var showText=$('.name_box .nameList h3').text(this_text);
                $(_this).parents('.nameList2').show();
                $('.name_box .nameList').show();
                $(".statis_chart").hide();
            }

        });

        $('.naListtye dd').live('click',function(){
            $(".statis_chart").show();
            $('.textName').remove();
            var this_txt=$(this).children('span').text();
            $('.name_box .nameList').append('<div class="textName"><em>'+ this_txt +'</em><i></i></div>');
            $(this).parent('dl').hide();
            if($(this).attr("type")==1){
                $("#echarts04").addClass("echarts");
                $.getScript("<?php echo url('teacher/count/all-task-rate')?>?userID="+userID,function(result){

                });


            }
            if($(this).attr("type")==2){
                $("#echarts01").addClass("echarts");
                $.getScript("<?php echo url('teacher/count/subject-task-rate')?>?userID="+userID,function(result){

                })
            }
            if($(this).attr("type")==3){
                $("#echarts02").addClass("echarts");
                classID="<?=app()->request->getParam('classID')?>";
                $.post("<?php echo url('teacher/count/score-change')?>",{"userID":userID,"classID":classID},function(result){
                    if(result.success){
                        $(".subjectScore").html(result.data);
                    }else{
                        popBox.errorBox(result.message);
                    }

                });
            }
            if($(this).attr("type")==4){
                $("#echarts02").addClass("echarts");
                classID="<?=app()->request->getParam('classID')?>";
                $.post("<?php echo url('teacher/count/ranking-change')?>",{"userID":userID,"classID":classID},function(result){
                    if(result.success){
                        $(".subjectScore").html(result.data);
                    }else{
                        popBox.errorBox(result.message);
                    }

                })
            }
        });

        $('.textName').live('click',function(){
            $('.naListtye').show();
        })

    })


</script>
<style>
    .echarts{ width:100%; height:300px; margin-bottom:30px; padding-bottom:20px; border-bottom:1px solid #ccc}
</style>