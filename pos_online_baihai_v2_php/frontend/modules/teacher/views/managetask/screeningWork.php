<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-4
 * Time: 下午3:49
 */
/* @var $this yii\web\View */  $this->title="筛选作业";
?>
<div class="currentRight grid_16 push_2 filterSubject">
    <div class="noticeH clearfix">
        <h3 class="h3L">题目筛选</h3>
    </div>
    <hr>
    <div class="searchBar">
        <form>
            <ul class="form_list">
                <li>
                    <div class="formL">
                        <label><i></i>题目关键字：</label>
                    </div>
                    <div class="formR">
                        <input type="text" class="text">
                        <input type="button" class="btn" value="搜索">
                        &nbsp;&nbsp;<a href="teacher-testpaper-make-ad_filterSubject.html">高级搜索</a></div>
                </li>
            </ul>
        </form>
    </div>
    <br>
    <br>

    <div class="schResult">
        <h3>搜索结果:</h3>
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label>搜索条件：</label>
                </div>
                <div class="formR  schTxtArea">
                    <p><span>(填写的搜索文本)</span></p>
                </div>
            </li>
        </ul>
        <h4>共有题目1道题如下:</h4>

        <div class="paperStructure clearfix">
            <h5>试卷中的题目:</h5>
            <ul class="paperItemList">
                <li tab="Q_blank" class="ac">填空题</li>
                <li tab="Q_choose">选择题</li>
                <li tab="Q_app">应用题</li>
            </ul>
            <div class="paperItemConts">
                <ul id="Q_blank" class="clearfix">
                    <!--<li val="b1">b1</li>
                    <li val="b2">b2</li>
                    <li val="b3">b3</li>
                    <li val="b4">b4</li>
                    <li val="b5">b5</li>-->
                </ul>
                <ul id="Q_choose" class="clearfix hide">
                    <!--<li val="c1">c1</li>
                    <li val="c2">c2</li>
                    <li val="c3">c3</li>
                    <li val="c4">c4</li>
                    <li val="c5">c5</li>-->
                </ul>
                <ul id="Q_app" class="clearfix hide">
                    <!--<li val="a1">a1</li>
                    <li val="a2">a2</li>
                    <li val="a3">a3</li>
                    <li val="a4">a4</li>
                    <li val="a5">a5</li>-->
                </ul>
                <div class="demoBar hide">
                    <span class="close">×</span>
                </div>
            </div>
        </div>
        <div class="schResult">
            <div class="testpaper"><!--选择题-->
                <button type="button" id="c10" pid="Q_chodddose" class="editBtn addBtn">组卷</button>
                <h5>题目1:</h5>
                <h6>选择题(单选)</h6>

                <p>题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分</p>

                <div class="checkArea">
                    <input type="radio" class="radio" name="aaa">
                    <label>A 备选项1</label>
                    <input type="radio" class="radio" name="aaa">
                    <label>B 备选项2</label>
                    <input type="radio" class="radio" name="aaa">
                    <label>C 备选项3</label>
                    <input type="radio" class="radio" name="aaa">
                    <label>D 备选项4</label>
                </div>
                <div class="btnArea clearfix"><span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span> <span
                        class="r_btnArea fr">难度:<em>5</em>&nbsp;&nbsp;&nbsp;录入:admin</span></div>
                <div class="answerArea hide">
                    <p><em>答案:</em><span>1.答案</span><span>2.答案</span><span>3.答案</span></p>

                    <p><em>解析:</em>解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容
                    </p>
                </div>
            </div>

            <div class="testpaper"><!--选择题-->
                <button type="button" id="c11" pid="Q_choose" class="editBtn addBtn">组卷</button>
                <h5>题目2:</h5>
                <h6>选择题(多选)</h6>

                <p>题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分</p>

                <div class="checkArea">
                    <input type="checkbox" class="checkbox">
                    <label>A 备选项1</label>
                    <input type="checkbox" class="checkbox">
                    <label>B 备选项2</label>
                    <input type="checkbox" class="checkbox">
                    <label>C 备选项3</label>
                    <input type="checkbox" class="checkbox">
                    <label>D 备选项4</label>
                </div>
                <div class="btnArea clearfix"><span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span> <span
                        class="r_btnArea fr">难度:<em>5</em>&nbsp;&nbsp;&nbsp;录入:admin</span></div>
                <div class="answerArea hide">
                    <p><em>答案:</em><span>1.答案</span><span>2.答案</span><span>3.答案</span></p>

                    <p><em>解析:</em>解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容
                    </p>
                </div>
            </div>


            <div class="testpaper"><!--选择题-->
                <button type="button" id="b6" pid="Q_blank" class="editBtn addBtn">组卷</button>
                <h5>题目3:</h5>
                <h6>填空题(完形填空)</h6>

                <p>题干__1__干部分题干部分题干部分__2__题干部分题干部分题干部分题干部__3__分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分</p>

                <div class="checkArea">
                    <ul>
                        <li><i>1.</i><input type="radio" class="radio" name="q1" checked><label>A 备选项1</label><input
                                type="radio" class="radio" name="q1"><label>B 备选项2</label><input type="radio"
                                                                                                 class="radio"
                                                                                                 name="q1"><label>C
                                备选项3</label>
                        </li>
                        <li><i>2.</i><input type="radio" class="radio" name="q2"><label>A 备选项1</label><input
                                type="radio" class="radio" name="q2"><label>B 备选项2</label><input type="radio"
                                                                                                 class="radio"
                                                                                                 name="q2"><label>C
                                备选项3</label>
                        </li>
                        <li><i>3.</i><input type="radio" class="radio" name="q3"><label>A 备选项1</label><input
                                type="radio" class="radio" name="q3"><label>B 备选项2</label><input type="radio"
                                                                                                 class="radio"
                                                                                                 name="q3"><label>D
                                备选项3</label>
                        </li>
                    </ul>
                </div>
                <div class="btnArea clearfix"><span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span> <span
                        class="r_btnArea fr">难度:<em>5</em>&nbsp;&nbsp;&nbsp;录入:admin</span></div>
                <div class="answerArea hide">
                    <p><em>答案:</em><span>1.答案</span><span>2.答案</span><span>3.答案</span></p>

                    <p><em>解析:</em>解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容
                    </p>
                </div>
            </div>
        </div>
        <div class="page"><a class="jumpBtn">首页</a><a class="jumpBtn">上一页</a><a class="current">1</a><a>2</a><a>3</a><a>4</a><a>5</a><span>……</span><a>9</a><a
                class="jumpBtn">下一页</a><a class="jumpBtn">末页</a></div>
    </div>
    <p class="tc bottomBtnBar">
        <button type="button" class="btn preStepBtn">上一步</button>
        <button type="button" class="btn nextStepBtn">确定</button>
    </p>
