<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-17
 * Time: 下午5:33
 */
/* @var $this yii\web\View */  $this->title="批改试卷";
?>
<div class="currentRight grid_16 push_2 test_correctPaper">
    <div class="noticeH clearfix">
        <h3 class="h3L">教师判卷</h3>
    </div>
    <hr>
    <div class="correctPaper">
        <h5>孙悟空的《期末考试模拟试卷》</h5>

        <div class="correctPaperSlide">
            <div class="testPaperWrap mc">
                <ul class="testPaperSlideList">
                    <?php foreach ($answerResult->picList as $v) { ?>
                        <li><img src="<?php echo publicResources() . $v->picUrl ?>" width="830" height="508"
                                 alt=""/></li>
                        <input type="hidden" class="picID" value="<?php echo $v->picID?>">
                        <input type="hidden" class="answerID" value="<?php echo $v->answerID?>">
                    <?php } ?>
                </ul>
            </div>
            <a href="javascript:" id="correctPaperPrev" class="correctPaperPrev">上一页</a> <a href="javascript:;"
                                                                                             id="correctPaperNext"
                                                                                             class="correctPaperNext">下一页</a>

            <div class="slideControlPanel">
                <div class="setBg" title="设定背景">
                    <div class="pop"><span></span> <a class="red"></a> <a class="pink"></a> <a class="yellow"></a> <a
                            class="blue"></a> <a class="green"></a></div>
                </div>
                <div class="setFont"><span class="tit"></span>

                    <div class="pop"><span></span> <a>12</a> <a>14</a> <a>16</a></div>
                </div>
                <div class="mySelect correctSelect"><span class="title">点拨</span>
                </div>
                <div class="comment">
                    <input class="text" type="text">
                </div>
                <div class="score hide">
                    <input class="text" type="text">
                </div>
                <div class="ok">确定</div>
                <div class="finish">保存本页批改</div>
                <div id="tipsPrev" class="tipsPrev"></div>
                <div class="play"></div>
                <div id="tipsNext" class="tipsNext"></div>
                <div class="hideText">隐藏批语</div>
                <div class="help">？</div>
            </div>
            <br>

            <div class="tc bottomBtnBar">
                <button type="button" class="btn correctEndBtn">批改完成</button>
            </div>

        </div>
    </div>
</div>
<script>
    $(function () {

//判卷
        var tpArg = {on: false, bg: "#fff", fontSize: 14, testPaperSize: 0, page: 0, tipsTxt: null};

        function current() {
            $('.testPaperSlideList li').eq(tpArg.page).addClass('current').siblings().removeClass('current')
        }

        current();

        tpArg.testPaperSize = $('.testPaperSlideList li').size();//翻页
        $('.testPaperSlideList').css("width", tpArg.testPaperSize * 800);
        $('#correctPaperNext').click(function () {
            if (tpArg.page < tpArg.testPaperSize - 1) {
                tpArg.page++;
                $('.testPaperSlideList').animate({"left": -800 * tpArg.page});
                current();
            }
        });
        $('#correctPaperPrev').click(function () {
            if (tpArg.page > 0) {
                tpArg.page--;
                $('.testPaperSlideList').animate({"left": -800 * tpArg.page});
                current();
            }
        });

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

        $('.correctSelect').mySelect({fn: function () {//下拉菜单
            if ($('.correctSelect .title').text() == "评分") {
                $('.comment .text').width("88px");
                $('.score').show();
                $('.score .text').placeholder({'top': "1px", 'value': "分数"});
            }
            if ($('.correctSelect .title').text() == "点拨") {
                $('.comment .text').width("150px");
                $('.score').hide();
            }
        }});


        $('.slideControlPanel .ok').click(function () {
            if ($('.score .text').val() != "") {
                tpArg.tipsTxt = '<span class="scoreTxt">' + $('.comment .text').val() + '</span><br><strong class="scoreVal">' + $('.score .text').val() + '</strong>分';
            }
            else {
                tpArg.tipsTxt = '<span class="scoreTxt">' + $('.comment .text').val() + '</span><br><strong class="scoreVal"></strong>';
            }
            tpArg.on = true;
        });

        $('.testPaperSlideList li').click(function (ev) {//添加tips
            var tipLeft = ev.clientX - $(this).offset().left + $(document).scrollLeft();
            var tipTop = ev.clientY - $(this).offset().top + $(document).scrollTop();
            if (tpArg.on == true && tpArg.tipsTxt != "") {
                $(this).append('<div class="tips" style="top:' + tipTop + 'px;left:' + tipLeft + 'px; background:' + tpArg.bg + ';font-size:' + tpArg.fontSize + 'px">' + tpArg.tipsTxt + '<span class="removeBtn hide">×</span></div>');

                var TipsJson = {id: null, pid: null, left: tipLeft, top: tipTop, background: tpArg.bg, fontSize: tpArg.fontSize };
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


                $('.tips').drag().children('.removeBtn').click(function () {
                    $(this).parent().remove();
                });
                $('.slideControlPanel input:text').val("").next().show();
                tpArg.on = false;
            }
        });
//        保存本页批改
        $(".finish").click(function () {
            var list = [];
            $('li.current div.tips').each(function (index, el) {
//                var score=$(el).find(".scoreVal").html();
                var comments = $(el).find(".scoreTxt").html();
                var style=$(el).attr("style");
                var checkInfo = {"style":style,"comments": comments};
                list.push(checkInfo);
            });
            var checkInfoList = {"checkInfoList": list};
            var picID = $('li.current').next(".picID").val();
            var answerID=$('li.current').next().next(".answerID").val();
            $.post("<?php  echo url('student/managetask/hold-organize-correct')?>", {checkInfoJson: JSON.stringify(checkInfoList), homeworkAnswerID: "<?php echo app()->request->getQueryParam('homeworkAnswerID')?>", picID: picID,answerID:answerID}, function (result) {

                popBox.alertBox(result.message);

            })
        });
//        批改完成
        $(".correctEndBtn").click(function () {
            $.post("<?php echo url('student/managetask/finish-correct')?>", {"homeworkAnswerID": "<?php echo app()->request->getQueryParam('homeworkAnswerID')?>"}, function (result) {
                if (result.code == 1) {
                    popBox.alertBox(result.message);
                    location.href = "<?php echo url('student/managetask/index')?>";
                } else {
                    popBox.alertBox(result.message);
                }
            })
        })


    })
</script>
