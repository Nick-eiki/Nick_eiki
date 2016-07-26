<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-4
 * Time: 下午3:04
 */
/* @var $this yii\web\View */  $this->title="组织作业";
$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/js/ztree/jquery.ztree.all-3.5.min.js');
$this->registerCssFile($publicResources . '/js/ztree/zTreeStyle/zTreeStyle.css'.RESOURCES_VER);
?>
<div class="currentRight grid_16 push_2 make_testpaper">
    <div class="noticeH clearfix">
        <h3 class="h3L">布置作业</h3>
    </div>
    <hr>
    <h4>作业结构</h4>

    <form>
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label><i></i>所在地区：</label>
                </div>
                <div class="formR">
                    <select>
                        <option value="选择省">选择省</option>
                        <option value="江苏省">江苏省</option>
                        <option value="四川省">四川省</option>
                        <option value="浙江省">浙江省</option>
                        <option value="云南省">云南省</option>
                        <option value="黑龙江省">黑龙江省</option>
                    </select>
                    <select>
                        <option value="选择省">选择省</option>
                        <option value="江苏省">江苏省</option>
                        <option value="四川省">四川省</option>
                        <option value="浙江省">浙江省</option>
                        <option value="云南省">云南省</option>
                        <option value="黑龙江省">黑龙江省</option>
                    </select>
                    <select>
                        <option value="选择省">选择省</option>
                        <option value="江苏省">江苏省</option>
                        <option value="四川省">四川省</option>
                        <option value="浙江省">浙江省</option>
                        <option value="云南省">云南省</option>
                        <option value="黑龙江省">黑龙江省</option>
                    </select>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label>年级：</label>
                </div>
                <div class="formR">
                    <select>
                        <option value="001">一年级</option>
                        <option value="002中">二年级</option>
                        <option value="003">三年级</option>
                    </select>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label>科目：</label>
                </div>
                <div class="formR">
                    <select>
                        <option value="">语文</option>
                        <option value="">数学</option>
                        <option value="">英语</option>
                    </select>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label>版本：</label>
                </div>
                <div class="formR">
                    <select>
                        <option value="">人教版</option>
                        <option value="">苏教版</option>
                    </select>
                </div>
            </li>

            <li>
                <div class="formL">
                    <label><i></i>涉及的知识点：</label>
                </div>
                <div class="formR">
                    <div id="knowledge" class="treeParent">
                        <button id="addPointBtn" type="button" class="addPointBtn">+知识点</button>
                        <div class="pointArea hide">
                            <input class="hidVal" type="hidden" value="11,12,14">
                            <h6>已选中知识点:</h6>
                            <ul class="labelList clearfix">
                            </ul>
                        </div>
                    </div>
                </div>
            </li>

            <li>
                <div class="formL">
                    <label>试卷作者：</label>
                </div>
                <div class="formR">
                    <select>
                        <option value="">本校</option>
                        <option value="">教师</option>
                    </select>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label>试卷简介：</label>
                </div>
                <div class="formR">
                    <textarea></textarea>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label>试卷类型：</label>
                </div>
                <div class="formR">
                    <input type="radio" name="testCls">
                    <label> 标准</label>
                    &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="testCls">
                    <label> 小测验</label>
                    &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="testCls">
                    <label> 作业</label>
                    &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="testCls">
                    <label> 自定义</label>
                </div>
            </li>
            <li class="makePaper">
                <hr>
                <div class="testpaperSetup clearBoth">
                    <h6>卷头</h6>
                    <hr>
                    <ul id="testPaperHeadTree" class="clearfix ztree">
                    </ul>
                    <h6>卷体</h6>
                    <hr>
                    <ul id="testPaperBodyTree" class="clearfix ztree">
                    </ul>
                </div>
                <div class="testPaperDemo clearfix">
                    <div class="testPaperHead">


                    </div>
                    <div id="0" class="testPaperBody"></div>
                </div>
                <hr>
            </li>
        </ul>
    </form>
    <p class="tc bottomBtnBar"><a href="<?php echo url('teacher/managetask/screening-work') ?>"
                                  class="btn nextStepBtn mini_btn" style="display:inline-block;">下一步</a></p>
</div>
<!--知识树-->
<script>
    $(function () {
        var zNodes = [
            { id: 1, pId: 0, name: "语文"},
            { id: 11, pId: 1, name: "拼音"},
            { id: 111, pId: 11, name: "声母"},
            { id: 112, pId: 11, name: "韵母"},
            { id: 12, pId: 1, name: "标点符号"},
            { id: 13, pId: 1, name: "造句"},
            { id: 14, pId: 1, name: "语法"},
        ];
        popBox.pointTree2(zNodes, $('.addPointBtn'))

    })
