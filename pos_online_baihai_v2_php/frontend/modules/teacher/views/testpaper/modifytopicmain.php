<?php
/**
 * Created by PhpStorm.
 * User: liquan
 * Date: 2015/2/2
 * Time: 15:36
 */
use frontend\components\helper\StringHelper;

/* @var $this yii\web\View */  $this->title='课程管理-题目管理';
$backend_asset = publicResources_new();
;
$this->registerJsFile($backend_asset . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/pubjs.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/ztree/jquery.ztree.all-3.5.min.js');

$this->registerCssFile($backend_asset . '/css/questionInput.css'.RESOURCES_VER);
$this->registerCssFile( '@web/ueditor/themes/default/css/ueditor.min.css'.RESOURCES_VER);
$this->registerJsFile( '@web/ueditor/ueditor.config.little.js'.RESOURCES_VER);
$this->registerJsFile( '@web/ueditor/ueditor.all.min.js');

//$answerOption=!empty($info->answerOptionJson)?$info->answerOptionJson:$info->answerOption;
?>

<!--知识树-->
<script>
/*
 QBox:大题题目
 Box:(容器)
 SBox:多选项
 ABox:答案
 IBox:小题
 I_(0,1,2,……):小题列表
 I_QBox:小题题目
 I_SBox:小题多选项
 I_ABox:小题答案
 I_IBox:小题的小题
 I_I_(0,1,2,……)

 btnBar:添加 备选项/小题 按钮
 NBox:解析

 */


var preview ={
    type:"<?php echo $info->tqtid ?>",//大题的题型
    subItem_id:0, //小题id
    selNum:100,//备选项序列(ueditor实例化)
    fillNum:200,//填空题序列(ueditor实例化)
    showType:"<?php echo $info->showTypeId?>",
    //大题的显示类型  1单选题 2多选题 3填空题 4问答题 5应用题 6完型填空7阅读理解
    subTypes:'<?php echo $childType ?>',//小题的类型

    id:"<?php echo $info->id ?>",
    content:'<?php echo $info->content?>',//Q:题目内容
    textContent:'',//题目内容文本
    answerContent:'<?php echo $info->answerContent ?>',//A:答案
    answerOptionJson:<?php echo $info->answerOption?>,
    analytical:'<?php echo $info->analytical ?>',//解析
    childQuesJson:<?php echo json_encode($info->childQues)?>,

    //大题题型为填空题
    /*childQuesJson:[
     {
     "quesType":"3",//小题的类型
     "answerContent":"小题答案",//答案内容
     },
     {
     "quesType":"3",//小题的类型
     "answerContent":"小题答案",//答案内容
     }
     ],*/
    //typeName:['单选','多选','填空','问答','应用','完形','阅读理解'],

//----------------------------------------------------------------------------

    //生成备选项html
    createSelHtml:function(OptionJson,showType){
        var showType=showType || this.showType;
        var sel=OptionJson;
        var html='';//备选项内容
        var html2='';//备选项答案
        var ue="";
        if(sel.length>0){
            for(var i=0, len=sel.length; i<len; i++){
                this.selNum++;
                html+='<div class="row">';
                html+='<div class="formL"><label>备选项<em>'+(i+1)+'</em></label></div>';
                html+='<div class="formR">';
                html+='<textarea id="sel_'+this.selNum+'" class="ue_textarea">'+sel[i].content+'</textarea>';
                html+='<span class="del_btn delSelBtn">删除</span>';
                html+='</div>';
                html+='</div>';

                if(showType==30001) {showType=1;}

                html2+='<span><input name="single" '+ (sel[i].right==1 ? 'checked' : '')+' value="'+i+'" type="'+ (showType==1 ? "radio" : "checkbox")+'" data-validation-engine= "'+(showType==30001?'validate[required] radio':'validate[minCheckbox[1]] checkbox')+'" data-errormessage-value-missing="答案不能为空"/> <label>备选项<em>'+(i+1)+'</em></label></span>';

            }
        }
        else{//没有小题数据  李泉代码
            html+='<div class="row">';
            html+='<div class="formL"><label>备选项<em>1</em></label></div>';
            html+='<div class="formR"><textarea id="sel_'+this.selNum+'" class="ue_textarea"></textarea></div>';
            html+='</div>';

            html2+='<span><input value="0" name="single" type="'+ (this.showType==1 ? "radio" : "checkbox")+'" data-validation-engine= "'+(showType==30001?'validate[required] radio':'validate[minCheckbox[1]] checkbox')+'" data-errormessage-value-missing="答案不能为空" /> <label>备选项<em>1</em></label></span>';
            var ue=UE.getEditor('sel_'+this.selNum);
        }
        var selHtml=[html,html2];//备选项数组:[0]备选项  [1]备选项答案
        return selHtml;



        /*else{//没有小题数据
         html+='<div class="row">';
         html+='<div class="formL"><label>备选项<em>1</em></label></div>';
         html+='<div class="formR"><textarea></textarea></div>';
         html+='</div>';
         html2+='<span><input name="single" type="'+ (this.showType==1 ? "radio" : "checkbox")+'"> <label>备选项<em>1</em></label></span>';
         }
         var selHtml=[html,html2];//备选项数组:[0]备选项  [1]备选项答案
         return selHtml;*/
    },

    //加载题目
    onloadType:function(){
        //判断大题的题型
        var showType=this.showType;
        if(showType==1 || showType==2){
            var html2='';
            var selHtml=this.createSelHtml(this.answerOptionJson);
            html2+='<div class="SBox">'+selHtml[0]+'</div>';
            html2+='<div class="ABox">';
            html2+='<div class="row">';
            html2+='<div class="formL">答案:</div>';
            html2+='<div class="formR">'+selHtml[1]+'</div>';
            html2+='</div>';
            html2+=' </div>';
            html2+='<div class="btnBar">';
            html2+='<div class="row">';
            html2+='<div class="formL"></div>';
            html2+='<div class="formR">';
            html2+='<button type="button" class="addSelBtn">添加备选项</button>';
            html2+='</div>';
            html2+='</div>';
            html2+='</div>';
            var html3="";
            var allhtml=html2+html3;
            $('.Box').html(allhtml);

            //富文本
            var textareas=$('.Box .ue_textarea');
            textareas.each(function(){
                var id=$(this).attr('id');
                UE.getEditor(id)
            })


        }
        else if(showType==3){
            var html='';
            html+='<div class="ABox">';
            html+='<div class="row">';
            html+='<div class="formL">答案:</div>';
            html+='<div class="formR"><textarea id="answer" class="ue_textarea">'+this.answerContent+'</textarea></div>';
            html+='</div>';
            html+='</div>';
            html+='<div class="IBox"></div>';//预留给插入填空小题
            $('.Box').append(html);



            if(this.childQuesJson.length>0){
                $('.ABox').hide();
                for(var i=0; i<this.childQuesJson.length; i++){
                    this.addItem(this.childQuesJson[i],i)
                }
            }
            else{
                var ue = UE.getEditor('answer');//初始化富文本
            }

            var html2="";
            html2+='<div class="btnBar">';
            html2+='<div class="row">';
            html2+='<div class="formL"><label></label></div>';
            html2+='<div class="formR">';
            html2+='<button type="button" class="addBtn bg_gray_d addType3Btn">添加填空小题</button>';
            html2+='</div>';
            html2+='</div>';
            html2+='</div>';
            $('.Box').append(html2);

        }
        else if(showType==4){
            var html='<div class="ABox">';
            html+='<div class="row">';
            html+='<div class="formL">答案:</div>';
            html+='<div class="formR">';
            /*html+='<textarea id="answer" class="ue_textarea">'+this.answerContent+'</textarea>';*/
            html+='<textarea id="answer" class="ue_textarea"></textarea>';
            html+='</div>';
            html+='</div>';
            html+='</div>';
            $('.Box').html(html);

            var ue=UE.getEditor('answer');//初始化富文本

            ue.ready(function(){
                if(!ue.hasContents()){

                    ue.setContent(preview.answerContent);
                }
            })
        }
        else{
            $('.Box').html('<div class="IBox"></div>');

            if(this.childQuesJson.length>0){
                for(var i=0; i<this.childQuesJson.length; i++){
                    this.addItem(this.childQuesJson[i],i);//对象传入

                    //对应显示题型
                    switch(this.childQuesJson[i].quesType){
                        case "30001":
                            $('.subItem:eq('+i+') select option[showtypeid="1"]').attr('selected',true);
                            break;
                        case "30005":
                            $('.subItem:eq('+i+') select option[showtypeid="2"]').attr('selected',true);
                            break;
                        case "30017":
                            $('.subItem:eq('+i+') select option[showtypeid="3"]').attr('selected',true);
                            break;
                        case "30031":
                            $('.subItem:eq('+i+') select option[showtypeid="4"]').attr('selected',true);
                            break;
                    }
                }
            }
            else{ this.addSub() }
            $('.Box').append('<div class="btnBar"><button type="button" class="addSubBtn">添加小题</button></div>');
            //富文本
            var textareas=$('.Box .IBox .I_SBox textarea');
            textareas.each(function(){
                var id=$(this).attr('id');
                UE.getEditor(id)
            });

            var contents=$('.Box .I_QBox textarea');
            contents.each(function(){
                var id=$(this).attr('id');
                UE.getEditor(id)
            });
            var contents=$('.Box .I_ABox textarea');
            contents.each(function(){
                var id=$(this).attr('id');
                UE.getEditor(id)
            });
        }

    },

    //改变小题题型
    changeType:function(sel){
        this.subItem_id++;
        this.selNum++;
        this.fillNum++;
        var pa=sel.parents('.I_type').next('.I_Box');
        var type=sel.find('option:selected').attr('showTypeId');
        var i=sel.parents('.subItem').index();
        preview.del_UE_obj(pa);
        pa.empty();


        if(type==1 || type==2){//选择题
            var html='';
            var html3='';
            html3+='<div class="I_QBox">';
            html3+='<div class="row">';
            html3+='<div class="formL">题目:</div>';
            html3+='<div class="formR"><textarea id="sel_Q'+this.subItem_id+'"  class="ue_textarea"></textarea></div>';
            html3+='</div>';
            html3+='</div>';

            html+=(this.showType==6 ? '': html3);//大题 题型为6时,选择题没有题目

            html+='<div class="I_SBox">';
            html+='<div class="row">';
            html+='<div class="formL"><label>备选项<em>1</em></label></div>';
            html+='<div class="formR"><textarea id="sel_'+this.subItem_id+'" class="ue_textarea"></textarea></div>';
            html+='</div>';
            html+='</div>';
            html+='<div class="I_ABox">';
            html+='<div class="row">';
            html+='<div class="formL">答案:</div>';
            html+='<div class="formR"><span><input value="'+i+'" name="beixuan'+i+'" type="'+(type==1 ? 'radio':'checkbox')+'" data-validation-engine= "'+(type==1?'validate[required] radio':'validate[minCheckbox[1]] checkbox')+'" data-errormessage-value-missing="答案不能为空"/> <label>备选项<em>1</em></label></span></div>';
            html+='</div>';
            html+='</div>';
            html+='<div class="btnBar">';
            html+='<div class="row">';
            html+='<div class="formL"></div>';
            html+='<div class="formR"><button class="addSelBtn" type="button">添加备选项</button></div>';
            html+='</div>';
            html+='</div>';
            pa.html(html);

            if(this.showType!=6){
                UE.getEditor('sel_Q'+this.subItem_id);
                UE.getEditor('sel_'+this.subItem_id);
            }
            else{
                UE.getEditor('sel_'+this.subItem_id);
            }

            //UE.getEditor('sel_Q'+this.subItem_id);
            //UE.getEditor('sel_'+this.subItem_id);
            /*var uq=UE.getEditor('sel_Q'+i);
             uq.ready(function(){
             uq.destroy();
             UE.getEditor('sel_Q'+i)
             });
             var us=UE.getEditor('sel_'+i);
             us.ready(function(){
             us.destroy();
             UE.getEditor('sel_'+i)
             });*/



        }
        else if(type==3){//填空题

            var html='';
            html+='<div class="I_QBox">';
            html+='<div class="row">';
            html+=' <div class="formL">题目:</div>';
            html+='<div class="formR"><textarea id="content'+this.fillNum+'" class="ue_textarea"></textarea></div>';
            html+='</div>';
            html+='</div>';
            html+='<div class="I_ABox">';
            html+='<div class="row">';
            html+='<div class="formL">答案:</div>';
            html+='<div class="formR"><textarea id="answerContent'+this.fillNum+'" class="ue_textarea"></textarea></div>';
            html+='</div>';
            html+='</div>';
            html+='<div class="I_IBox"></div>';//预留给插入填空小题
            var html2='<div class="btnBar">';
            html2+='<div class="row">';
            html2+='<div class="formL"><label></label></div>';
            html2+='<div class="formR">';
            html2+='<button type="button" class="addBtn addLevel3Btn">添加填空小题</button>';
            html2+='</div>';
            html2+='</div>';
            html2+=' </div>';
            pa.html(html+html2);
            UE.getEditor('content'+this.fillNum);
            UE.getEditor('answerContent'+this.fillNum);
            /*var uq=UE.getEditor('content'+this.fillNum);
             uq.ready(function(){
             uq.destroy();
             UE.getEditor('content'+this.fillNum)
             });
             var ua=UE.getEditor('answerContent'+this.fillNum);
             ua.ready(function(){
             ua.destroy();
             UE.getEditor('answerContent'+this.selNum)
             })	*/
        }
        else if(type==4){//简答题
            var html='';
            html+='<div class="I_QBox">';
            html+='<div class="row">';
            html+='<div class="formL">题目:</div>';
            html+='<div class="formR"><textarea id="content'+this.selNum+'" class="ue_textarea"></textarea></div>';
            html+='</div>';
            html+='</div>';
            html+='<div class="I_ABox">';
            html+='<div class="row">';
            html+='<div class="formL">答案:</div>';
            html+='<div class="formR"><textarea id="answerContent'+this.selNum+'" class="ue_textarea" name="single"></textarea></div>';
            html+='</div>';
            html+='</div>';
            pa.html(html);
            uq=UE.getEditor('content'+this.selNum);
            UE.getEditor('answerContent'+this.selNum);
            /*var uq=UE.getEditor('content'+i);
             uq.ready(function(){
             uq.destroy();
             UE.getEditor('content'+i)
             });
             var ua=UE.getEditor('answerContent'+i);
             ua.ready(function(){
             ua.destroy();
             UE.getEditor('answerContent'+i)
             })		*/
        }
    },

    //点击添加小题按钮,添加小题
    addSub:function(){
        this.subItem_id++;
        this.fillNum++;
        this.selNum++;
        var i=$('.IBox .subItem').size();
        var html='';
        var html3='';//选择题题目
        html3+='<div class="I_QBox">';
        html3+='<div class="row">';
        html3+=' <div class="formL">题目:</div>';
        html3+=' <div class="formR"><textarea id="sel_Q'+this.subItem_id+'" class="ue_textarea"></textarea></div>';
        html3+='</div>';
        html3+='</div>';

        html+='<div class="subItem I_'+i+'">';
        html+='<h5>小题<em>'+(i+1)+'</em>:</h5>';
        html+='<span class="del_btn delSubBtn">删除</span>';
        html+='<div class="I_type">';
        html+='<div class="row">';
        html+='<div class="formL">题型:</div>';
        html+='<div class="formR"><select>'+this.subTypes+'</select></div>';
        html+='</div>';
        html+='</div>';
        $('.IBox').append(html);



        //判断小题题型
        var subType=$('.I_type select').find('option:first').attr('showTypeId');

        //var subType=4; // 测试小题类型

        if(subType==1 || subType==2){
            var html='';
            html+='<div class="I_Box">';
            html+=(this.showType==6 ? '': html3);//大题 题型为6时,选择题没有题目
            html+='<div class="I_SBox">';
            html+='<div class="row">';
            html+='<div class="formL">备选项<em>'+1+'</em>:</div>';
            html+='<div class="formR"><textarea id="sel_'+this.subItem_id+'" class="ue_textarea" ></textarea></div>';
            html+='</div>';
            html+='</div>';
            html+='<div class="I_ABox">';
            html+='<div class="row">';
            html+='<div class="formL">答案:</div>';
            html+='<div class="formR">';
            html+='<span><input name="beixuan'+i+'" value="'+i+'" type="'+(subType==1?'radio':'checkbox')+'" data-validation-engine= "'+(subType==1?'validate[required] radio':'validate[minCheckbox[1]] checkbox')+'" data-errormessage-value-missing="答案不能为空"/> 备选项<em>'+1+'</em></span></div>';
            html+='</div>';
            html+='</div>';

            html+='<div class="btnBar">';
            html+='<div class="row">';
            html+='<div class="formL"></div>';
            html+='<div class="formR">';
            html+='<button class="addSelBtn" type="button">添加备选项</button>';
            html+='</div>';
            html+='</div>';
        }
        else if(subType==3){

            var html='';
            html+='<div class="I_Box">';
            html+='<div class="I_SBox"></div>';
            html+='<div class="I_ABox">';
            html+='<div class="row">';
            html+='<div class="formL">答案:</div>';
            html+='<div class="formR"><textarea id="answerContent'+this.fillNum+'"  class="ue_textarea" ></textarea></div>';
            html+='</div>';
            html+='</div>';
            html+='<div class="I_IBox"></div>';

            html+='<div class="btnBar">';
            html+='<div class="row">';
            html+='<div class="formL"><label></label></div>';
            html+='<div class="formR">';
            html+='<button type="button" class="addLevel3Btn">添加填空小题</button>';
            html+=' </div>';
            html+='</div>';
            html+='</div>';
            html+='</div>';
        }
        else if(subType==4){
            var html='';
            html+=' <div class="I_Box">';
            html+='<div class="I_QBox">';
            html+='<div class="row">';
            html+='<div class="formL">题目:</div>';
            html+='<div class="formR"><textarea id="content'+this.selNum+'" class="ue_textarea"></textarea></div>';
            html+='</div>';
            html+='</div>';
            html+='<div class="I_SBox"></div>';
            html+='<div class="I_ABox">';
            html+='<div class="row">';
            html+='<div class="formL">答案:</div>';
            html+=' <div class="formR"><textarea  id="answerContent'+this.selNum+'" class="ue_textarea" ></textarea></div>';
            html+='</div>';
            html+='</div>';
            html+='</div>';
        }
        $('.I_'+i).append(html);

        if(subType==1 || subType==2){

            if(this.showType!=6){
                UE.getEditor('sel_Q'+this.subItem_id);
                UE.getEditor('sel_'+this.subItem_id);
            }
            else{
                UE.getEditor('sel_'+this.subItem_id);
            }

        }
        if(subType==3){

            UE.getEditor('answerContent'+this.fillNum);
        }
        if(subType==4){
            UE.getEditor('content'+this.selNum);
            UE.getEditor('answerContent'+this.selNum);
        }



        /*var uq=UE.getEditor('content0'+this.subItem_id);
         uq.ready(function(){
         uq.destroy();
         UE.getEditor('sel_Q'+this.subItem_id);
         });
         var ua=UE.getEditor('sel_'+this.subItem_id);
         ua.ready(function(){
         ua.destroy();
         UE.getEditor('sel_'+this.subItem_id);
         })	*/

        //UE.getEditor('sel_Q'+this.subItem_id);
        //UE.getEditor('sel_'+this.subItem_id);


    },

    //onload添加小题
    addItem:function(child,i,level,j){//i小题的序列  level:第三层级(小小题) j:小题索引
        /*var defaultVal={
         "quesType":"1",//小题的类型
         "content":"小题的题目内容",//小题的题目
         "answerContent":"0",//小题答案
         "answerOptionJson":[//sel:小题的备选项
         {
         "id":"001",
         "content":"小题备选项144444444444内容",
         "right":"1"//1正确答案，0错误答案
         },
         {
         "id":"002",
         "content":"小题备选项2内容",
         "right":"1"//1正确答案，0错误答案
         }
         ],
         "childQuesJson":[//小小题
         {
         "quesType":"",
         "content":"",
         "answerContent":"",
         "answerOptionJson":"",
         "analytical":""
         }
         ]
         };*/
        //var obj=$.extend({},defaultVal,child);
        var obj=$.extend({},child);
        var showType=obj.quesType;
        if(showType==30001||showType==30005){
            var theAnswerOptionJson = null;
            if(obj.answerOptionJson){
                theAnswerOptionJson = eval('(' + obj.answerOptionJson + ')');
            }
            var selHtml=this.createSelHtml(theAnswerOptionJson, showType);//创建多选项

            var html='';
            var html3='';//选择题题目
            html3+='<div class="I_QBox">';
            html3+='<div class="row">';
            html3+=' <div class="formL">题目:</div>';
            html3+=' <div class="formR"><textarea id="content'+i+'" class="ue_textarea">'+this.childQuesJson[i].content+'</textarea></div>';
            html3+=' </div>';
            html3+=' </div>';

            html+='<div class="subItem I_'+i+'">';
            html+='<h5>小题<em>'+(i+1)+'</em>:</h5>';
            html+='<span class="del_btn delSubBtn">删除</span>';
            html+='<div class="I_type">';
            html+='<div class="row">';
            html+='<div class="formL">题型:</div>';
            html+='<div class="formR"><select>'+this.subTypes+'</select></div>';
            html+='</div>';
            html+='</div>';
            html+='<div class="I_Box">';
            html+=(this.showType==6 ? '': html3);//大题 题型为6时,选择题没有题目
            html+='<div class="I_SBox">';
            html+=selHtml[0];   //备选项列表
            html+='</div>';
            html+='<div class="I_ABox">';
            html+='<div class="row">';
            html+='<div class="formL">答案:</div>';
            html+='<div class="formR">';
            html+=selHtml[1]; //备选项答案
            html+='</div>';
            html+='</div>';
            html+='</div>';
            html+='<div class="btnBar">';
            html+='<div class="row">';
            html+='<div class="formL"></div>';
            html+='<div class="formR">';
            html+='<button class="addSelBtn" type="button">添加备选项</button>';
            html+='</div>';
            html+=' </div>';
            html+='</div>';
            html+='</div>';
            html+='</div>';
            $('.IBox').append(html);
            //UE.getEditor('content'+i);
            //UE.getEditor('answerContent');

            /*var uq=UE.getEditor('content'+i);
             uq.ready(function(){
             uq.destroy();
             UE.getEditor('content'+i)
             });
             var ua=UE.getEditor('answerContent');
             ua.ready(function(){
             ua.destroy();
             UE.getEditor('answerContent')
             })	*/
        }

        else if(showType==30031){
            var html="";
            html+='<div class="subItem I_'+i+'">';
            html+='<h5>小题<em>'+(i+1)+'</em>:</h5>';
            html+='<span class="del_btn delSubBtn">删除</span>';
            html+='<div class="I_type">';
            html+='<div class="row">';
            html+='<div class="formL">题型:</div>';
            html+='<div class="formR"><select>'+this.subTypes+'</select></div>';
            html+='</div>';
            html+='</div>';
            html+='<div class="I_Box">';
            html+='<div class="I_QBox">';
            html+='<div class="row">';
            html+='<div class="formL">题目:</div>';
            html+='<div class="formR"><textarea  id="content'+i+'" class="ue_textarea">'+obj.content+'</textarea></div>';
            html+='</div>';
            html+='</div>';
            html+='<div class="I_SBox"></div>';
            html+='<div class="I_ABox">';
            html+='<div class="row">';
            html+='<div class="formL">答案:</div>';
            html+='<div class="formR"><textarea id="answerContent'+i+'" class="ue_textarea">'+obj.answerContent+'</textarea></div>';
            html+='</div>';
            html+='</div>';
            html+='</div>';
            html+=' </div>';
            $('.IBox').append(html);

            UE.getEditor('content'+i);
            UE.getEditor('answerContent'+i);

            /*var uq=UE.getEditor('content'+i);
             uq.ready(function(){
             uq.destroy();
             UE.getEditor('content'+i)
             });
             var ua=UE.getEditor('answerContent'+i);
             ua.ready(function(){
             ua.destroy();
             UE.getEditor('answerContent'+i)
             })	*/
        }

        else if(showType==30017){
            if(level==3){
                $('.I_'+j+' .I_ABox').empty();
                var html="";
                html+='<div class="row I_I_'+i+'"">';
                html+='<div class="formL">小小题<em>'+(i+1)+'</em>:</div>';
                html+='<div class="formR">';
                html+='<textarea id="answerContent'+j+i+'" class="ue_textarea">'+obj.answerContent+'</textarea>';
                html+='<span class="del_btn delLevel3Btn">删除</span>';
                html+='</div>';
                html+='</div>';

                $('.I_'+j+' .I_IBox').append(html);

                UE.getEditor('answerContent'+j+i);

                /*var ua=UE.getEditor('answerContent'+j+i);
                 ua.ready(function(){
                 ua.destroy();
                 UE.getEditor('answerContent'+j+i)
                 })*/
            }
            else{
                if(this.showType==3){//判断大题题型
                    var html="";
                    html+='<div class="row I_'+i+'">';
                    html+='<div class="formL">小题<em>'+(i+1)+'</em>:</div>';
                    html+='<div class="formR">';
                    html+='<textarea id="content'+i+'" class="ue_textarea">'+obj.answerContent+'</textarea>';
                    html+='<span class="del_btn delStype3Btn">删除</span>';
                    html+='</div>';
                    html+='</div>';
                    $('.IBox').append(html);
                    var uq=UE.getEditor('content'+i);
                    uq.ready(function(){
                        uq.destroy();
                        UE.getEditor('content'+i)
                    });
                }
                else{

                    var html="";
                    html+='<div class="subItem I_'+i+'">';
                    html+='<h5>小题<em>'+(i+1)+'</em>:</h5>';
                    html+='<span class="del_btn delSubBtn">删除</span>';
                    html+='<div class="I_type">';
                    html+='<div class="row">';
                    html+='<div class="formL">题型:</div>';
                    html+='<div class="formR"><select>'+this.subTypes+'</select></div>';
                    html+='</div>';
                    html+='</div>';
                    html+='<div class="I_Box">';
                    html+='<div class="I_QBox">';
                    html+='<div class="row">';
                    html+=' <div class="formL">题目:</div>';
                    html+='<div class="formR"><textarea id="content'+i+'" class="ue_textarea">'+obj.content+'</textarea></div>';
                    html+='</div>';
                    html+='</div>';
                    html+='<div class="I_SBox"></div>';
                    html+='<div class="I_ABox">';
                    html+='<div class="row">';
                    html+='<div class="formL">答案:</div>';
                    html+='<div class="formR"><textarea id="answerContent'+i+'" class="ue_textarea">'+obj.answerContent+'</textarea></div>';
                    html+='</div>';
                    html+='</div>';
                    html+='<div class="I_IBox"></div>';
                    html+='<div class="btnBar">';
                    html+='<div class="row">';
                    html+='<div class="formL"><label></label></div>';
                    html+='<div class="formR">';
                    html+='<button type="button" class="addLevel3Btn">添加填空小题</button>';
                    html+='</div>';
                    html+='</div>';
                    html+='</div>';
                    html+='</div>';
                    html+='</div>';
                    $('.IBox').append(html);
                    //UE.getEditor('content'+i);
                    //UE.getEditor('answerContent'+i);
                    /*var uq=UE.getEditor('content'+i);
                     uq.ready(function(){
                     uq.destroy();
                     UE.getEditor('content'+i)
                     });*/

                    /* var ua=UE.getEditor('answerContent'+i);
                     ua.ready(function(){
                     ua.destroy();
                     UE.getEditor('answerContent'+i)
                     })*/




                }
            }
            if(  obj.childQues &&  obj.childQues.length>0){
                for( var j=0; j<obj.childQues.length; j++){
                    this.addItem(obj.childQues[j],j,3,i)
                }
            }
        }



    },

    //添加备选项
    addSelItem:function(btn){
        this.selNum++;
        var html='';
        var pa=btn.parents('[class$="Box"]');
        var i=pa.children('[class$="SBox"]').children('.row').size();
        //var lastId=pa.children('[class$="SBox"]').find('[id^="sel_"]:last').attr('id');//读取最后一个id
        //this.selNum=parseInt(lastId.split("_")[1]);
        var type="";
        if(this.showType==1) type="radio";
        else if(this.showType==2) type="checkbox";
        else if(this.showType==5 ||this.showType==6||this.showType==7){
            type=pa.children('[class$="ABox"]').find('input').attr('type')
        }

        var name=pa.children('[class$="ABox"]').find('input').attr('name');
        html+='<div class="row">';
        html+='<div class="formL">备选项<em>'+(i+1)+'</em></div>';
        html+='<div class="formR">';
        html+='<textarea id="sel_'+this.selNum+'" class="ue_textarea"></textarea>';
        html+='<span class="del_btn delSelBtn">删除</span>';
        html+='</div>';
        html+='</div>';
        var html2='<span><input name="'+name+'" value="'+i+'" type="'+type+'"> <label>备选项<em>'+(i+1)+'</em></label></span>';
        pa.children('[class$="SBox"]').append(html);
        pa.children('[class$="ABox"]').find('.formR').append(html2);
        UE.getEditor('sel_'+this.selNum);
    },


    //排序
    Sequence:function(pa,tag,attr){
        pa.find(tag).each(function(index) {
            $(this).text(index+1)
        });
        if(attr){
            pa.find(tag).each(function(index) {
                $(this).attr(attr,index)
            });
        }
    },

//--------------------------------------------------------------------------------------

    //得到表单对象
    GetFormObj:function(showType){//大题的题型
        var type =showType;
        var question= $('.QBox textarea').val();//题目
        var sel=[];//备选项
        var subItem=[];//小题
        var analy=UE.getEditor('analytical').getContent();//解析
        var answer=[];//答案

        if(type==1 || type==2 ){//选择题
            $('.SBox textarea').each(function(index) {
                sel.push($(this).val())
            });

            $('.ABox input:checked').each(function(index) {
                answer.push($(this).parent('span').index())
            });
        }
        else if(type==3){//填空题
            if($('.IBox').children().size()>0){
                $('.IBox textarea').each(function(index) {
                    answer.push($(this).val())
                });
            }
            else{
                $('.ABox textarea').each(function(index) {
                    answer.push($(this).val())
                });
            }


        }
        else if(type==4){
            $('.ABox textarea').each(function(index) {
                answer.push($(this).val())
            });
        }
        else if(type==5||type==6 || type==7){
            var subSize=$('.IBox .subItem').size();
            $('.IBox .subItem').each(function(index) {
                var subCont={};//小题的obj
                subCont.title=$(this).find('.I_QBox textarea').val();//小题题目
                subCont.sel=[];//小题的sel
                subCont.type="";//小题题型
                subCont.sel_answer=[];//选择题答案

                subCont.type=$(this).find('select').val();

                $(this).find('.I_ABox input:checked').each(function(index) {
                    subCont.sel_answer.push($(this).parent('span').index());
                });
                subCont.Muti_answer=[];//小题 填空题多个答案
                subCont.answer=$(this).find('.I_ABox textarea').val();//小题的答案 简答题/填空题一个答案
                $(this).find('.I_SBox textarea').each(function(index) {
                    subCont.sel.push($(this).val());
                });
                $(this).find('.I_IBox textarea').each(function(index) {
                    subCont.Muti_answer.push($(this).val());
                });
                subItem.push(subCont)
            });
        }
        /*
         obj={
         question:'';//题目
         sel=[];//大题的备选项
         subItem=[//小题
         {
         title:''         //小题题目
         sel:[]           //小题备选项
         sel_answer:[]    //小题备选项答案
         answer:''        //小题填空题答案
         Muti_answer:[]   //小题填空题多个答案
         },
         {
         title:''         //小题题目
         sel:[]           //小题备选项
         sel_answer:[]    //小题备选项答案
         answer:''        //小题填空题答案
         Muti_answer:[]   //小题填空题多个答案
         }
         ];
         analy =''//解析
         answer=[];//答案
         }
         */
        var obj={'question':question,'sel':sel,'subItem':subItem,'answer':answer,'analy':analy};
        return obj;
    },

    //生成大题的html
    getViewHtml:function(showType){//showType 大题的题型
        var obj=this.GetFormObj(showType);//showType 大题的题型
        var html='';
        html+='<div class="QuestionCont">';
        html+='<h5 class="Q_title">'+obj.question+'</h5>';

        if(showType==1 || showType==2){
            html+='<div class="sub">';
            html+='<dl class="clearfix">';
            html+='<dt>备选项:</dt>';
            for(var i=0; i<obj.sel.length; i++){
                html+='<dd>'+obj.sel[i]+'</dd>';
            }
            html+='</dl>';
            html+='</div>';
            html+='</div>';
        }
        if(showType==5 || showType==6 || showType==7){
            for(var i=0; i<obj.subItem.length; i++){
                html+='<div class="sub">';
                html+='<h6 class="subTitle">第'+(i+1)+'小题</h6>';
                if(this.showType==6 && obj.subItem[i].sel.length>0){
                    html+='<p></p>';
                }
                else{
                    html+='<p>题目:'+obj.subItem[i].title+'</p>';
                }
                html+='<dl class="clearfix">';
                if(obj.subItem[i].sel.length>0){
                    html+='<dt>备选项:</dt>';
                    for(var j=0; j<obj.subItem[i].sel.length; j++){
                        html+='<dd>'+obj.subItem[i].sel[j]+'</dd>';
                    }
                }
                else if(obj.subItem[i].Muti_answer.length>0){
                    html+='<dt>填空题:</dt>';
                    for(var j=0; j<obj.subItem[i].Muti_answer.length; j++){
                        html+='<dd>'+obj.subItem[i].Muti_answer[j]+'</dd>';
                    }
                }
                else{
                    html+='<dt></dt>';
                    html+='<dd>'+obj.subItem[i].answer+'</dd>';//简答题 单个填空
                }
                html+='</dl>';
                html+='</div>';
            }
        }

        html+='<div class="btnBar">';
        html+='<a class="openAnalyBtn">查看答案和解析</a><span>难度:<em>1</em>&nbsp;&nbsp;录入:<i>我自己</i></span>';
        html+='</div>';
        html+='<div class="A_cont clearfix">';

        html+='<dl>';
        html+='<dt>答案:</dt>';
        if(obj.subItem.length>0){//如果有小题
            for(var i=0; i<obj.subItem.length; i++){
                html+='<dd>';
                html+='<h6>'+(i+1)+'小题:</h6>';
                html+='<p>';
                if(obj.subItem[i].sel.length>0){//小题备选项答案
                    html+='<span>';
                    for(var j=0; j<obj.subItem[i].sel_answer.length; j++){
                        html+='备选项'+(obj.subItem[i].sel_answer[j]+1)+', ';
                    }
                    html+='</span>';
                }
                else if(obj.subItem[i].Muti_answer.length>0){
                    html+='<span>'+obj.subItem[i].Muti_answer+'</span>';
                }
                else{
                    html+='<span>'+obj.subItem[i].answer+'</span>';//简答题 单个填空
                }
                html+='</p>';
                html+='</dd>';
            }
        }
        else{
            html+='<dd>';
            for(var j=0; j<obj.answer.length; j++){
                if(preview.showType==1||preview.showType==2){
                    html+='<span><em>备选项:</em>'+(obj.answer[j]+1)+'</span> ';
                }
                else if(preview.showType==3){
                    html+='<span><em>答案'+(j+1)+':</em>'+obj.answer[j]+'</span> ';
                }
                else{
                    html+='<span>'+obj.answer[j]+'</span> ';
                }

            }
            html+='</dd>';
        }
        html+='</dl>';
        html+='<dl class="analyCont">';
        html+='<dt>解析:</dt>';
        html+='<dd>'+obj.analy+'</dd>';
        html+='</dl>';
        html+='</div>';
        return html;
    },

    //预览窗口
    viewBox: function(htmlStr){
        var html = '';
        html+='<div class="popBox previewBox hide" title="题目预览">';
        html += htmlStr;
        html+='</div>';
        $('body').append(html);
        $('.previewBox').dialog({
            autoOpen:false,
            width:600,
            modal: true,
            resizable:false,
            buttons: [
                {
                    text: "确定",
                    click: function() {
                        $(this).remove();
                    }
                }
            ]
        });
        $( ".previewBox" ).dialog( "open" );
    },
    /*删除UE对象*/
    del_UE_obj:function(pa){//父级
        var IDs=pa.find('textarea');
        IDs.each(function(index, element) {
            var id=$(this).attr('id');
            UE.delEditor(id);
        })
    }

};


$(function(){
    preview.showType=<?php echo $info->showTypeId?>;
    preview.subTypes='<?php echo $childType ?>';

    //页面初始化
    preview.onloadType();

    //添加小题
    $('.addSubBtn').live('click',function(){

        preview.addSub();
    });

    //删除小题
    $('.delSubBtn').live('click',function(){
        var pa=$(this).parent('.subItem');
        $(this).parent('.subItem').remove();
        preview.Sequence($('.IBox .subItem h5'),"em");//小题号排序
        $('.IBox .subItem').each(function(index, element) {
            $(this).attr('class',"subItem I_"+index);
        });
        preview.del_UE_obj(pa);
    });

    //添加备选项
    $('.addSelBtn').live('click',function(){
        preview.addSelItem($(this));
    });

    //删除备选项
    $('.delSelBtn').live('click',function(){
        var pa=$(this).parents("[class$='SBox']");
        var index=$(this).parents('.row').index();
        pa.next().find('span').eq(index).remove();
        $(this).parents('.row').remove();
        preview.Sequence(pa,"em");//备选项排序
        preview.Sequence(pa.next(),"em");//备选项答案排序
        preview.Sequence(pa.next(),"input","value");//input(radio checkbox)value值
        preview.del_UE_obj($(this).parent('div'));

    });


    //添加填空小小题
    $('.addLevel3Btn').live('click',function(){
        var pa_I_ABox=$(this).parents('.subItem').find('.I_ABox');
        preview.del_UE_obj(pa_I_ABox);
        pa_I_ABox.empty();
        var pa=$(this).parents('.I_Box');
        var i=pa.children('.I_IBox').children().size();
        preview.selNum++;
        var	html='<div class="row I_I_'+i+'">';
        html+='<div class="formL">小小题<em>'+(i+1)+'</em>:</div>';
        html+='<div class="formR">';
        html+='<textarea id="subItem_'+(preview.selNum)+'" class="ue_textarea"></textarea>';
        html+='<span class="del_btn delLevel3Btn">删除</span>';
        html+='</div>';
        html+='</div>';
        pa.children('.I_IBox').append(html);
        var ue = UE.getEditor('subItem_'+(preview.selNum));
    });

    //删除填空小小题
    $('.delLevel3Btn').live('click',function(){
        var pa=$(this).parents('.I_IBox');
        $(this).parents('.row').remove();
        preview.Sequence(pa,"em");//小小题排序
        preview.del_UE_obj($(this).parent('div'));


    });


    //添加填空题 showType=3
    $('.addType3Btn').live('click',function(){

        UE.delEditor('answer');
        $('.ABox').remove();
        var pa=$(this).parents('.Box');
        var i=pa.children('.IBox').children().size();
        preview.selNum++;

        var	html='<div class="row I_'+i+'">';
        html+='<div class="formL">小题<em>'+(i+1)+'</em>:</div>';
        html+='<div class="formR">';
        html+='<textarea id="subItem_'+(preview.selNum)+'" class="ue_textarea"></textarea>';
        html+='<span class="del_btn delStype3Btn">删除</span>';
        html+='</div>';
        html+='</div>';
        pa.children('.IBox').append(html);
        var ue = UE.getEditor('subItem_'+(preview.selNum));
    });

    //删除填空小题
    $('.delStype3Btn').live('click',function(){
        var pa=$(this).parents('.IBox');
        $(this).parents('.row').remove();
        preview.Sequence(pa,"em");//小题号排序
        preview.del_UE_obj($(this).parent('div'));
    });

    //修改题型
    $('select').live('change',function(){
        var I_SBox_pa=$(this).parents('.subItem').find('.I_SBox');
        var I_ABox_pa=$(this).parents('.subItem').find('.I_ABox');
        if(I_SBox_pa) preview.del_UE_obj(I_SBox_pa);
        if(I_ABox_pa) preview.del_UE_obj(I_ABox_pa);
        preview.changeType($(this));

    });

    //预览
    $('.viewBtn').bind('click',function(){
        //var html=preview.getViewHtml(preview.showType);
        //preview.viewBox(html);
        $("#form").validationEngine('detach').validationEngine({
            promptPosition:"centerRight",
            maxErrorsPerField:1,
            showOneMessage:true,
            addSuccessCssClassToField:'ok'
        });
        if ($("#form").validationEngine('validate') == false) {
            return false;
        }
        var obj=preview.GetFormObj(preview.showType);
        var url='<?php echo url("/teacher/testpaper/save-ques-content")?>';
        var id = $("#Id").val();
        var str = '';

        if(obj.subItem!=''){
            var childSel = [];
            $.each(obj.subItem, function(key, val) {
                if(val.Muti_answer!=''){
                    var muti = [];
                    $.each(val.Muti_answer, function(k, v) {
                        var sel = {"quesType":val.type,"content":val.title,"answerContent":v,"answerOptionJson":""};
                        muti.push(sel);
                    });
                    obj.subItem[key].Muti_answer = muti;
                    var childobj={"quesType":val.type,'content':val.title,"answerContent":val.sel_answer,"answerOptionJson":val.sel,"childQuesJson":val.Muti_answer};
                }else if(val.sel!=''){
                    var childSelOption = [];
                    var str = '';
                    $.each(val.sel, function(k, v) {
                        v = v.replace(/\"/g,'\'');
                        if($.inArray(k,val.sel_answer) != -1){
                            var sel2 = {"id":k,"content":v,"right":"1"};
                        }else{
                            var sel2 = {"id":k,"content":v,"right":"0"};
                        }
                        childSelOption.push(sel2);
                    });
                    obj.subItem[key].sel = childSelOption;
                    $.each(val.sel_answer,function(a,b){
                        str += b + ",";
                    });
                    val.sel_answer = str.substring(0,str.length-1);
                    var childobj={"quesType":val.type,'content':val.title,"answerContent":val.sel_answer,"answerOptionJson":val.sel,"childQuesJson":''};
                }else{
                    var childobj={"quesType":val.type,'content':val.title,"answerContent":val.answer,"answerOptionJson":val.sel,"childQuesJson":''};
                }
                childSel.push(childobj);
            });
            obj.subItem = childSel;
        }

        if(obj.sel!=''){
            var selOption = [];
            var childQuesJson = '';
            $.each(obj.sel, function(key, val) {
                val = val.replace(/\"/g,'\'');
                if($.inArray(key,obj.answer) != -1){
                    var sel = {"id":key,"content":val,"right":"1"};
                }else{
                    var sel = {"id":key,"content":val,"right":"0"};
                }
                selOption.push(sel);
            });
            obj.sel = selOption;
            $.each(obj.answer,function(a,b){
                str += b + ",";
            });
            obj.answer = str.substring(0,str.length-1);
        }else{
            var count = 0;
            $.each(obj.answer,function(a,b){
                str += b + ",";
                count++;

            });
            if(count > 1){
                $.each(obj.answer, function(k, v) {
                    var sel = {"quesType":'30017',"content":obj.question,"answerContent":v,"answerOptionJson":""};
                    obj.subItem.push(sel);
                });
                obj.answer = '';
            }else{
                obj.answer = str.substring(0,str.length-1);
            }

        }
        $.post(url,{'id':id,'answerOptionJson':JSON.stringify(obj.sel),'answerContent':obj.answer,'analytical':obj.analy,'childQuesJson':JSON.stringify(obj.subItem),'saveType':0},function(msg){
            if(msg.success==true){

                $.post('/teacher/testpaper/viewtopic',{'id':id},function(e){
                    preview.viewBox(e);
                })
            }
            //var html=preview.getViewHtml(preview.showType);
            //preview.viewBox(html);
        })
    });

    //判断富文本内容是否为空
    function judgeContent(){
        result=true;
        UE.utils.each(UE.instants, function (value, key) {

            if (value.key) {
                try{
                    var t = value.getContent();
                    if (t == '') {
                        message = '题目或答案不能为空';
                        if (value.key.indexOf('sel_') == 0) {
                            message = '备选项不能为空'
                        }
                        if (value.key.indexOf('analytical') == 0) {
                            message = '解析不能为空'
                        }
                        if (value.key.indexOf('subItem_') == 0) {
                            message = '小题不能为空'
                        }
                        if (value.key.indexOf('answer') == 0) {
                            message = '题目不能为空'
                        }

                        if (value.key.indexOf('answerContent') == 0) {
                            message = '答案不能为空'
                        }
                        value.focus();
                        result = false;


                        popBox.alertBox(message);
                        return false;


                    }}
                catch (e){
                }
            }
        });
        return result;
    }

    //保存
    $('.saveBtn').click(function(){

        //judgeContent();     //it is empty or not
        if(!judgeContent()){
            return false;
        }
        $("#form").validationEngine('detach').validationEngine({
            promptPosition:"centerRight",
            maxErrorsPerField:1,
            showOneMessage:true,
            addSuccessCssClassToField:'ok'
        });
        if ($("#form").validationEngine('validate') == false) {
            return false;
        }
        var obj=preview.GetFormObj(preview.showType);

        var url='<?php echo url("/teacher/testpaper/save-ques-content")?>';
        var id = $("#Id").val();
        var str = '';


        if(obj.subItem!=''){
            var childSel = [];
            $.each(obj.subItem, function(key, val) {
                if(val.Muti_answer!=''){
                    var muti = [];
                    $.each(val.Muti_answer, function(k, v) {
                        var sel = {"quesType":val.type,"content":val.title,"answerContent":v,"answerOptionJson":""};
                        muti.push(sel);
                    });
                    obj.subItem[key].Muti_answer = muti;
                    var childobj={"quesType":val.type,'content':val.title,"answerContent":val.sel_answer,"answerOptionJson":val.sel,"childQuesJson":val.Muti_answer};
                }else if(val.sel!=''){
                    var childSelOption = [];
                    var str = '';
                    $.each(val.sel, function(k, v) {
                        v = v.replace(/\"/g,'\'');
                        if($.inArray(k,val.sel_answer) != -1){
                            var sel2 = {"id":k,"content":v,"right":"1"};
                        }else{
                            var sel2 = {"id":k,"content":v,"right":"0"};
                        }
                        childSelOption.push(sel2);
                    });
                    obj.subItem[key].sel = childSelOption;
                    $.each(val.sel_answer,function(a,b){
                        str += b + ",";
                    });
                    val.sel_answer = str.substring(0,str.length-1);

                    var childobj={"quesType":val.type,'content':val.title,"answerContent":val.sel_answer,"answerOptionJson":val.sel,"childQuesJson":''};
                }else{
                    var childobj={"quesType":val.type,'content':val.title,"answerContent":val.answer,"answerOptionJson":val.sel,"childQuesJson":''};
                }
                childSel.push(childobj);
            });
            obj.subItem = childSel;
        }

        if(obj.sel!=''){
            var selOption = [];
            var childQuesJson = '';
            $.each(obj.sel, function(key, val) {
                val = val.replace(/\"/g,'\'');
                if($.inArray(key,obj.answer) != -1){
                    var sel = {"id":key,"content":val,"right":"1"};
                }else{
                    var sel = {"id":key,"content":val,"right":"0"};
                }
                selOption.push(sel);
            });
            obj.sel = selOption;
            $.each(obj.answer,function(a,b){
                str += b + ",";
            });
            obj.answer = str.substring(0,str.length-1);
        }else{
            var count = 0;
            $.each(obj.answer,function(a,b){
                str += b + ",";
                count++;

            });
            if(count > 1){
                $.each(obj.answer, function(k, v) {
                    var sel = {"quesType":'30017',"content":obj.question,"answerContent":v,"answerOptionJson":""};
                    obj.subItem.push(sel);
                });
                obj.answer = '';
            }else{
                obj.answer = str.substring(0,str.length-1);
            }

        }
        $.post(url,{'id':id,'answerOptionJson':JSON.stringify(obj.sel),'answerContent':obj.answer,'analytical':obj.analy,'childQuesJson':JSON.stringify(obj.subItem),'saveType':1},function(msg){
            if(msg.success==true){
                location.href="<?php echo url('/teacher/testpaper/topic-finish')?>?status=1";
            }
            else{
                location.href="<?php echo url('/teacher/testpaper/topic-finish')?>?status=0";
            }
        })
    });
    //保存草稿箱
    $('.saveDraftBox').click(function(){

        //judgeContent();     //it is empty or not
        if(!judgeContent()){
            return false;
        }
        $("#form").validationEngine('detach').validationEngine({
            promptPosition:"centerRight",
            maxErrorsPerField:1,
            showOneMessage:true,
            addSuccessCssClassToField:'ok'
        });
        if ($("#form").validationEngine('validate') == false) {
            return false;
        }
        var obj=preview.GetFormObj(preview.showType);
        var url='<?php echo url("/teacher/testpaper/save-ques-content")?>';
        var id = $("#Id").val();
        var str = '';

        if(obj.subItem!=''){
            var childSel = [];
            $.each(obj.subItem, function(key, val) {
                if(val.Muti_answer!=''){
                    var muti = [];
                    $.each(val.Muti_answer, function(k, v) {
                        var sel = {"quesType":val.type,"content":val.title,"answerContent":v,"answerOptionJson":""};
                        muti.push(sel);
                    });
                    obj.subItem[key].Muti_answer = muti;
                    var childobj={"quesType":val.type,'content':val.title,"answerContent":val.sel_answer,"answerOptionJson":val.sel,"childQuesJson":val.Muti_answer};
                }else if(val.sel!=''){
                    var childSelOption = [];
                    var str = '';
                    $.each(val.sel, function(k, v) {
                        v = v.replace(/\"/g,'\'');
                        if($.inArray(k,val.sel_answer) != -1){
                            var sel2 = {"id":k,"content":v,"right":"1"};
                        }else{
                            var sel2 = {"id":k,"content":v,"right":"0"};
                        }
                        childSelOption.push(sel2);
                    });
                    obj.subItem[key].sel = childSelOption;
                    $.each(val.sel_answer,function(a,b){
                        str += b + ",";
                    });
                    val.sel_answer = str.substring(0,str.length-1);
                    var childobj={"quesType":val.type,'content':val.title,"answerContent":val.sel_answer,"answerOptionJson":val.sel,"childQuesJson":''};
                }else{
                    var childobj={"quesType":val.type,'content':val.title,"answerContent":val.answer,"answerOptionJson":val.sel,"childQuesJson":''};
                }
                childSel.push(childobj);
            });
            obj.subItem = childSel;
        }

        if(obj.sel!=''){
            var selOption = [];
            var childQuesJson = '';
            $.each(obj.sel, function(key, val) {
                val = val.replace(/\"/g,'\'');
                if($.inArray(key,obj.answer) != -1){
                    var sel = {"id":key,"content":val,"right":"1"};
                }else{
                    var sel = {"id":key,"content":val,"right":"0"};
                }
                selOption.push(sel);
            });
            obj.sel = selOption;
            $.each(obj.answer,function(a,b){
                str += b + ",";
            });
            obj.answer = str.substring(0,str.length-1);
        }else{
            var count = 0;
            $.each(obj.answer,function(a,b){
                str += b + ",";
                count++;

            });
            if(count > 1){
                $.each(obj.answer, function(k, v) {
                    var sel = {"quesType":'30017',"content":obj.question,"answerContent":v,"answerOptionJson":""};
                    obj.subItem.push(sel);
                });
                obj.answer = '';
            }else{
                obj.answer = str.substring(0,str.length-1);
            }

        }
        $.post(url,{'id':id,'answerOptionJson':JSON.stringify(obj.sel),'answerContent':obj.answer,'analytical':obj.analy,'childQuesJson':JSON.stringify(obj.subItem),'saveType':0},function(msg){
            if(msg.success==true){
                location.href="<?php echo url('/teacher/testpaper/topic-finish')?>?status=1";
            }
            else{
                location.href="<?php echo url('/teacher/testpaper/topic-finish')?>?status=0";
            }
        })
    });
    $('.h3L').click(function () {
        popBox.face($(this), $(this));
    });
})
</script>
<div class="grid_19 main_r">
    <div class="main_cont">
        <div class="title">
            <h4>修改题目</h4>
        </div>
        <hr>

        <br>
        <?php $form =\yii\widgets\ActiveForm::begin( [
            'enableClientScript' => false,
            'id' => 'form',
        ])?>
        <input type="hidden" value="<?php echo $info->id ?>" id="Id">
        <div class="itemsBox">
            <div class="QBox">
                <div class="row">
                    <div class="formR" style="padding-bottom:20px; width:100%">
                        <div class="testPaperView">
                            <div class="paperArea">
                                <div class="paper"><!--选择题-->
                                    <h6><?php if(isset($info->year) && !empty($info->year)){echo '【'.$info->year.'年】';}?> <?=$info->provenanceName; ?> <?=$info->questiontypename; ?></h6>
                                    <p class="q_content"><?php echo StringHelper::htmlPurifier($info->content)?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="Box">

            </div>
            <div class="NBox">
                <div class="row">
                    <div class="formL">解析:</div>
                    <div class="formR" style="width: 500px">
                        <?php
                        echo \frontend\widgets\ueditor\MiniUEditor::widget(
                            array(
                                'id'=>'analytical',
                                'name'=>'analytical',
                                'UEDITOR_CONFIG'=>array(
                                    'maximumWords'=>'1000',
                                ), 'options' => array(
                                'value' => $info->analytical,
                            )
                            ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php \yii\widgets\ActiveForm::end(); ?>
        <hr>
        <div class="row submit_BtnBar">
            <div class="formL"></div>
            <div class="formR">
                <button type="button" class="saveBtn bg_blue">保存题目</button>
                <!--<button type="button" class="viewBtn">题目预览</button>-->
          <!--      <button type="button" class="saveDraftBox bg_blue_l">保存到草稿箱</button>-->
            </div>
        </div>
    </div>
</div>
