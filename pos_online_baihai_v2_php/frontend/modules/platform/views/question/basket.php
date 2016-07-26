<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2016/2/23
 * Time: 14:04
 */
use frontend\components\WebDataCache;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title='选题篮';
$this->registerCssFile(publicResources_new2() . '/css/test_basket.css'.RESOURCES_VER);
$this->blocks['requireModule']='app/test_basket/test_basket_view';
?>
<div class="main col1200 clearfix test_basket_view" id="requireModule" rel="app/test_basket/test_basket_view">
    <div class="container">
        <div class="sUI_pannel top_panner">
            <a href="javascript:history.back(-1);" class="btn bg_gray icoBtn_back return_btn"><i></i>返回</a>
            <div class="pannel_r">
                <span><a id="conformBtn" href="javascript:;" class="btn btn40 bg_blue">确认出题</a></span>
                <ul id="conformList" class="conformList pop">
                    <span class="arrow"></span>
                    <li><a href="javascript:;">出题</a></li>
                    <li><a data-popBox="hmwk" href="javascript:;">作业</a></li>
                    <li><a href="javascript:;">练一练</a></li>
                    <li><a href="javascript:;">试卷</a></li>
                </ul>
            </div>

        </div>

    </div>
    <div class="container" style="height:60px; line-height: 60px; text-indent: 25px; font-size: 16px">
        选择题3道
    </div>
    <div class="container no_bg">
        <div class="testPaper">
            <div class="quest">
                <div class="sUI_pannel quest_title">
                    <div class="pannel_l">
                        <b>1</b>
                    </div>
                    <div class="pannel_r" style="margin-right: 25px">
                        <span><a href="javascript:;" class="del_btn"><i></i>删除</a></span>
                    </div>
                </div>
                <div class="pd25">
                    <div class="Q_title">
                        <p>阅读下面文言文，完成后面题目</p>
                        <p>七年，入见，帝①从容问曰：“卿得良马否？”飞曰：“臣有二马，日啖刍豆数斗，饮泉一斛，然非精洁则不受。介②而驰，初不甚疾，比行百里始奋迅。自午至酉，犹可二百里。褫③鞍甲而不息不汗，若无事然。此其受大而不苟取，力裕而不求逞，致远之材也。不幸相继以死。今所乘者，日不过数升，而秣④不择粟，饮不择泉，揽辔未安，踊踊疾驱，甫百里，力竭汗喘，殆欲毙然。此其寡取易盈，好逞易穷，驽钝之材也。”帝称善，曰：“卿今议论极进。”</p>
                        <p>——节选自《宋史·岳飞传》</p>
                        <p>注释：①帝：宋高宗赵构，此次谈话后就封岳飞为太尉。②介：备上鞍甲。③褫（chǐ）：脱去，卸下。④秣：喂食。</p>
                    </div>
                    <div class="Q_cont">
                        <p>A.飞船沿椭圆轨道1经过P点时的速度与沿圆轨道经过P点时的速度相等</p>
                        <p>B.飞船在圆轨道2上时航天员出舱前后都处于失重状态</p>
                        <p>C.飞船在圆轨道2的角速度大于同步卫星运行的角速度</p>
                        <p>D.飞船从椭圆轨道1的Q点运动到P点过程中万有引做正功</p>
                    </div>
                    <div class="sUI_pannel btnArea">
                        <button type="button" class="bg_white icoBtn_open show_aswerBtn">查看答案解析 <i></i></button>
                    </div>
                    <div class="A_cont">
                        <div class="answerBar">
                            <h6>答案:</h6>
                            <p>BC</p>
                        </div>
                        <div class="analyzeBar">
                            <h6>解析：</h6>
                            <p>A、P点是椭圆轨道的远地点，飞船飞经该点时将做的近心运动满足，飞船在轨道2上做圆周运动经过P点时满足，根据运动条件知v1＜v2，故A错误；</p>
                            <p>B、飞船在圆轨道上做匀速圆周运动，万有引力完全提供向心力，故航天员出舱前后都处于失重状态，故B正确；</p>
                            <p>C、飞船在轨道2上周期大约为90分钟，而同步卫星的周期为24h，故知圆轨道上周期小于同步卫星周期，角速度大于同步卫星的角速度，故C正确；</p>
                            <P>D、飞船在P点时的加速度由万有引力产生，不管沿圆轨道还是椭圆轨道卫星在P点所受万有引力大小相等，故产生的加速度亦相同，故D错误。故选BC</p>
                        </div>

                    </div>


                </div>

            </div>
            <div class="quest join_basket">
                <div class="sUI_pannel quest_title">
                    <div class="pannel_l">
                        <b>2</b>
                    </div>
                    <div class="pannel_r" style="margin-right: 25px">
                        <span><a href="javascript:;" class="del_btn"><i></i>删除</a></span>
                    </div>
                </div>
                <div class="pd25">
                    <div class="Q_title">
                        <p>阅读下面文言文，完成后面题目</p>
                        <p>七年，入见，帝①从容问曰：“卿得良马否？”飞曰：“臣有二马，日啖刍豆数斗，饮泉一斛，然非精洁则不受。介②而驰，初不甚疾，比行百里始奋迅。自午至酉，犹可二百里。褫③鞍甲而不息不汗，若无事然。此其受大而不苟取，力裕而不求逞，致远之材也。不幸相继以死。今所乘者，日不过数升，而秣④不择粟，饮不择泉，揽辔未安，踊踊疾驱，甫百里，力竭汗喘，殆欲毙然。此其寡取易盈，好逞易穷，驽钝之材也。”帝称善，曰：“卿今议论极进。”</p>
                        <p>——节选自《宋史·岳飞传》</p>
                        <p>注释：①帝：宋高宗赵构，此次谈话后就封岳飞为太尉。②介：备上鞍甲。③褫（chǐ）：脱去，卸下。④秣：喂食。</p>
                    </div>
                    <div class="Q_cont">
                        <p>A.飞船沿椭圆轨道1经过P点时的速度与沿圆轨道经过P点时的速度相等</p>
                        <p>B.飞船在圆轨道2上时航天员出舱前后都处于失重状态</p>
                        <p>C.飞船在圆轨道2的角速度大于同步卫星运行的角速度</p>
                        <p>D.飞船从椭圆轨道1的Q点运动到P点过程中万有引做正功</p>
                    </div>
                    <div class="sUI_pannel btnArea">
                        <button type="button" class="bg_white icoBtn_open show_aswerBtn">查看答案解析 <i></i></button>
                    </div>
                    <div class="A_cont">
                        <div class="answerBar">
                            <h6>答案:</h6>
                            <p>BC</p>
                        </div>
                        <div class="analyzeBar">
                            <h6>解析：</h6>
                            <p>A、P点是椭圆轨道的远地点，飞船飞经该点时将做的近心运动满足，飞船在轨道2上做圆周运动经过P点时满足，根据运动条件知v1＜v2，故A错误；</p>
                            <p>B、飞船在圆轨道上做匀速圆周运动，万有引力完全提供向心力，故航天员出舱前后都处于失重状态，故B正确；</p>
                            <p>C、飞船在轨道2上周期大约为90分钟，而同步卫星的周期为24h，故知圆轨道上周期小于同步卫星周期，角速度大于同步卫星的角速度，故C正确；</p>
                            <P>D、飞船在P点时的加速度由万有引力产生，不管沿圆轨道还是椭圆轨道卫星在P点所受万有引力大小相等，故产生的加速度亦相同，故D错误。故选BC</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page"><a class="jumpBtn prev">上一页</a><a class="current">1</a><span>……</span><a>2</a><a>3</a><a>4</a><a>5</a><span>……</span><a>9</a><a class="jumpBtn next">下一页</a></div>
    </div>