</div>
<script>
    $(function () {
//已选题目 选项卡
        $('.paperItemList li').click(function () {
            var index = $(this).index();
            $(this).addClass('ac').siblings().removeClass('ac');
            $('.paperItemConts ul').eq(index).show().siblings().hide();
        });
        $('.openAnswerBtn').click(function () {
            $(this).parents('.testpaper').children('.answerArea').toggle();
        });

//试卷中的题目 fixed
        var divTop = $('.paperStructure').offset().top;
        var divW = $('.paperStructure').width();
        var divH = $('.paperStructure').outerHeight() + 20;
        var windowScrollTop;
        $(window).scroll(function () {
            windowScrollTop = $(window).scrollTop();
            if (windowScrollTop >= divTop) {
                $('.paperStructure').css({'position': 'fixed', 'top': 0, 'width': divW, 'z-index': 100});
                $('.paperStructure').next().css({'padding-top': divH + 'px'})
            }
            else {
                $('.paperStructure').css({'position': 'static'});
                $('.paperStructure').next().css({'padding-top': 0})
            }
        });

//组卷按钮
        $('.testpaper .addBtn').live('click', function () {
            var id = $(this).attr('id');
            var pid = $(this).attr('pid');
            var index = $('#' + pid).index();
            var tab = "";
            $('.paperItemList li').each(function () {//判断是否有此题型
                if ($(this).attr('tab') == pid) tab = true;
            });
            if (tab == true) {
                $(this).removeClass('addBtn').addClass('delBtn').text('删除');
                $('.paperItemList li').eq(index).addClass('ac').siblings().removeClass('ac');
                $('#' + pid).show().siblings().hide();
                $('#' + pid).append('<li>' + id + '</li>');
            } else {
                popBox.errorBox('本试卷没有该题型!!')
            }
        });

        $('.testpaper .delBtn').live('click', function () {
            var id = $(this).attr('id');
            $(this).removeClass('delBtn').addClass('addBtn').text('组卷');
            var pid = $(this).attr('pid');
            var index = $('#' + pid).index();
            $('.paperItemList li').eq(index).addClass('ac').siblings().removeClass('ac');
            $('#' + pid).show().siblings().hide();
            $('#' + pid + ' li').each(function (index, element) {
                if ($(this).text() == id) $(this).remove();
            });
        });

//点击题目id,显示题型
        $('.paperItemConts li').live('click', function () {
            $('.paperItemConts .demoBar').show();
        });
        $('.demoBar .close').click(function () {
            $(this).parent().hide();
        })

    })
</script>