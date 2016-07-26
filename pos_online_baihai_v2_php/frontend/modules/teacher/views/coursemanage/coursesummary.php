<?php
/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-10-30
 * Time: 下午4:47
 */
/* @var $this yii\web\View */  $this->title='课程管理-课程总结';
$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
?>
<div class="currentRight grid_17 push_1 hear">
    <div class="notice courseManage">
        <div class="noticeH clearfix noticeB">
            <h3 class="h3L">班级阶段总结</h3>
            <div class="new_not fr"> <em>我的班级：</em>
                <select class="select_tab" onchange="classSearch();">
				 <?php foreach($teacherClassList as $value){?>
                    <option value="<?php echo $value->classID ?>"<?php if($value->classID == $classid){?> selected="selected" <?php }?>><?php echo $value->className ?></option>
				<?php }?>
                </select>
				<em class="sum">总结：</em>
                    <select class="select_tab2" onchange="listSearch();">

					<?php foreach($status['name'] as $key=>$val){?>
                    <option value="<?php echo $key?>"<?php if($key == $status['flag']){?> selected="selected" <?php }?>><?php echo $val?></option>
					<?php }?>
                      
                    </select>

                <button class="new_examination newSumBtn">新建总结</button>
            </div>
        </div>
        <hr>
        <div class="courseManage_main">
            <ul class="courseManage_List">
			<?php foreach($obj_list->summaryList as $val){?>
                <li class="clearfix pr">
                    <div>
				         <h4 class="title"><?php echo $val->summarizeName?></h4>
                         <input type="hidden" value="<?php echo $val->subjectID ?>" class="subject">
                        <p class="time_release"><em class="startTime"><?php echo date("Y-m-d",strtotime($val->beginTime))?></em> 　至　 <em class="endTime"><?php echo date("Y-m-d",strtotime($val->finishTime))?></em></p>
                        <dl class="clearfix">
                            <dt>知识难点：</dt>
                            <dd class="Qpoint">
                            	<?php foreach ($val->knowledgePoint as $v){
                            			echo "<span val='".$v."'>".KnowledgePointModel::getNamebyId($v)."</span>";
                          		  }
                            	?>
                            </dd>
                         </dl>
                         <dl class="clearfix">
                            <dt>学习氛围：</dt>
                            <dd class="state2">
                            <span class="state" ><?php echo mb_substr($val->classAtmosphere, 0, 30, 'utf-8'); ?></span>
                            <span class="state_more"><?php echo $val->classAtmosphere?> </span>
                            <?php if(!empty($val->classAtmosphere)){?>
                                <em class="more_sum" style="color: #0055aa;">[详情..]</em>
                                <?php }?>
                            </dd>
                           </dl>
                           <dl class="clearfix">
                            <dt>学习规划： </dt>
                            <dd class="plan"><?php echo $val->studyPlan ?></dd>
                            <dd class="summarizeID" style="display: none;"><?php echo $val->summarizeID ?></dd>
                            
                        </dl>
						<?php if($status['flag'] == 0){
							echo "<button type='button' class='btn editBtn'>修改</button>";
						}?>            
						
                    </div>
                </li>
                <?php } ?>
            </ul>
            <br>

		        <?php
		         echo \frontend\components\CLinkPagerExt::widget( array(
		               'pagination'=>$pages,
		                'maxButtonCount' => 5
		            )
		        );
		        ?>
        </div>
    </div>
</div>
<input type="hidden" value="<?php echo $departmentId ?>" id="departmentId">
<link href="<?php echo publicResources() ?>/js/ztree/zTreeStyle/zTreeStyle.css" rel="stylesheet" type="text/css">
<script src="<?php echo publicResources() ?>/js/ztree/jquery.ztree.all-3.5.min.js" type="text/javascript"></script>

