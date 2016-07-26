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
        $('.correctPaperSlide').testpaperSlider();

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


                $('.tips').drag(function () {
                    savePage();
                }).children('.removeBtn').click(function () {
                    $(this).parent().remove();
                    savePage();
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
                var style = $(el).attr("style");
                var checkInfo = {"style": style, "comments": comments};
                list.push(checkInfo);
            });
            var checkInfoList = {"checkInfoList": list};
            var tID = $('li.current').next(".tID").val();
            $.post("<?php  echo url('teacher/managetask/hold-correct')?>", {
                checkInfoJson: JSON.stringify(checkInfoList),
                homeworkAnswerID: "<?php echo app()->request->getParam('homeworkAnswerID')?>",
                "tID": tID
            }, function (result) {

                popBox.alertBox(result.message);

            })
        });
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
            $(".correctEndBox").dialog("open");

        });

        $(".okBtn").click(function () {

            //noinspection JSUndeclaredVariable
           var  score = $('#score').val();
            if (isNaN(score)) {
                popBox.alertBox('请输入数字');
                return false;
            }


            $.post("<?php echo url('teacher/managetask/finish-correct')?>", {
                "homeworkAnswerID": "<?php echo app()->request->getParam('homeworkAnswerID')?>",
                score: score
            }, function (result) {
                if (!result.success) {
                    popBox.alertBox(result.message);

                } else {
                    location.href = "<?php echo url('teacher/managetask/organize-work-details',array('homeworkID'=>app()->request->getParam('homeworkID')))?>";
                }
            });
        });


    });

    tc = null;
    function savePage() {
        clearTimeout(tc);
        tc = setTimeout(function () {
            var list = [];
            $('li.current div.tips').each(function (index, el) {
                var comments = $(el).find(".scoreTxt").html();
                var style = $(el).attr("style");
                var checkInfo = {"style": style, "comments": comments};
                list.push(checkInfo);
            });
            var checkInfoList = {"checkInfoList": list};
            var tID = $('li.current').attr('data-value');

            $.post("<?php  echo url('teacher/managetask/hold-correct')?>", {
                checkInfoJson: JSON.stringify(checkInfoList),
                homeworkAnswerID: "<?php echo app()->request->getParam('homeworkAnswerID')?>",
                "tID": tID
            }, function (result) {
                if (!reuslt.success) {
                    popBox.alertBox(result.message);
                }


            })


        }, 300);

    }
</script>

<?php $this->endBlock('head_html') ?>
<?php $this->beginBlock('foot_html') ?>

<div id="dati" class="popBox correctEndBox" style="display: none" title="完成答题">
    <!--完成答题-->
    <div class="popCont" style="padding-left:100px">
        <p class="">您已完成本题答案的批阅</p>
        <br>

        <p>得分 <input id="score" type="text" class="text w50"> 分
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
        <div class="title"><a href="#" class="txtBtn backBtn"></a>
            <h4><?= $answerResult->homeworkName ?></h4>

            <div class="title_r">
                <div class="pageCount"></div>
            </div>
        </div>
        <hr>
        <div class="correctPaper">
            <h5><?= $answerResult->teacherName ?>的《<?= $answerResult->homeworkName ?>》</h5>

            <div class="correctPaperSlide">
                <div class="testPaperWrap mc pr">
                    <ul class="testPaperSlideList">
                        <?php foreach ($answerResult->homeworkCheckInfoS as $v) { ?>
                            <li data-value="<?= $v->tID ?>"><img src="<?php echo publicResources() . $v->imageUrl ?>"
                                                                 width="830" height="508"
                                                                 alt=""/>
                                <?php $checkInfoList = json_decode($v->checkInfoJson);
                                if (!empty($checkInfoList->checkInfoList)) {
                                    foreach ($checkInfoList->checkInfoList as $value) { ?>

                                        <div class="tips" style="<?php echo $value->style ?>">
                                            <span class="scoreTxt"><?php echo $value->comments ?></span>
                                            <br>

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
                    <div class="ok">确定</div>

                    <div class="help">？</div>
                </div>
                <br>

                <div class="tc bottomBtnBar">
                    <button type="button" style="height:46px; width:312px" class="bg_green correctEndBtn">批改完成</button>
                </div>

            </div>
        </div>
    </div>
</div>