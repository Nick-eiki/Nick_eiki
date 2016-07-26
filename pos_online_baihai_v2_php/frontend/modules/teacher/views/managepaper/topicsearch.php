<?php

/* @var $this yii\web\View */  $this->title="题目管理";
$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);


?>
<script>

        $(function(){
            $(".btn").click(function(){
                var name = $(".text").val();
                var url = '<?php echo app()->request->url?>';
                $.post(url, {name:name}, function (result) {
                    alert(result);exit;
                    $('#srchResult').html(result);
                })

            });
            $('.paperItemList li').click(function(){
                $(this).addClass('ac').siblings().removeClass('ac');
            });
            $('.openAnswerBtn').click(function(){
                $(this).parents('.testpaper').children('.answerArea').toggle();
            });

            var divTop=$('.paperStructure').offset().top;
            var divW=$('.paperStructure').width();
            var divH=$('.paperStructure').outerHeight()+20;
            var windowScrollTop;
            $(window).scroll(function(){
                windowScrollTop=$(window).scrollTop();
                if(windowScrollTop>=divTop){
                    $('.paperStructure').css({'position':'fixed','top':0,'width':divW,'z-index':100});
                    $('.paperStructure').next().css({'padding-top':divH+'px'})
                }
                else{
                    $('.paperStructure').css({'position':'static'});
                    $('.paperStructure').next().css({'padding-top':0})
                }

            })

        })
    </script>

<!--    <meta charset="utf-8">
    <title>教师-题目管理-筛选题目</title>
    <link href="../css/base.css" type="text/css" rel="stylesheet">
    <link href="../css/teacher.css" type="text/css" rel="stylesheet">
    <link href="../css/jquery-ui.css" type="text/css" rel="stylesheet">
    <link href="../css/popBox.css" type="text/css" rel="stylesheet">
    <link href="../js/ztree/zTreeStyle/zTreeStyle.css" rel="stylesheet" type="text/css">
    <script src="../js/jquery-1.7.1.min.js" type="text/javascript"></script>
    <script src="../js/main.js" type="text/javascript"></script>
    <script src="../js/base.js" type="text/javascript"></script>
    <script src='../js/jquery-ui.min.js' type="text/javascript"></script>
    <script src="../js/ztree/jquery.ztree.all-3.5.min.js" type="text/javascript"></script>-->
</body>

<div class="currentRight grid_16 push_2 filterSubject">
    <div class="noticeH clearfix">
        <h3 class="h3L">题目筛选</h3>
        <div class="new_not fr">
            <select class="mySel">
                <option value="001">我录入的题目</option>
            </select>
            <a href="javascript:" class="B_btn120 btn uploadNewtestpaperBtn">录入题目</a> </div>
    </div>
    <hr>
    <br>
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
                        &nbsp;&nbsp;<a href="teacher-testpaper-make-ad_filterSubject.html">高级搜索</a> </div>
                </li>
            </ul>
        </form>
    </div>
    <br>
    <br>
    <?php if($item){?>
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
                <li class="ac">填空题</li>
                <li>选择题</li>
                <li>应用题</li>
            </ul>
            <div class="paperItemConts">
                <ul class="clearfix">
                    <li>题目id</li>
                    <li>题目id</li>
                    <li>题目id</li>
                    <li>题目id</li>
                    <li>题目id</li>
                </ul>
            </div>
        </div>
        <div class="schResult">
            <div class="testpaper"><!--选择题-->
                <span class="editBtn">组卷</span>
                <h5>题目1:</h5>
                <h6>【2013年】 高考 选择题</h6>
                <p>题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分</p>
                <div class="checkArea">
                    <input type="radio" class="radio" name="aaa">
                    <label>A 备选项1</label>
                    <input type="radio" class="radio"  name="aaa">
                    <label>B 备选项2</label>
                    <input type="radio" class="radio"  name="aaa">
                    <label>C 备选项3</label>
                    <input type="radio" class="radio"  name="aaa">
                    <label>D 备选项4</label>
                </div>
                <div class="btnArea clearfix"> <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span> <span class="r_btnArea fr">难度:<em>5</em>&nbsp;&nbsp;&nbsp;录入:admin</span> </div>
                <div class="answerArea hide">
                    <p><em>答案:</em><span>1.答案</span><span>2.答案</span><span>3.答案</span></p>
                    <p><em>解析:</em>解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容</p>
                </div>
            </div>
            <div class="testpaper"><!--选择题-->
                <span class="editBtn delBtn">删除</span>
                <h5>题目1:</h5>
                <h6>【2013年】 高考 选择题</h6>
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
                <div class="btnArea clearfix"> <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span> <span class="r_btnArea fr">难度:<em>5</em>&nbsp;&nbsp;&nbsp;录入:admin</span> </div>
                <div class="answerArea hide">
                    <p><em>答案:</em><span>1.答案</span><span>2.答案</span><span>3.答案</span></p>
                    <p><em>解析:</em>解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容</p>
                </div>
            </div>
            <div class="testpaper"><!--选择题-->
                <span class="editBtn">组卷</span>
                <h5>题目1:</h5>
                <h6>【2013年】 高考 选择题</h6>
                <p>题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分</p>
                <div class="checkArea">
                    <ul>
                        <li><i>1.</i>
                            <input type="checkbox" class="checkbox" checked>
                            <label>A 备选项1</label>
                            <input type="checkbox" class="checkbox">
                            <label>B 备选项2</label>
                            <input type="checkbox" class="checkbox">
                            <label>C 备选项3</label>
                            <input type="checkbox" class="checkbox">
                            <label>D 备选项4</label>
                        </li>
                        <li><i>2.</i>
                            <input type="checkbox" class="checkbox">
                            <label>A 备选项1</label>
                            <input type="checkbox" class="checkbox">
                            <label>B 备选项2</label>
                            <input type="checkbox" class="checkbox">
                            <label>C 备选项3</label>
                            <input type="checkbox" class="checkbox">
                            <label>D 备选项4</label>
                        </li>
                        <li><i>3.</i>
                            <input type="checkbox" class="checkbox">
                            <label>A 备选项1</label>
                            <input type="checkbox" class="checkbox">
                            <label>B 备选项2</label>
                            <input type="checkbox" class="checkbox">
                            <label>C 备选项3</label>
                            <input type="checkbox" class="checkbox">
                            <label>D 备选项4</label>
                        </li>
                    </ul>
                </div>
                <div class="btnArea clearfix"> <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span> <span class="r_btnArea fr">难度:<em>5</em>&nbsp;&nbsp;&nbsp;录入:admin</span> </div>
                <div class="answerArea hide">
                    <p><em>答案:</em><span>1.答案</span><span>2.答案</span><span>3.答案</span></p>
                    <p><em>解析:</em>解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容</p>
                </div>
            </div>
        </div>
        <div class="page"><a class="jumpBtn">首页</a><a class="jumpBtn">上一页</a><a class="current">1</a><a>2</a><a>3</a><a>4</a><a>5</a><span>……</span><a>9</a><a class="jumpBtn">下一页</a><a class="jumpBtn">末页</a></div>
    </div>
    <p class="tc bottomBtnBar">
        <button type="button" class="preStepBtn">上一步</button>
        <button type="button" class="nextStepBtn">下一步</button>
    </p>
</div>
<?php }?>
</div>
</div>
</div>
<!--主体内容结束-->