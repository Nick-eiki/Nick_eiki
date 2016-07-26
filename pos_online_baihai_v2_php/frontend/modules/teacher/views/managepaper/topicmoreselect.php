<?php
/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-11-15
 * Time: 下午4:55
 */

/* @var $this yii\web\View */  $this->title="筛选题目";
$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/ztree/jquery.ztree.all-3.5.min.js');
$this->registerJsFile($backend_asset . '/js/ztree/zTreeStyle/zTreeStyle.css'.RESOURCES_VER);

?>
<script>
    $(function(){
        $('.paperItemList li').click(function(){
            $(this).addClass('ac').siblings().removeClass('ac');
        });
        $('.openAnswerBtn').click(function(){
            $(this).parents('.testpaper').children('.answerArea').toggle();
        });


        var zNodes =[
            { id:1, pId:0, name:"语文"},
            { id:11, pId:1, name:"拼音"},
            { id:111, pId:11, name:"声母"},
            { id:112, pId:12, name:"韵母"},
            { id:12, pId:1, name:"标点符号"},
            { id:13, pId:1, name:"造句"},
            { id:14, pId:1, name:"语法"}
        ];

        popBox.pointTree2(zNodes,$('.addPointBtn'));//初始化树

    })
</script>

<div class="currentRight grid_16 push_2 filterSubject">
<div class="noticeH clearfix">
    <h3 class="h3L">题目筛选</h3>
</div>
<hr>

<br>
<div class="searchBar clearBoth advancedSearch clearfix">
    <h4>高级搜索:</h4>
    <form>
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label><i></i>使用地区:</label>
                </div>
                <div class="formR">
                    <select>
                        <option value="选择省">选择省</option>
                    </select>
                    <select>
                        <option value="选择地区">选择地区</option>
                        <option value="1990">1900</option>
                        <option value="1990">1900</option>
                        <option value="1990">1900</option>
                        <option value="1990">1900</option>
                    </select>
                    <select>
                        <option value="选择县">选择县</option>
                        <option value="1990">1900</option>
                        <option value="1990">1900</option>
                        <option value="1990">1900</option>
                        <option value="1990">1900</option>
                    </select>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>适用年级:</label>
                </div>
                <div class="formR">
                    <select class="mySel">
                        <option value="">一年级</option>
                        <option value="">二年级</option>
                        <option value="">三年级</option>
                    </select>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>科目:</label>
                </div>
                <div class="formR">
                    <select>
                        <option value="">数学</option>
                        <option value="">语文</option>
                        <option value="">英语</option>
                    </select>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>版本:</label>
                </div>
                <div class="formR">
                    <select>
                        <option value="">人教版</option>
                    </select>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>知识点:</label>
                </div>
                <div class="formR">
                    <div class="treeParent">
                        <button type="button" class="addPointBtn">编辑知识点</button>
                        <div class="pointArea hide">
                            <input class="hidVal" type="hidden" value="">
                            <h6>已选中知识点:</h6>
                            <ul class="labelList"></ul>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>题型:</label>
                </div>
                <div class="formR">
                    <select>
                        <option value="">单选</option>
                        <option value="">多选</option>

                    </select>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>出处:</label>
                </div>
                <div class="formR">
                    <select>
                        <option value="">高考</option>
                        <option value="">中考</option>
                        <option value="">升级</option>
                        <option value="">普通</option>
                        <option value="">上学期末</option>
                        <option value="">下学期末</option>
                    </select>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>年份:</label>
                </div>
                <div class="formR">
                    <select>
                        <option value="">2013年</option>
                        <option value="">2014年</option>
                        <option value="">2015年</option>
                    </select>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>名校:</label>
                </div>
                <div class="formR">
                    <select>
                        <option value="">北京四中</option>
                        <option value="">人大附中</option>
                        <option value="">其他</option>
                    </select>
                    <input type="text" class="text hide">
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>自定义标签:</label>
                </div>
                <div class="formR">
                    <input type="text" value="" class="input_box text">
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>题目关键字:</label>
                </div>
                <div class="formR">
                    <input type="text" value="" class="input_box text">
                </div>
            </li>
            <li>
                <div class="formL">
                    <label></label>
                </div>
                <div class="formR">
                    <button type="button" class="btn20">搜索</button>
                </div>
            </li>
        </ul>
    </form>