</script>


<!--试卷结构-->

<script type="text/javascript">
var testPaperCont = {
    paperHead: [
        {"id": "line", "name": "\u88c5\u8ba2\u7ebf", "pId": "testPaperHead", "dataid": 0, "text": "", },
        {"id": "secret_sign", "pId": "testPaperHead", "name": "\u7edd\u5bc6\u2605\u542f\u7528\u524d", "dataid": 0, "text": "\u7edd\u5bc6\u2605\u542f\u7528\u524d", "checked": false},
        {"id": "main_title", "pId": "testPaperHead", "name": "\u4e3b\u6807\u9898", "text": "\u4e3b\u6807\u9898", "dataid": 0, "checked": true},
        {"id": "sub_title", "pId": "testPaperHead", "name": "\u526f\u6807\u9898", "text": "\u5185\u90e8\u6a21\u62df\u8003\u8bd5", "dataid": 0, "checked": true},
        {"id": "info", "pId": "testPaperHead", "name": "\u8303\u56f4\/\u65f6\u95f4", "text": "", "dataid": 0, "checked": true},
        {"id": "student_input", "pId": "testPaperHead", "name": "\u5b66\u751f\u8f93\u5165", "dataid": 0, "text": "\u5b66\u751f\u8f93\u5165", "checked": true},
        {"id": "pay_attention", "pId": "testPaperHead", "name": "\u6ce8\u610f\u4e8b\u9879", "dataid": 0, "text": "1.答题前填写好自己的姓名、班级、考号等信息<br>2.请将答案正确填写在答题卡上<br>3.请将答案正确填写在答卡上<br>4.请将答案正确填写在答题卡上", "checked": true}
    ],
    paperBody: [
        {"id": "win_paper_typeone", "pId": "testPaperBody", "name": "\u7b2c\u4e00\u5377(\u9009\u62e9\u9898)", "dataid": 10021, "text": "\u7b2c\u4e00\u5377(\u9009\u62e9\u9898)", "open": true, "checked": true},
        {"id": "win_paper_typetwo", "pId": "testPaperBody", "name": "\u7b2c\u4e8c\u5377(\u975e\u9009\u62e9\u9898)", "dataid": 10022, "text": "\u7b2c\u4e8c\u5377(\u975e\u9009\u62e9\u9898)", "open": true, "checked": true},


        {"id": "1", "pId": "win_paper_typeone", "name": "\u5355\u9009\u9898", "dataid": "1", "text": "注释内容", "checked": true},
        {"id": "2", "pId": "win_paper_typetwo", "name": "\u586b\u7a7a\u9898", "dataid": "2", "text": "注释内容", "checked": true},
        {"id": "3", "pId": "win_paper_typetwo", "name": "\u8ba1\u7b97\u9898", "dataid": "3", "text": "注释内容", "checked": true},
        {"id": "4", "pId": "win_paper_typeone", "name": "\u89e3\u7b54\u9898", "dataid": "4", "text": "注释内容", "checked": true},
        {"id": "5", "pId": "win_paper_typetwo", "name": "\u5224\u65ad\u9898", "dataid": "5", "text": "注释内容", "checked": true}
    ]
};

var num = ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十"];
var setting2 = {
    check: {
        enable: true,
        chkboxType: {"Y": "", "N": ""}
    },
    data: {
        simpleData: {
            enable: true
        }
    },
    callback: {
        onCheck: testPaperSetupCheck
    },
    view: {
        showIcon: false,
        showLine: false,
    }
};

//排序方法
function sequence() {
    var sp_num = $('.testPaperBody .subPart:visible').size();
    $('.subPart:visible').each(function (sp_num) {
        $(this).children('h6').children('i').text(num[sp_num] + '.')
    });
}

function editZnodes(id, obj, val) {
    var zHead = testPaperCont.paperHead;
    var zBody = testPaperCont.paperBody;
    for (var i = 0; i < zHead.length; i++) {
        if (zHead[i].id == id) zHead[i][obj] = val;
    }
    for (var i = 0; i < zBody.length; i++) {
        if (zBody[i].id == id) zBody[i][obj] = val;
    }
}

