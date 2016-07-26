<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-3
 * Time: 下午6:38
 */
/** @var CController $this */
/* @var $this yii\web\View */  $this->title="教师--个人中心--考试管理--判卷";

?>

<?php $this->beginBlock('head_html') ?>

<script>
    $(function () {
        $('.correctPaperSlide').testpaperSlider({img_id:window.location.hash.replace("#","")});

//判卷
        var tpArg = {on: false, bg: "#fff", fontSize: 14, testPaperSize: 0, page: 0, tipsTxt: null};


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

        $('.comment .text').placeholder({'top': "1px", 'value': "评语"});

        $('.correctSelect').mySelect({
            fn: function () {//下拉菜单
                //noinspection JSJQueryEfficiency
                if ($('.correctSelect .title').text() == "评分") {
                    $('.comment .text').width("142px");
                    $('.score').show();
                    $('.score .text').placeholder({'top': "1px", 'value': "分数"});
                }
                if ($('.correctSelect .title').text() == "点拨") {
                    $('.comment .text').width("300px");
                    $('.score').hide();
                }
            }
        });

        $('.slideControlPanel .ok').click(function(){
            var min_score=0;
            var max_score=<?=$fullScore?>;
            var input_score=$('.score .text').val();
            var input_comment=$('.comment .text').val();
            if(input_comment==""){
                $('.comment .text').focus().val('');
                popBox.errorBox('请输入评语!');
                return false;
            }
            if(input_score!=""){
                if(input_score>=min_score && input_score<=max_score && !isNaN(input_score)){
                    tpArg.tipsTxt='<span class="scoreTxt">'+$('.comment .text').val()+'</span><br><strong class="scoreVal">'+$('.score .text').val()+'</strong>分';

                }
                else{
                    $('.score .text').focus().val('');
                    popBox.errorBox('请输入 最低分('+min_score+')——最高分('+max_score+') 之间的数字!');
                    return false;
                }
            }
            else{
                tpArg.tipsTxt='<span class="scoreTxt">'+$('.comment .text').val()+'</span><br><strong class="scoreVal"></strong>';
            }
            tpArg.on=true;
        });

        $('.testPaperSlideList li').click(function (ev) {//添加tips
            var tipLeft = ev.clientX - $(this).offset().left + $(document).scrollLeft();
            var tipTop = ev.clientY - $(this).offset().top + $(document).scrollTop();
            if (tpArg.on == true && tpArg.tipsTxt != "") {
                $(this).append('<div class="tips" style="top:' + tipTop + 'px;left:' + tipLeft + 'px; background:' + tpArg.bg + ';font-size:' + tpArg.fontSize + 'px">' + tpArg.tipsTxt + '<span class="removeBtn hide">×</span></div>');
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

//拖拽
                //$('.tips').drag();
                $( ".tips" ).draggable({containment:"parent",stop:function(){
                    savePage();
                }});

                $('.tips .removeBtn').click(function(){
                    $(this).parent().remove();
                    savePage();
                });
//                $('.tips').drag(function () {
//                    savePage();
//                }).children('.removeBtn').click(function () {
//                    $(this).parent().remove();
//                    savePage();
//                });
                $('.slideControlPanel input:text').val("").next().show();
                tpArg.on = false;
            }
        });
//
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
            var totalScore=0;
              $(".testPaperSlideList  .scoreVal").each(function(index,el){
                var score=parseInt($(el).text());
                  if(isNaN(score)){
                      score=0;
                  }
                  totalScore+=score;
            });
            $("#score").html(totalScore);
            $(".correctEndBox").dialog("open");
        });
        $(".okBtn").click(function () {

            //noinspection JSUndeclaredVariable
            var  score = $('#score').html();
            $.post("<?php echo url('teacher/exam/finish-correct')?>", {
                "testAnswerID": "<?php echo app()->request->getParam('testAnswerID')?>",
                score: score
            }, function (result) {
                if (!result.success) {
                    popBox.errorBox(result.message);

                } else {
<!--                    location.href = "--><?php //echo url('teacher/managetask/organizeworkdetails',array('homeworkID'=>app()->request->getParam('homeworkID')))?><!--";-->
                    window.history.go(-1);
                }
            });
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

    });

    tc = null;
    function savePage() {

        clearTimeout(tc);
        tc = setTimeout(function () {
            var list = [];
            $('li.current div.tips').each(function (index, el) {
                var comments = $(el).find(".scoreTxt").html();
                var style = $(el).attr("style");
                var scoreVal=$(el).find(".scoreVal").html();
                var checkInfo = {"style": style, "comments": comments,"scoreVal":scoreVal};
                list.push(checkInfo);
            });
            var checkInfoList = {"checkInfoList": list};
            var tID = $('li.current').attr('data-value');

            $.post("<?php  echo url('teacher/exam/hold-correct')?>", {
                checkInfoJson: JSON.stringify(checkInfoList),
                testAnswerID: "<?php echo app()->request->getParam('testAnswerID')?>",
                "tID": tID
            }, function (result) {
                if (!result.success) {
                    popBox.alertBox(result.message);
                }


            })


        }, 300);

    }