</div>
<div class="schResult">
    <h3>搜索结果:</h3>
    <ul class="form_list">
        <li>
            <div class="formL">
                <label>搜索条件：</label>
            </div>
            <div class="formR schTxtArea">
                <p><span>北京</span><span>东城区</span><span>一年级</span><span>数学</span><span>人教版</span></p>
                <p><span>知识点一</span><span>知识点二</span></p>
                <p><span>选择题</span><span>高考</span><span>2013年</span> </p>
                <p><span>标签一</span><span>标签二</span></p>
            </div>
        </li>
    </ul>
    <h4>共有题目1道题如下:</h4>
    <div class="schResultItems">
        <div class="paperStructure clearfix">
            <h5>试卷中的题目:</h5>
            <ul class="paperItemList">
                <li class="ac">单选</li>
                <li>多选</li>

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
        <div class="testpaper"><!--选择题-->
            <span class="editBtn">组题</span>
            <h5>题目1:</h5>
            <h6>【2013年】 高考 选择题</h6>
            <p>题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分</p>
            <div class="checkArea"><input type="radio" class="radio" name="aaa"><label>A 备选项1</label><input type="radio" class="radio"  name="aaa"><label>B 备选项2</label><input type="radio" class="radio"  name="aaa"><label>C 备选项3</label><input type="radio" class="radio"  name="aaa"><label>D 备选项4</label></div>
            <div class="btnArea clearfix">
                <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span>
                <span class="r_btnArea fr">难度:<em>5</em>&nbsp;&nbsp;&nbsp;录入:admin</span>
            </div>
            <div class="answerArea hide">
                <p><em>答案:</em><span>1.答案</span><span>2.答案</span><span>3.答案</span></p>
                <p><em>解析:</em>解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容</p>
            </div>
        </div>
        <div class="testpaper"><!--选择题-->
            <span class="editBtn">移除</span>
            <h5>题目1:</h5>
            <h6>【2013年】 高考 选择题</h6>
            <p>题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分</p>
            <div class="checkArea"><input type="checkbox" class="checkbox"><label>A 备选项1</label><input type="checkbox" class="checkbox"><label>B 备选项2</label><input type="checkbox" class="checkbox"><label>C 备选项3</label><input type="checkbox" class="checkbox"><label>D 备选项4</label></div>
            <div class="btnArea clearfix">
                <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span>
                <span class="r_btnArea fr">难度:<em>5</em>&nbsp;&nbsp;&nbsp;录入:admin</span>
            </div>
            <div class="answerArea hide">
                <p><em>答案:</em><span>1.答案</span><span>2.答案</span><span>3.答案</span></p>
                <p><em>解析:</em>解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容</p>
            </div>
        </div>
        <div class="testpaper"><!--选择题-->
            <span class="editBtn">组题</span>
            <h5>题目1:</h5>
            <h6>【2013年】 高考 选择题</h6>
            <p>题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分</p>
            <div class="checkArea">
                <ul>
                    <li><i>1.</i><input type="checkbox" class="checkbox" checked><label>A 备选项1</label><input type="checkbox" class="checkbox"><label>B 备选项2</label><input type="checkbox" class="checkbox"><label>C 备选项3</label><input type="checkbox" class="checkbox"><label>D 备选项4</label>
                    </li>
                    <li><i>2.</i><input type="checkbox" class="checkbox"><label>A 备选项1</label><input type="checkbox" class="checkbox"><label>B 备选项2</label><input type="checkbox" class="checkbox"><label>C 备选项3</label><input type="checkbox" class="checkbox"><label>D 备选项4</label>
                    </li>
                    <li><i>3.</i><input type="checkbox" class="checkbox"><label>A 备选项1</label><input type="checkbox" class="checkbox"><label>B 备选项2</label><input type="checkbox" class="checkbox"><label>C 备选项3</label><input type="checkbox" class="checkbox"><label>D 备选项4</label>
                    </li>
                </ul>
            </div>
            <div class="btnArea clearfix">
                <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span>
                <span class="r_btnArea fr">难度:<em>5</em>&nbsp;&nbsp;&nbsp;录入:admin</span>
            </div>
            <div class="answerArea hide">
                <p><em>答案:</em><span>1.答案</span><span>2.答案</span><span>3.答案</span></p>
                <p><em>解析:</em>解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容</p>
            </div>
        </div>
    </div>

    <div class="page"><a class="jumpBtn">首页</a><a class="jumpBtn">上一页</a><a class="current">1</a><a>2</a><a>3</a><a>4</a><a>5</a><span>……</span><a>9</a><a class="jumpBtn">下一页</a><a class="jumpBtn">末页</a></div>
</div>

<p class="tc bottomBtnBar">
    <button type="button" class="preStepBtn">上一步</button> <button type="button" class="nextStepBtn">确定</button>
    <!--点击确定应该跳转到题目录入的列表页，美工说你们知道是那个页面，我就不写了-->
</p>
</div>
<!--主体内容结束-->