//树的check行为
function testPaperSetupCheck(event, treeId, treeNode) {
    if (treeNode.checked == false) {
        $('#' + treeNode.id).hide();
        editZnodes(treeNode.id, "checked", false);
        sequence();
    }
    else {
        $('#' + treeNode.id).show();
        editZnodes(treeNode.id, "checked", true);
        sequence()
    }
}


function fixTxt(txt) {
    var new1 = txt.replace(/\s/g, "")//清除所有空格
    var new2 = new1.replace(/<br>/g, "\n")//用回车替换<br>
    return new2
};;
function addBr(txt) {
    var new1 = txt.replace(/\n|\r/g, "<br>");
    return new1
}


$(function () {

//试卷结构树
    $.fn.zTree.init($("#testPaperHeadTree"), setting2, testPaperCont.paperHead);
    $.fn.zTree.init($("#testPaperBodyTree"), setting2, testPaperCont.paperBody);


//试卷头部
    var headIndex = testPaperCont.paperHead.length;
    $('.testPaperBody').attr("id", "testPaperHead");
    for (var i = 0; i < headIndex; i++) {
        var id = testPaperCont.paperHead[i].id;
        if (id == "line") {//插入装订线
            $('#testPaperHead').append('<div id="line"  class="paper_bindLine"><span class="hide">装订线</span></div>');
        }
        if (id == "secret_sign" || id == "main_title" || id == "sub_title" || id == "info" || id == "student_input") {
            var headPart = '<div id="' + testPaperCont.paperHead[i].id + '" class="setup"><div class="setupBar hide"><span class="setupBtn">编辑</span></div><p>' + testPaperCont.paperHead[i].name + '</p><div class="editBar  hide"><input type="text" class="text txt"><input class="btn okBtn" type="button" value="确定"><input class="btn cancelBtn" type="button" value="取消"></div></div>';
            if (testPaperCont.paperHead[i].pId == "testPaperHead") {
                $('#testPaperHead').append(headPart)
            }
        }
        if (id == "pay_attention") {//插入表格和注意事项
            $('#testPaperHead').append('<div class="part7"><table class="topTotalTable"><thead><tr><th>题号</th><th>一</th><th>二</th><th>三</th><th>四</th><th>五</th><th>六</th><th>七</th><th>八</th><th>九</th><th>总分</th></tr><tr><td>得分</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></thead></table></div><div id="pay_attention" class="setup"><div class="setupBar hide"><span class="setupBtn">编辑</span></div><h6>' + testPaperCont.paperHead[i].name + ':</h6><p>' + testPaperCont.paperHead[i].text + '</p><div class="editBar hide "><textarea class="txt"></textarea><br><input class="btn okBtn" type="button" value="确定"><input class="btn cancelBtn" type="button" value="取消"></div>');
        }

        if (testPaperCont.paperHead[i].checked != true) {
            $('#testPaperHead').children('#' + testPaperCont.paperHead[i].id).hide();
        }
    }


//试卷插入项目
    var subject = testPaperCont.paperBody.length;
    $('.testPaperBody').attr("id", "testPaperBody");
    for (var i = 0; i < subject; i++) {
        var paperPart = '<div id="' + testPaperCont.paperBody[i].id + '" class="paperPart"><div class="testPaperTitle setup"><p>' + testPaperCont.paperBody[i].name + '</p><div class="editBar  hide"><input type="text" class="text txt"> <input class="btn okBtn" type="button" value="确定"> <input class="btn cancelBtn" type="button" value="取消"></div><span class="setupBar hide"><span class="setupBtn">编辑</span></span></div><div class="testPaperCom setup"><h6>说明:</h6><p>' + testPaperCont.paperBody[i].text + '</p><div class="editBar  hide"><input type="text" class="text txt"> <input class="btn okBtn" type="button" value="确定"> <input class="btn cancelBtn" type="button" value="取消"></div><span class="setupBar hide"><span class="setupBtn">编辑"注释"</span></span></div><div class="editBar  hide"><input type="text" class="text txt"> <input class="btn okBtn" type="button" value="确定"> <input class="btn cancelBtn" type="button" value="取消"></div><span class="setupBar hide"><span class="setupBtn">编辑"注释"</span></span></div>';
        var subPart = '<div id="' + testPaperCont.paperBody[i].id + '" class="subPart setup"><table><thead><tr><th>评卷人</th><th>得分</th></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr></thead></table><h6><i>一.</i><em>' + testPaperCont.paperBody[i].name + '</em></h6><p>(' + testPaperCont.paperBody[i].text + ')</p><div class="editBar  hide"><input type="text" class="text txt"> <input class="btn okBtn" type="button" value="确定"> <input class="btn cancelBtn" type="button" value="取消"></div><span class="setupBar hide"><span class="setupBtn">编辑"注释"</span><span class="upBtn">上移</span><span class="downBtn">下移</span></span></div>';

        if (testPaperCont.paperBody[i].pId == "testPaperBody") {
            $('#testPaperBody').append(paperPart)
        }
        if (testPaperCont.paperBody[i].pId == "win_paper_typeone") {
            $('#win_paper_typeone').append(subPart)
        }
        if (testPaperCont.paperBody[i].pId == "win_paper_typetwo") {
            $('#win_paper_typetwo').append(subPart)
        }
        if (testPaperCont.paperBody[i].checked != true) {
            $('#testPaperBody').children('#' + testPaperCont.paperBody[i].id).hide();
        }
    }

//插入dom后排序,必须放在这里!!!!!
    sequence();


//下移
    $('.setup .downBtn').live('click', function () {
        var arr = testPaperCont.paperBody;
        var next = $(this).parents('.subPart').next('.subPart');
        $(next).after($(this).parents('.subPart'));
        sequence()


    });
//上移
    $('.setup .upBtn').live('click', function () {
        var arr = testPaperCont.paperBody;
        var prev = $(this).parents('.subPart').prev('.subPart');
        $(prev).before($(this).parents('.subPart'));
        sequence()

    });


//显示编辑按钮
    $('.testPaperDemo .setup').hover(
        function () {
            $(this).addClass('hover');
            if ($(this).attr("edit") != "on") {
                $(this).children('.setupBar').show();
            }
        },
        function () {
            $(this).removeClass('hover').children('.setupBar').hide();
        }
    );

    $('.setupBtn').click(function () {
        $(this).parent('.setupBar').hide();
        $(this).parents('.setup').attr("edit", "on");
        var oldText = $(this).parent().siblings('p').html();
        $(this).parent().siblings('.editBar').show().children('.txt').val(fixTxt(oldText));
        $(this).parent().siblings('p').hide();
    });


//编辑
    $('.testPaperDemo .setup').find('.okBtn').click(function () {
        var _this = $(this).parents('.setup');
        var oldText = $(_this).children('p').text();
        var newText = $(_this).find('.txt').val();
        if (newText == "") {
            newText = oldText
        }
        $(_this).children('p').html(addBr(newText)).show();
        $(_this).children('.editBar').hide();
        $(_this).attr("edit", "no");
        $(_this).children('.setupBar').show();

        //修改"树"数据
        var id = $(_this).attr("id");
        editZnodes(id, "text", newText);
    });

    $('.testPaperDemo .testPaperTitle').find('.okBtn').click(function () {
        var _this = $(this).parents('.testPaperTitle');
        var id = $(this).parents('.paperPart').attr("id");
        var oldText = $(_this).children('p').text();
        var newText = $(_this).find('.txt').val();

        if (newText == "") {
            newText = oldText
        }
        for (var i = 0; i < headIndex; i++) {
            if (testPaperCont.paperHead[i].id == id) {
                testPaperCont.paperHead[i].name = newText;
            }
            if (testPaperCont.paperBody[i].id == id) {
                testPaperCont.paperBody[i].name = newText;
            }
        }
    });

    $('.testPaperDemo .testPaperCom').find('.okBtn').click(function () {
        var _this = $(this).parents('.testPaperCom');
        var id = $(this).parents('.paperPart').attr("id");
        var oldText = $(_this).children('p').text();
        var newText = $(_this).find('.txt').val();

        if (newText == "") {
            newText = oldText
        }
        for (var i = 0; i < headIndex; i++) {
            if (testPaperCont.paperHead[i].id == id) {
                testPaperCont.paperHead[i].text = newText;
            }
            if (testPaperCont.paperBody[i].id == id) {
                testPaperCont.paperBody[i].text = newText;
                alert(testPaperCont.paperBody[i].text)
            }

        }

    });

    $('.testPaperDemo .setup').find('.cancelBtn').click(function () {
        var _this = $(this).parents('.setup');
        var oldText = $(_this).children('p').html();
        $(_this).children('p').html(oldText).show();
        $(_this).children('.editBar').hide();
        $(_this).attr("edit", "no");
        $(_this).children('.setupBar').show();
    })


})


</script>