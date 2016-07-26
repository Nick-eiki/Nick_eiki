<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-1
 * Time: 下午2:06
 */
use frontend\components\helper\AreaHelper;

/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="学生-收藏详情";
?>
<script>
    $(function(){
        $('.personal').bind('mouseover',function(){
            $(this).children('.tab').show();

        });
        $('.personal').bind('mouseout',function(){
            $(this).children('.tab').hide();

        })


    })
</script>
<script>
    $(function(){

        $('.Proofreader').click(function(){
            $('#proofreader').dialog({
                autoOpen: false,
                width:500,
                modal: true,
                resizable:false,
                buttons: [
                    {
                        text: "确定",

                        click: function() {
                            if($('#mySchoolPop .text').val()==1){
                                $( this ).dialog( "close" );
                            }
                            else{
                                alert('请正确填写信息');
                            }

                        }
                    },
                    {
                        text: "取消",

                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    }

                ]
            });
            $( "#proofreader" ).dialog( "open" );
            //event.preventDefault();
            return false;

        });
        $('.updown').click(function(){
            $('#uploadTestpaper').dialog({
                autoOpen: false,
                width:500,
                modal: true,
                resizable:false,
                buttons: [
                    {
                        text: "确定",

                        click: function() {
                            if($('#mySchoolPop .text').val()==1){
                                $( this ).dialog( "close" );
                            }
                            else{
                                alert('请正确填写信息');
                            }

                        }
                    },
                    {
                        text: "取消",

                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    }

                ]
            });
            $( "#uploadTestpaper" ).dialog( "open" );
            //event.preventDefault();
            return false;

        });
//添加班级荣誉
        $('.add_honor').click(function(){
            $('#honour').dialog({
                autoOpen: false,
                width:500,
                modal: true,
                resizable:false,
                buttons: [
                    {
                        text: "保存",
                        class:"okBtn",
                        click: function() {
                            if($('#mySchoolPop .text').val()==1){
                                $( this ).dialog( "close" );
                            }
                            else{
                                var oTxt=$('.text_t').val();
                                var oText=$('.text_tt').val();
                                if(oTxt=='')
                                {
                                    alert('请正确填写信息');
                                }
                                else
                                {
                                    $('.class_Compile').append('<li><span>'+ oText +'</span>&nbsp;&nbsp;<span>'+ oTxt +'</span><i></i> </li>');


                                }
                                $( this ).dialog( "close" );
                                oTxt=$('.text_t').val('');
                                oText=$('.text_tt').val('');
                            }

                        }
                    },
                    {
                        text: "取消",
                        class:"cancelBtn",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    }

                ]
            });
            $( "#honour" ).dialog( "open" );
            //event.preventDefault();
            return false;

        });



//它的荣誉弹窗编辑,删除
//编辑
        $('.edit').live('click',function(){
            $(this).parents('.m').hide();
            var strong= $(this).siblings('.tt').text();
            var b_text=$(this).parents('.m').siblings('.b').children('.text_js').val(strong);
            $(this).parents('.m').siblings('.b').show('');

        });
//删除
        $('.del').live('click',function(){
            $(this).parents('.m').remove();
        });
//编辑的文字
//$('.add2').css('display','block');
        /*$('.text_tt').blur(function(){
         $('.add2').show();
         })

         $('.text_tt').focus(function(){
         $('.add2').hide();
         })*/



//添加荣誉
        $('.add_btn_js').bind('click',function(){
            var text_val= $('.text_js').val();
            var html='';
            html+='<li>';
            html+='<div class="m">';
            html+='<strong class="tt">'+ text_val +'</strong>';
            html+='<a href="javascript:;" class="edit">编辑</a><a href="javascript:;" class="del">删除</a>';
            html+='</div>';
            html+='<div class="b" style="display:none;">';
            html+='<input type="text" class="text text_js">';
            html+='<a href="javascript:;" class="a_button bg_red ok">确定</a><a href="javascript:;" class="a_button bg_gray no">取消</a>';
            html+='</div>';
            html+='</li>';
            if(text_val=='')
            {
                alert('此处不能为空')
            }
            else
            {
                $('.imp_list').append(html);
                $('.text_js').val('');
            }

        });


        $('.ok').live('click',function(){
            var text_t=$(this).siblings('.text_js').val();
            alert(text_t);
            //$(this).parents('.b').hide();
            //$('.tt').text(text_t);
        });
        $('.no').live('click',function(){
            $(this).parents('.b').hide();
        })

    })






