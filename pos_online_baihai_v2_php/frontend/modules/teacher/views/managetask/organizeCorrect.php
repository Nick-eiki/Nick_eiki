<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-17
 * Time: 下午5:33
 */
/* @var $this yii\web\View */  $this->title="批改试卷";
?>

<script>
$(function () {
    $('.correctPaperSlide').testpaperSlider();

    var tpArg={on:false,bg:"#fff",fontSize:14,testPaperSize:0,page:0,tipsTxt:'',tipsMark:'<div class="tipsMark"></div>'};
//on:可以添加tips   tipsTxt:tips内容


    $('.setBg,.setFont').click(function () {//打开弹出框
        $(this).children('div').show();
        return false;
    });
    $('.setBg div a').click(function () {//设定背景
        $(this).parent().hide();
        $('.setBg').css('background-color', $(this).css('background-color'));
        tpArg.bg = $(this).css('background-color');
        return false;
    });
    $('.setFont div a').click(function () {//设定字号
        $(this).parent().hide();
        $('.setFont').css("background-image", "none").children('.tit').text($(this).text());
        tpArg.fontSize = $(this).text();
        return false;
    });

    $('.slideControlPanel .ok').click(function () {
        var acs=$('.slideControlPanel .ac').size();
        if($('.comment .text').val()!="" || acs>0){
            $(this).addClass('ac');
            tpArg.tipsTxt='<span class="commentTxt">'+$('.comment .text').val()+'</span>';
            tpArg.on=true;
        }
        else{
            $(this).removeClass('ac');
            popBox.errorBox('请填写"评语" 或者 选择"判卷标签"');
        }
    });
    /*
    * ‘answerRight’:”对错判断” 0错，
     -1 半对，
     1 对
     2 没有判断
    * */

    $('.slideControlPanel .mark').click(function(){
        $(this).addClass('ac').siblings('.mark').removeClass('ac');
    });
//放大镜
    function zoom(){
        var oSmall=$('.testPaperSlideList .current');
        var img_src=oSmall.children('img').attr('src');
        $('body').append('<div id="zoomInBox" class="zoomInBox hide"><img src="'+img_src+'" alt=""/></div>');
        oSmall.append('<div id="mask" class="mask"></div>');
        var mask=$('#mask');

        var oBig=$('#zoomInBox');
        var oImg=$('.zoomInBox img');

        oSmall.mousemove(function(){
            mask.show();
            oBig.show();
        });

        oSmall.mouseout(function(){
            mask.hide();
            oBig.hide();
        });

        oSmall.mousemove(function(event){
            var l=event.pageX-oSmall.offset().left-mask.width()/2;
            var t=event.pageY-oSmall.offset().top-mask.height()/2;
            var def_w=oSmall.width()-mask.width();
            var def_h=oSmall.height()-mask.height();

            l<0 && (l=0);
            l>def_w && (l=def_w);
            t<0 && (t=0);
            t>def_h && (t=def_h);

            mask.css({'left':l,'top':t});
            oBig.css({'left':event.pageX+80,'top':event.pageY-50});
            oImg.css({'left':-l*(oImg.width()-oBig.width())/def_w,'top':-t*(oImg.height()-oBig.height())/def_h});
        });
    }
    $('.zoom').toggle(
        function(){
            $(this).addClass('cancel_zoom');
            zoom();
        },
        function(){
            $(this).removeClass('cancel_zoom');
            $('#mask,#zoomInBox').remove();
        }
    );
//正确
    $('.slideControlPanel .correct').click(function(){
        tpArg.tipsMark='<div class="tipsMark"><i class="tipsCorrect" data-value="1"></i></div>';
        tpArg.simple=true;
    });
//半对
    $('.slideControlPanel .problem').click(function(){
        tpArg.tipsMark='<div class="tipsMark"><i class="tipsProblem" data-value="-1"></i></div>';
        tpArg.simple=true;

    });
//错误
    $('.slideControlPanel .wrong').click(function(){
        tpArg.tipsMark='<div class="tipsMark"><i class="tipsWrong" data-value="0"></i></div>';
        tpArg.simple=true;
    });

    $('.testPaperSlideList li').click(function (ev) {//添加tips
        if(tpArg.on==false){
            popBox.errorBox('填写评语 或 判卷标签,点击[确定]');
        }
        var tipLeft = ev.clientX - $(this).offset().left + $(document).scrollLeft();
        var tipTop = ev.clientY - $(this).offset().top + $(document).scrollTop();
        if(tpArg.on==true && tpArg.tipsTxt!=""){

            $('.slideControlPanel .mark,.slideControlPanel .ok').removeClass('ac');
            var html='<div class="tips" style="top:'+tipTop+'px;left:'+tipLeft+'px; background:'+tpArg.bg+';font-size:'+tpArg.fontSize+'px">'+tpArg.tipsMark+tpArg.tipsTxt+'<span class="removeBtn hide">×</span></div>';
            $(this).append(html);
            tpArg.tipsMark='<div class="tipsMark"></div>';

            savePage();
            var TipsJson = {
                id: null,
                pid: null,
                left: tipLeft,
                top: tipTop,
                background: tpArg.bg,
                fontSize: tpArg.fontSize
            };
            var timer;
            $('.tips').hover(
                function () {
                    var _this = $(this);
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        _this.children('.removeBtn').fadeIn()
                    }, 800)
                },
                function () {
                    var _this = $(this);
                    clearTimeout(timer);
                    _this.children('.removeBtn').fadeOut()
                }
            );
            $( ".tips" ).draggable({stop:function(){
                savePage();
            }});

            $('.tips .removeBtn').click(function(){
                $(this).parent().remove();
                savePage();
            });

                $('.comment .text').val("").placeholder({'top':"5px",'value':"评语"});
            tpArg.on = false;
        }
    });