</div>
<div id="bskt_conf_hmwk_Box" class="popBox bskt_conf_hmwk_Box hide" title="确认出题" >
    <?php $form =\yii\widgets\ActiveForm::begin( array(
        'enableClientScript' => false,
        'id' => "homework_form",
        'method'=>'post'
    )) ?>
    <div class="popCont">
        <div class="subTitleBar">
            <h5>完善作业信息</h5>
        </div>
        <div class="sUI_formList">
            <div class="row">
                名称：<input type="text" class="text homeworkName"  data-validation-engine="validate[required]" style="width: 402px">
            </div>
            <div class="row">
                <label>学段：<?=WebDataCache::getDictionaryName($department)?>
                    <input type="hidden" id="department" value="<?=$department?>">
                    <input type="hidden" id="cartId" value="<?=$cartId?>">
                </label>
                <label>学科：<?=SubjectModel::model()->getSubjectName($subject)?>
                    <input type="hidden" id="subject" value="<?=$subject?>">
                </label>
                <label>版本：
                    <?php echo Html::dropDownList('','',$versionList,   array(
                        "id" => "version",
                        'data-validation-engine' => 'validate[required]',
                        'data-prompt-target' => "grade_prompt",
                        'data-prompt-position' => "inline",
                        'data-errormessage-value-missing' => "年级不能为空",
                    ))?>
                </label>
                <label class="tomeData">分册：<?php echo Html::dropDownList('','',$chapterArray,array(
                        "id" => "tome",
                        'data-validation-engine' => 'validate[required]'
                    ) )?>
                </label>
            </div>
            <div class="row">
                章节：
                <div class="chapter_sel clearfix">
                    <div class="cha_box cha_l leftTree">
                        <ul id="pointTree" class="tree pointTree">
                            <li><i class="openSubMenu"></i><a href="javascript:;" data-value="3451" title="七年级下">六级子菜单 第一级</a>
                                <ul class="subMenu">
                                    <li><i></i><a href="javascript:;" data-value="3452" title="第五章 相交线与平行线">第二级0001</a></li>
                                    <li><i></i><a href="javascript:;" data-value="3453" title="5.1 相交线">第二级0002</a></li>
                                    <li><i class="openSubMenu"></i><a href="javascript:;" data-value="3454" title="5.1 相交线">第二级0003</a>
                                        <ul class="subMenu">
                                            <li><i></i><a href="javascript:;" data-value="3455" title="第五章 交线与平行线">第三级0001</a></li>
                                            <li><i></i><a href="javascript:;" data-value="3456" title="5.1 相交线">第三级0002</a></li>
                                            <li><i></i><a href="javascript:;" data-value="3457" title="5.1 相交线">第三级0003</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="cha_box cha_c">
                        <br><br><button id="add_custom_btn" type="button" class="bg_blue">添加</button><br><br>
                        <button id="del_custom_btn" type="button" class="">删除</button>
                    </div>
                    <div class="cha_box cha_r"><ul id="custom_sel_list" class="custom_sel_list"></ul></div>
                </div>
            </div>
        </div>
    </div>
    <div class="popBtnArea">
        <button type="button" class="okBtn create">确定</button>
        <button type="button" class="cancelBtn">取消</button>
    </div>
    <?php \yii\widgets\ActiveForm::end(); ?>
