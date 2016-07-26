<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-17
 * Time: 下午3:52
 */

use frontend\components\CHtmlExt;
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */  $this->title="教学库";
?>

    <script>
        $(function(){
            $('#searchJS').click(function(){
                $('#database_List').toggleClass("hide");
                $('#arr_icon').toggleClass("close");
            });
            //搜索按钮切换
            $('.terrace_btn_js span').bind('click',function(){
                $(this).addClass('s_btn').siblings('span').removeClass('s_btn');

            });
            $('.searchBtn').click(function(){
                var name =$('.fr .text').val();
               if(name ==""){
                   popBox.alertBox('资料名称不能为空，请填写名称.');
               }
                $.post("<?php echo app()->request->url;?>",{name:name},function(data){
                    $('#material').html(data);
                });
            });

           $('.B_btn110').bind('click',function(){
                var type =$('#type').val();
               var provience=$('#provience').val();
               var city =$('#city').val();
               var country =$('#county').val();
               var grade =$('#grade').val();
               var subject =$('#subject').val();
               var name=$('#name').val();
               $.post('<?php echo app()->request->url;?>',{type:type,provience:provience,city:city,country:country,grade:grade,subject:subject,name:name},function(data){
                   $('#material').html(data);
               })
            });
            function clearForm()
            {
                var formObj = document.getElementById('database_List');
                if(formObj == undefined)
                {
                    return;
                }

                for(var i=0; i<formObj.elements.length; i++)
                {
                    if(formObj.elements[i].type == "text")
                    {
                        formObj.elements[i].value = "";
                    }
                    else if(formObj.elements[i].type == "password")
                    {
                        formObj.elements[i].value = "";
                    }
                    else if(formObj.elements[i].type == "radio")
                    {
                        formObj.elements[i].checked = false;
                    }
                    else if(formObj.elements[i].type == "checkbox")
                    {
                        formObj.elements[i].checked = false;
                    }
                    else if(formObj.elements[i].type == "select-one")
                    {
                        formObj.elements[i].options[0].selected = true;
                    }
                    else if(formObj.elements[i].type == "select-multiple")
                    {
                        for(var j = 0; j < formObj.elements[i].options.length; j++)
                        {
                            formObj.elements[i].options[j].selected = false;
                        }
                    }
                    else if(formObj.elements[i].type == "file")
                    {
                        var file = formObj.elements[i];
                        if (file.outerHTML) {
                            file.outerHTML = file.outerHTML;
                        } else {
                            file.value = "";  // FF(包括3.5)
                        }
                    }
                    else if(formObj.elements[i].type == "textarea")
                    {
                        formObj.elements[i].value = "";
                    }
                }

            }
            $('.clear_btn').click(function(){
                clearForm()
            })
        })
    </script>


<!--主体内容开始-->
<div class="replace">
<div class="crumbs grid_24">
    <a href="#">首页</a>&gt;&gt;<a href="#">教学库</a>