//        保存本页批改

    $('.popBox').dialog({
        autoOpen: false,
        width: 480,
        modal: true,
        resizable: false,
        close: function () {
            $(this).dialog("close")
        }
    });
    //        批改完成
    $(".correctEndBtn").click(function () {
        $.post("<?php echo url('teacher/managetask/finish-correct')?>", {"homeworkanswerid": "<?php echo app()->request->getParam('homeworkanswerid')?>"}, function (result) {
            if (result.success) {
                popBox.successBox(result.message);
                window.onbeforeunload=null;
                location.href = "<?php echo url('teacher/managetask/work-details',array('classhworkid'=>$answerResult->relId))?>";
            } else {
                popBox.alertBox(result.message);
            }
        })
    });

    $(".okBtn").click(function () {

        //noinspection JSUndeclaredVariable
        var score = $('#score').val();
        if (isNaN(score)) {
            popBox.alertBox('请输入数字');
            return false;
        }


        $.post("<?php echo url('teacher/managetask/finish-correct')?>", {
            "homeworkAnswerID": "<?php echo app()->request->getParam('homeworkanswerid')?>",
            score: score
        }, function (result) {
            if (!result.success) {
                popBox.alertBox(result.message);

            } else {
                window.onbeforeunload=null;
                location.href = "<?php echo url('teacher/managetask/work-details',array('classhworkid'=>$answerResult->relId))?>";
            }
        });
    });
    //截止刷新
    window.onbeforeunload=function () {
        event.returnValue = "重新加载页面将不能修改此前所做的批改!";
    }

});

tc = null;
function savePage() {
    clearTimeout(tc);
    tc = setTimeout(function () {
        var list = [];
        $('li.current div.tips').each(function (index, el) {
            var comments = $(el).find(".commentTxt").html();
            var style = $(el).attr("style");
            var answerRight = $(el).find("i").attr("data-value");
            if(answerRight==''|| answerRight==undefined){
                answerRight ='2';
            }
            var checkInfo = {"style": style, "comments": comments,"answerRight":answerRight};
            list.push(checkInfo);
        });
        var checkInfoList = {"checkInfoList": list};
        var picID = $('li.current').next(".picID").val();
        var answerID = $('li.current').next().next(".answerID").val();
        $.post("<?php  echo url('teacher/managetask/hold-organize-correct')?>", {checkInfoJson: JSON.stringify(checkInfoList), homeworkanswerid: "<?php echo app()->request->getParam('homeworkanswerid')?>", picID: picID, answerID: answerID}, function (data) {
            if (!data.success) {
                popBox.alertBox(data.message);
            }
        })

    }, 300);

}
</script>
<!--主体-->