</script>

<?php $this->endBlock('head_html') ?>
<?php $this->beginBlock('foot_html') ?>

<div id="dati" class="popBox correctEndBox" style="display: none" title="批改完成">
    <!--完成答题-->
    <div class="popCont" style="padding-left:100px">
        <p class="">您已完成主观题答案的批阅</p>
        <br>
        <p>得分 <span id="score"lass="text w50">
             </span>
            分
        </p>
        <!--            &nbsp;&nbsp;&nbsp;&nbsp;满分 100 分-->
        <br>

    </div>
    <div class="popBtnArea">
        <button type="button" class="okBtn">确定</button>
        <button type="button" class="cancelBtn">取消</button>
    </div>
</div>
<?php $this->endBlock('foot_html') ?>

<div class="grid_19 main_r">
    <div class="main_cont test_class_overall_appraisal">
        <div class="title"><a class="txtBtn backBtn"></a>
            <h4>在线批改</h4>

            <div class="title_r">
                <div class="pageCount"></div>
            </div>
        </div>
        <hr>
        <div class="correctPaper">
            <h5><?= $answerResult->studentName ?>的《<?= $answerResult->name ?>》</h5>

            <div class="correctPaperSlide">
                <div class="testPaperWrap mc pr">
                    <ul class="testPaperSlideList">
                        <?php foreach ($answerResult->testCheckInfoS as $v) { ?>
                            <li data-value="<?= $v->tID ?>"><img src="<?php echo publicResources() . $v->imageUrl ?>"
                                                                 width="830"
                                                                 alt=""/>
                                <?php $checkInfoList = json_decode($v->checkInfoJson);
                                if (!empty($checkInfoList->checkInfoList)) {
                                    foreach ($checkInfoList->checkInfoList as $value) { ?>

                                        <div class="tips" style="<?php echo $value->style ?>">
                                            <span class="scoreTxt"><?php echo $value->comments ?></span>
                                            <br>
                                            <?php if(!empty($value->scoreVal)){?>
                                            <strong class="scoreVal"><?=$value->scoreVal?></strong>
                                            分
                                            <?php }?>
                                            <span class="removeBtn" >×</span>
                                        </div>
                                    <?php }
                                } ?>
                            </li>


                        <?php } ?>

                    </ul>
                    <a href="javascript:" id="prevBtn" class="correctPaperPrev">上一页</a> <a href="javascript:"
                                                                                           id="nextBtn"
                                                                                           class="correctPaperNext">下一页</a>
                </div>




            </div>
            <div class="slideControlPanel">
                <div class="setBg" title="设定背景">
                    <div class="pop"><span></span> <a class="red"></a> <a class="pink"></a> <a class="yellow"></a>
                        <a
                            class="blue"></a> <a class="green"></a></div>
                </div>
                <div class="setFont"><span class="tit"></span>

                    <div class="pop"><span></span> <a>12</a> <a>14</a> <a>16</a></div>
                </div>
                <div class="mySelect correctSelect"><span class="title">点拨</span>
                    <ul class="selectList pop">
                        <li><a href="javascript:">点拨</a></li>
                        <li><a href="javascript:">评分</a></li>
                    </ul>
                    <a class="openBtn" href="javascript:"></a></div>
                <div class="comment">
                    <input class="text" type="text" style="padding:3px 0 !important">
                </div>
                <div class="score hide">
                    <b>得分</b>
                    <input class="text" type="text" style="padding:3px 0 !important">
                </div>
                <?php if($isCheck!=1){?>
                <div class="ok">确定</div>
                <?php }else{?>
                <div class="disableBtn">确定</div>
                <?php }?>
                <div class="zoom" title="放大"></div>
                <div class="help">？</div>
            </div>
            <br>
            <div class="tc bottomBtnBar">
                <?php if($isCheck!=1){?>
                <button type="button" style="height:46px; width:312px" class="bg_green correctEndBtn">批改完成</button>
                <?php }else{?>
                <button type="button" style="height:46px; width:312px" class="bg_green disableBtn">批改完成</button>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<script>
    //            点击后退按钮后退
    $(".backBtn").click(function(){
        window.history.go(-1);
    })
</script>