</div>
<div class="class_c grid_24 clearfix tch">
    <div class="currentLeft grid_17 database_div">
        <div class="noticeH clearfix database_top">
            <h3 class="h3L">教学库</h3>
            <div class="new_not fr">
                <input type="text" class="text">
                <button type="button" class="searchBtn bg_red_d">搜索</button>
                &nbsp;&nbsp;<a id="searchJS">高级搜索<i class="open" id="arr_icon"></i></a> </div>
        </div>
        <hr>
        <div class="database_main">
            <form id="database_List" class="hide">
                <ul class="form_list database_List" >
                    <li class="short_list">
                        <div class="formL">
                            <label>资料类型：</label>
                        </div>
                        <div class="formR">
                            <select class="mySel" id="type">
                                <option value="0">请选择</option>
                                <option value="1">教案</option>
                                <option value="2">讲义</option>
                            </select>

                        </div>
                    </li>
                    <li>
                        <div class="formL">
                            <label for="name">区域：</label>
                        </div>
                        <div class="formR">
                            <?php
                            echo CHtmlExt::dropDownListAjax( "provience", '', ArrayHelper::map(AreaHelper::getProvinceList(), 'AreaID', 'AreaName'),
                                [
                                    "defaultValue" => false, "prompt" => "请选择",
                                    'ajax' => [
                                        'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                                        'data' => ['id' => new \yii\web\JsExpression('this.value')],
                                        'success' => 'function(html){jQuery("#' . "city" . '").html(html).change();}'
                                    ],
                                    "id" =>"provience",

                                ]);
                            ?>
                            <?php
                            echo CHtmlExt::dropDownListAjax( "city", '', ArrayHelper::map(AreaHelper::getCityList('provience'), 'AreaID', 'AreaName'), array(
                                "defaultValue" => false, "prompt" => "请选择", "id" => "city",
                                'ajax' => array(
                                    'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                                    'data' => array('id' => new \yii\web\JsExpression('this.value')),
                                    'success' => 'function(html){jQuery("#' . "county" . '").html(html).change();}'
                                ),
                            ));
                            ?>
                            <?php
                            echo CHtmlExt::dropDownListAjax("county", '', ArrayHelper::map(AreaHelper::getRegionList('city'), 'AreaID', 'AreaName'),
                                array(
                                    'data-validation-engine' => 'validate[required]', "defaultValue" => false, "prompt" => "请选择", "id" =>"county",
                                    'data-prompt-target' => "county_prompt",
                                    'data-prompt-position' => "inline",
                                    'data-errormessage-value-missing' => "所在地不能为空",
                                ));?>
                            <span id="county_prompt"></span>
                        </div>
                    </li>
                    <li class="short_list">
                        <div class="formL">
                            <label>年级：</label>
                        </div>
                        <div class="formR">
                            <?php
                            echo CHtmlExt::dropDownListAjax('grade',
                                '',
                                ArrayHelper::map(GradeModel::model()->getList(), 'gradeId', 'gradeName'),
                                ['prompt'=>'请选择','id'=>'grade']);
                            ?>
                        </div>
                    </li>
                    <li>
                        <div class="formL">
                            <label>科目：</label>
                        </div>
                        <div class="formR">
                            <?php
                            echo CHtmlExt::dropDownListAjax('subject',
                                '',
                                SubjectModel::model()->getList(),
                                array("prompt" => "请选择",'id'=>'subject'));
                            ?>
                        </div>
                    </li>
                    <li class="long_list">
                        <div class="formL">
                            <label>资料名称：</label>
                        </div>
                        <div class="formR">
                            <input type="text" class="text" id="name">
                        </div>
                    </li>
                </ul>
                <p class="conserve clearBoth">
                    <button type="button" class="B_btn110">搜索</button><span class="conserveSearch">找到符合条件的结果约<em> 0 </em>条</span><a class="clear_btn">清空搜索条件</a>
                </p>
            </form>
            <div id="material">
           <?php echo $this->render('_list_material',array('model'=>$model,'pages'=>$pages))?>
            </div>
        </div>

    </div>
    <div class="centRight">
        <div class="centRightT">
            <a href="classHandsin.html" class=" outAdd_btn B_btn120">设置手拉手班级</a> </div>
        <div class="centRightT clearfix">
            <p class="title titleLeft"> <span>手拉手班级</span><i></i> </p>
            <hr>
            <dl class="list_dl clearfix">
                <dt><img src="../images/pic.png" alt="" width="90" height="90"></dt>
                <dd>
                    <h3>177班</h3>
                </dd>
                <dd><span>学校：</span>北京人大附中</dd>

                <dd><span>成员：</span>30名学生</dd>
            </dl>
        </div>
        <div class="centRightT">

            <ul class="class_list clearfix">
                <li><a href="#"><img src="../images/user_s.jpg" alt="" title="北京"></a></li>
                <li><a href="#"><img src="../images/user_s.jpg" alt="" title="北京"></a></li>
                <li><a href="#"><img src="../images/user_s.jpg" alt="" title="北京"></a></li>
            </ul>
        </div>
        <div class="centRightT">
            <h3 class="clearfix">推荐视频</h3>
            <hr>
            <h4>资料名称资料名称资料名称资料名称......</h4>
            <dl class="y_list">
                <dt><a href="#"><img src="../images/teacher_m.jpg"></a></dt>
                <dd>
                    <span>简介：</span>简介简介简介简介简介简介简介简介简介简介简介简介简介简介简介
                </dd>

            </dl>
            <ul class="info_list">
                <li><a href="#">资料名称资料名称料名称资料名称料名称资料名称资料名称资料名称</a></li>
                <li><a href="#">资料名称资料名称料名称资料名称料名称资料名称资料名称资料名称</a></li>
                <li><a href="#">资料名称资料名称料名称资料名称料名称资料名称资料名称资料名称</a></li>
                <li><a href="#">资料名称资料名称料名称资料名称料名称资料名称资料名称资料名称</a></li>
            </ul>
        </div>
    </div>
</div>
</div>

<!--主体内容结束-->