<div class="grid_19 main_r">
    <div class="main_cont test_class_overall_appraisal">
        <div class="title"><a href="<?php echo url('teacher/managetask/work-details',['classhworkid'=>$answerResult->relId])?>" class="txtBtn backBtn"></a>
            <h4>在线批改</h4>

            <div class="title_r">
                <div class="pageCount"></div>
            </div>
        </div>
        <div class="correctPaper">
            <h5><?php echo $answerResult->studentName; ?>的《<?php echo $homeworkName->name ?>》</h5>

            <div class="correctPaperSlide">
                <div class="testPaperWrap mc pr">
                    <ul class="testPaperSlideList slid">
                        <?php foreach ($answerResult->picList as $v) {
                            ?>
                            <li><img src="<?php echo publicResources() . $v->picUrl ?>" width="830"
                                     alt=""/>
                                <?php $checkInfoList = json_decode($v->checkJson);
                                if (!empty($checkInfoList->checkInfoList)) {
                                    foreach ($checkInfoList->checkInfoList as $value) { ?>

                                        <div class="tips" style="<?php echo $value->style ?>">
                                            <div class="tipsMark">
                                                <?php switch ($value->answerRight) {
                                                    case '-1':
                                                        ?>
                                                        <i class="tipsProblem" data-value="-1"></i>
                                                        <?php
                                                        break;
                                                    case '0':
                                                        ?>
                                                        <i class="tipsWrong" data-value="0"></i>
                                                        <?php  break;
                                                    case '1':
                                                        ?>
                                                        <i class="tipsCorrect" data-value="1"></i>
                                                        <?php        break;
                                                    case '2':
                                                        break;
                                                }?>
                                            </div>
                                            <span class="commentTxt"><?php echo $value->comments ?></span>
                                            <br>

                                        </div>
                                    <?php }
                                } ?>
                            </li>
                            <input type="hidden" class="picID" value="<?php echo $v->picID ?>">
                            <input type="hidden" class="answerID" value="<?php echo $v->answerID ?>">
                        <?php } ?>
                    </ul>
                    <a href="javascript:;" id="prevBtn" class="correctPaperPrev">上一页</a>
                    <a href="javascript:;" id="nextBtn" class="correctPaperNext">下一页</a>

                </div>
            </div>
            <div class="slideControlPanel">
                <div class="setBg" title="设定背景">
                    <div class="pop"><span></span> <a class="red"></a> <a class="pink"></a> <a class="yellow"></a> <a
                            class="blue"></a> <a class="green"></a></div>
                </div>
                <div class="setFont"><span class="tit"></span>

                    <div class="pop"><span></span> <a>12</a> <a>14</a> <a>16</a></div>
                </div>
                <div class="mySelect correctSelect tc gray_d">点拨</div>
                <div class="comment">
                    <input class="text" type="text" style="padding:3px 0 !important">
                </div>
                <div class="score hide">
                    <b>得分</b>
                    <input class="text" type="text" style=" padding:3px 0 !important">
                </div>
                <div title="正确" class="mark correct">正确</div>
                <div title="半对" class="mark problem">半对</div>
                <div title="错误" class="mark wrong">错误</div>
                <div class="ok">确定</div>
                <div class="zoom" title="放大"></div>

            </div>
            <br>

            <div class="tc bottomBtnBar">
                <button type="button" style="height:46px; width:312px" class="bg_green correctEndBtn">批改完成</button>
            </div>


        </div>
    </div>
</div>


<!--主体end-->