</script>
<script type="text/javascript">
    $(function(){
        $('#downNum').click(function(){
            var id= '<?php echo $model->id;?>';
            $.post("<?php echo url('student/default/get-down-num')?>",{id:id},function(data){
                if(data.success){
                    $("#downNum i").html(data.data);
                }else{
                    popBox.alertBox(data.message);
                }
            })
        })
    })
</script>

<!--顶部开始-->

<!--顶部结束-->
<!--主体内容开始-->

    <div class="centLeft collect_l">
        <h3>讲义名称</h3>
        <hr>
        <div class="wd_details">
            <h4><?php echo $model->name;?></h4>
            <ul class="wd_keywords_list clearfix">
                <li>
                    <p><?php echo $model->subjectname;?></p>
                </li>
                <li>
                    <p><?php echo $model->gradename;?></p>
                </li>
                <li>
                    <p><?php echo $model->versionname;?></p>
                </li>
                <li class="wd_source">
                    <p class="sou_btn"><a style="color: #0000ff;" href="<?php echo url('school/index',array('schoolId'=>$model->school));?>"><?php echo $model->schoolName;?></a></p>
                </li>
            </ul>
            <ul class="wd_introduce_list ">
                <li><em>适用于:</em><?php echo AreaHelper::getAreaName($model->provience);?> &nbsp;<?php echo AreaHelper::getAreaName($model->city);?>&nbsp;<?php echo AreaHelper::getAreaName($model->country);?></li>
                <li>      <?php if($model->contentType==2){?>
                        <em>章节讲解：</em>
                        <?php
                        if(isset($model->chapKids)){
                            foreach(ChapterInfoModel::findChapter($model->chapKids) as $key=>$item){
                                echo $item->name;
                            } } }else{?>
                        <em>知识点讲解：</em>
                        <?php
                        if(isset($model->chapKids)){
                            foreach(KnowledgePointModel::findKnowledge($model->chapKids) as $key=>$item){
                                echo $item->name;
                            }  }  } ?>
                </li>
                <li><em>视频介绍：</em><?php echo $model->matDescribe;?></li>
            </ul>
            <button class="bg_green dataBtn" type="button" id="downNum">下载教案 (<i class="red"><?php echo $model->downNum;?></i></button>


        </div>

    </div>
    <div class="centRight">
        <div class="item Ta_teacher">
            <h4>Ta的老师</h4>
            <a class="more" href="#">更多</a>
            <ul class="teacherList">
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
            </ul>


        </div>
    </div>

<!--主体内容结束-->



<!--弹出框pop--------------------->
<!--班级荣誉-->
<div id="honour" class="add_honour popoBox hide " title="班级荣誉">
    <div class="impBox">
        <form>
            <div class="honourT clearfix">
                <label>荣誉：</label>
                <div class="our">
                    <!--<i class="add2">请添加新的荣誉</i>-->
                    <input type="text" class="text text_tt text_js">

                    <span class="a_button bg_green add_btn_js">添加</span>
                </div>
            </div>
            <ul class="imp_list">
                <li>
                    <div class="m">
                        <strong class="tt">我班在学籍运动会上跳绳比赛中勇获第一名</strong>
                        <a href="javascript:;" class="edit">编辑</a><a href="javascript:" class="del">删除</a>
                    </div>
                    <div class="b" style="display:none;">
                        <input type="text" class="text text_js">
                        <a href="javascript:;" class="a_button bg_red ok">确定</a><a href="javascript:;" class="a_button bg_gray no">取消</a>
                    </div>
                </li>
            </ul>
        </form>
    </div>
</div>