</div>
<script>
    $(function(){

        //选择 学段 学科……
        $('#version').change(function(){
                subject=$('#subject').val();
                department=$('#department').val();
            version=$(this).val();
            $.post('<?=Url::to("/platform/question/get-tome-list")?>',{
                subject:subject,
                department:department,
                version:version
            },function(result){
                $('.tomeData').html(result);
            });
                $('#custom_sel_list').empty();
        });
//        改变分册章节树变化
        $(document).on('change','#tome',function(){
            var tome=$(this).val();
            var subject=$('#subject').val();
           var  department=$('#department').val();
          var  version=$('#version').val();
            $.post('<?=Url::to("/platform/question/get-chapter-list")?>',{
                tome:tome,
                subject:subject,
                department:department,
                version:version
            },function(result){
                    $('.leftTree').html(result);
            })
        });
//        创建作业
          $('.create').click(function(){
              if ($('#homework_form').validationEngine('validate')) {
                  var homeworkName=$('.homeworkName').val();
                  var subject=$('#subject').val();
                  var  department=$('#department').val();
                  var  version=$('#version').val();
                  var chapterList=$('#custom_sel_list').find('li');
                  var cartId=$('#cartId').val();
               chapterList.each(function(index,el){
                      if(index==0){
                          chapterId=$(el).attr('id');
                      }
                  });
                  $.post('<?=Url::to(["/platform/question/create-homework"])?>',{
                      homeworkName:homeworkName,
                      subject:subject,
                      department:department,
                      version:version,
                      chapterId:chapterId,
                      cartId:cartId
                  },function(result){
                       if(result.success){
                           location.reload();
                       }
                  })
              }
          })
    })
</script>