<script>
    $(function(){
        var zNodes =[];
        var subObj = "<?php echo $subjectList;?>";


//修改总结
        var obj={};//数据对象
        var pa;//单元

        function getVal(tpa){//获取弹出框中的输入值
            obj.title=tpa.find('.title').val();
            obj.startTime=tpa.find('.startTime').val();
            obj.endTime=tpa.find('.endTime').val();
            obj.point={};
            obj.point.id=[];
            obj.point.name=[];
            obj.summarizeID = tpa.find('.summarizeID').val(); 
            var html='';
            var aLi=tpa.find('.labelList').children('li');
            if(aLi.size()>0){
                aLi.each(function(index, element) {
                    obj.point.id.push($(this).attr('val'));
                    obj.point.name.push($(this).text());
                    html+='<span val="'+obj.point.id[index]+'">'+obj.point.name[index]+'</span>';
                });
            }
            else{
                html="空";
            }
            obj.state=tpa.find('.state_more').val()||"空";
            obj.plan=tpa.find('.plan').val()||"空";
            return html;
        }

        $('.courseManage_List li .editBtn').live('click',function(){
            pa=$(this).parent();
            obj.title=pa.find('.title').text();
            obj.startTime=pa.find('.startTime').text();
            obj.endTime=pa.find('.endTime').text();
            obj.point=[];
            pa.find('.Qpoint span').each(function(index, element) {
                obj.point.push($(this).attr('val'));
            });
            obj.state=pa.find('.state_more').text();
            obj.plan=pa.find('.plan').text();
            obj.summarizeID = pa.find('.summarizeID').text();
            obj.sub = pa.find('.subject').val();
           
            var html='<ul class="form_list"><form id="form_edit">';
            html+='<li><div class="formL"><label><i></i>总评名称：</label></div><div class="formR"><input type="text" class="text title" id="title" value="'+obj.title+'" data-validation-engine="validate[required,maxSize[30]]" data-errormessage-value-missing="名称不能为空" ><span class="altTxt"><i class="warn_icon"></i></span></div></li>';
            html+='<li><div class="formL"><label><i></i>时间段：</label></div><div class="formR"><input type="text" class="text startTime" id="startTime" style="width:83px" onclick="WdatePicker();" value="'+obj.startTime+'" data-validation-engine="validate[required]" data-errormessage-value-missing="时间不能为空">&nbsp;&nbsp;至&nbsp;&nbsp;<input type="text" class="text endTime" id="endTime" style="width:83px" onclick="WdatePicker();" value="'+obj.endTime+'" data-validation-engine="validate[required]" data-errormessage-value-missing="时间不能为空"></div></li>';
            html+='<li><div class="formL"><label><i></i>知识难点：</label></div><div class="formR"><div class="treeParent"><button type="button" class="addPointBtn">编辑知识点</button><div class="pointArea hide"><input class="hidVal" type="hidden" value="'+obj.point+'"data-validation-engine="validate[required]" data-errormessage-value-missing="知识点不能为空"><h6>已选中知识点:</h6><ul class="labelList"></ul></div></div></div></li>';
            html+='<li><div class="formL"><label>班内氛围：</label></div><div class="formR"><textarea class="state" id="state" data-validation-engine="validate[required,maxSize[140]]">'+obj.state+'</textarea></div></li>';
            html+='<li><div class="formL"><label><i></i>学习规划：</label></div><div class="formR"><textarea class="plan" id="plan" data-validation-engine="validate[required,maxSize[140]]">'+obj.plan+'</textarea></div></li>';
            html+='<li><div class="formL"><label></label></div><div class="formR"><button type="button" class="btn saveBtn">保存</button>&nbsp;&nbsp;<button type="button" class="btn cancelBtn">取消</button></div></li></ul></form>';
            $(this).parents('li').append(html).children('div').hide();

            var Edurl = "/ajaxteacher/GetKnowledgeByDepartmentId";
            var depart =  $("#departmentId").val();
            var sub =  obj.sub;
            $.post(Edurl,{'subjectID':sub,'departmentId':depart},function(msg){
                var zNodes =msg.data;
                popBox.pointTree2(zNodes, $('.addPointBtn'));//初始化树
            });

            $('.saveBtn').live('click',function(){//保存
            	$('#form_edit').validationEngine();
	            if ($('#form_edit').validationEngine('validate')) {
					var summarizeID =  obj.summarizeID;
	                var classId = $(".select_tab").val();
	    			var subjectID = obj.sub;
	    			var summarizeName = $("#title").val();
	    			var startTime =  $("#startTime").val();
	    			var endTime =  $("#endTime").val();
	    			var knowledgepoint = $(".hidVal").val();
	    			var fenwei = $("#state").val();
	    			var studyPlan = $("#plan").val();
	    			var creatorId = <?php echo $userid?>;
	    			if(knowledgepoint == ""){
	                    popBox.alertBox('知识难点不能为空！');
	                    return false;
	                }
	                if(studyPlan == ""){
	                    popBox.alertBox('学习计划不能为空！');
	                    return false;
	                }
	    			var url = '<?php echo url("teacher/coursemanage/edit-course")?>';

	    			$.post(url, {summarizeID:summarizeID,classId: classId,subjectID:subjectID, summarizeName: summarizeName,startTime:startTime,endTime:endTime,fenwei:fenwei,studyPlan:studyPlan,knowledgepoint:knowledgepoint,creatorId:creatorId}, function (result) {
	    				 if (result.success) {
	                     	 location.reload();
							 //location.href="<?php echo url('teacher/coursemanage/coursesummary?classId=')?>"+classId;
	                     } else {
	                         alert("修改失败");
	                     }
	                });  
	            	$(this).parents('.form_list').remove();
            	}
              
            });

            $('.cancelBtn').live('click',function(){//取消修改cancel
                $(this).parents('.form_list').remove();
                $(pa).show();
            })
        });

//新建总结



        $('.newSumBtn').die('click').click(function(){
            var html='<div class="popBox newSumBox hide" title="新建总结"><form id="form_id">';
            html+='<ul class="form_list"><li><div class="formL"><label><i></i>总评科目：</label></div><div class="formR"><select id="addsub">'+subObj+'</select><i class="altTxt"></i></div></li>';
            html+='<li><div class="formL"><label><i></i>总评名称：</label></div><div class="formR"><input id="name" type="text" class="text title" value="" data-validation-engine="validate[required,maxSize[30]]" data-errormessage-value-missing="名称不能为空" ><span class="altTxt"><i class="altTxt"></i></span></div></li>';
            html+='<li><div class="formL"><label><i></i>时间段：</label></div><div class="formR"><input  id="time1" type="text" class="text startTime" style="width:83px" onclick="WdatePicker();" value="" data-validation-engine="validate[required]" data-errormessage-value-missing="时间不能为空"/>&nbsp;&nbsp;至&nbsp;&nbsp;<input type="text" id="time2" class="text endTime" style="width:83px" onclick="WdatePicker();" value="" data-validation-engine="validate[required]" data-errormessage-value-missing="时间不能为空"/></div></li>';
            html+='<li><div class="formL"><label><i></i>知识难点：</label></div><div class="formR"><div class="treeParent"><button type="button" class="addPointBtn">编辑知识点</button><div class="pointArea hide"><input class="hidVal" type="hidden" id="" value="" data-validation-engine="validate[required]" data-errormessage-value-missing="知识点不能为空"><h6>已选中知识点:</h6><ul class="labelList"></ul></div></div></div>	</li>';
            html+='<li><div class="formL"><label>班内氛围：</label></div><div class="formR"><textarea class="state" id="qf" data-validation-engine="validate[required]" onkeyup="words_deal();"></textarea></div><br><i style="float:right;margin-right:25px;">剩余<span id="textCount">300</span>个字</i></li><li><div class="formL"><label><i></i>学习规划：</label></div><div class="formR"><textarea class="plan" id="gh" data-validation-engine="validate[required]" onkeyup="words_deal2();"></textarea><br><i style="float:right;">剩余<span id="textCount2">300</span>个字</i></li></ul></form></div>';
            $('body').append(html);
            $("#addsub").change(function(){
                $('.newSumBox .addPointBtn').next('.pointArea').children('.hidVal').val('');
                $('.newSumBox .addPointBtn').next('.pointArea').hide();
            });
            $('.newSumBox .addPointBtn').click(function(){
                var url = "/ajaxteacher/GetKnowledgeByDepartmentId";
                var departmentId = $("#departmentId").val();
                var subjectId = $("#addsub").val();
                $this=$(this);
                $.post(url,{'subjectID':subjectId,'departmentId':departmentId},function(msg){
                    var zNodes =msg.data;
                    popBox.pointTree(zNodes,$this);
                });

            });
            $('.newSumBox').dialog({
            	
                autoOpen: false,
                width:580,
                modal: true,
				close: function() {$( this ).remove()},
                resizable:false,
                buttons: [
                    {
                    	
                        text: "保存",
                        click: function(e) {
                        	$('#form_id').validationEngine();
                            insertNewItem();
                            
                        }
                    },
                    {
                        text: "取消",
                        click: function() {
                            $( this ).remove();
                        }
                    }
                ]
            });

            $( ".newSumBox" ).dialog( "open" );
            return false;
        });
        function insertNewItem(){//插入新增总结
        	
        	 if ($('#form_id').validationEngine('validate')) {
                 var name = $("#name").val();
                 var time1 = $("#time1").val();
                 var time2 = $("#time2").val();
                 var qf = $("#qf").val();
                 var gh = $("#gh").val();
                 
				var url = '<?php echo url("teacher/coursemanage/add-course")?>';
                var classId = $(".select_tab").val();
				var subjectID =$("#addsub").val();
				var knowledgepoint = $(".hidVal").val();
				var creatorId = <?php echo $userid?>;
				if(knowledgepoint == ""){
                    popBox.alertBox('知识难点不能为空！');
                    return false;
	             }
	             if(gh == ""){
	                 popBox.alertBox('学习计划不能为空！');
	                 return false;
	             }
                $.post(url, {classId: classId,subjectID:subjectID, summarizeName: name,startTime:time1,endTime:time2,fenwei:qf,studyPlan:gh,knowledgepoint:knowledgepoint,creatorId:creatorId},
                       function (data) {
                          if (data.success) {
                            	location.reload();
                          } else {
                                popBox.alertBox(data.message);
                          } 
                });
                $( this ).remove();
        }
        }
    });
    //详情
    $('.state_more').hide();
    $('.more_sum').toggle(function(){
        $(this).siblings('.state').hide();
        $(this).siblings('.state_more').show();
    },function(){
        $(this).siblings('.state_more').hide();
        $(this).siblings('.state').show();
    });

    function classSearch(){
    	var status = $(".select_tab2").val();
		var classId = $(".select_tab").val();
        location.href="<?php echo url('teacher/coursemanage/coursesummary')?>"+"?classId="+classId+"&status="+status;
     }

	function listSearch(){
		var status = $(".select_tab2").val();
		var classId = $(".select_tab").val();
        location.href="<?php echo url('teacher/coursemanage/coursesummary')?>"+"?classId="+classId+"&status="+status;
	}
	function words_deal(){ 
		var curLength=$("#qf").val().length; 
		if(curLength>300){ 
			var num=$("#qf").val().substr(0,300); 
			$("#qf").val(num); 
			$("#textCount").text(0);
			popBox.alertBox("超过字数限制，多出的字将被截断！" ); 
		}else { 
			$("#textCount").text(300-$("#qf").val().length); 
		} 
	}
	function words_deal2(){ 
		var curLength=$("#gh").val().length; 
		if(curLength>300){ 
			var num=$("#gh").val().substr(0,300); 
			$("#gh").val(num);
			$("#textCount2").text(0);
			popBox.alertBox("超过字数限制，多出的字将被截断！" ); 
		}else { 
			$("#textCount2").text(300-$("#gh").val().length); 
		} 
	}
  
</script>
