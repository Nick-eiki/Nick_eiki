<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-1
 * Time: 下午6:24
 */
/* @var $this yii\web\View */
/* @var $this yii\web\View */  $this->title="教师首页-资料库";
$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
?>

    <script>
        $(function(){
            $('#docBagBox').dialog({
                autoOpen: false,
                width:550,
                modal: true,
                resizable:false,
                buttons:[
                    {
                        text: "确定",
                        click: function() {
                            if ($('#forms_id').validationEngine('validate')) {
                                var name= $('#names').val();
                                    var stuLimit=0;
                                    var groupMemberLimit=0;

                                if($("#departmentMemLimit").attr("checked")=="checked"){
                                    var departmentMemLimit=1;
                                }
                                $.post('<?php echo url('teacher/default/add-data-bag');?>',{name:name,stuLimit:stuLimit,groupMemberLimit:groupMemberLimit,departmentMemLimit:departmentMemLimit},function(data){
                                    if(data.success){
                                        location.reload();
                                    }else{
                                        popBox.alertBox(data.message);
                                    }
                                })

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

            $('#edit_data').dialog({
                autoOpen: false,
                width:550,
                modal: true,
                resizable:false,
                buttons:[
                    {
                        text: "确定",
                        click: function() {
                            if ($('#form_editId').validationEngine('validate')) {
                                var id=$('#dataBagId').val();//资料袋id
                                var name=$('#edit_name').val();
                                    var student=0;
                                    var group=0;

                                if($("#department").attr("checked")=="checked"){
                                    var department=1;
                                }
                                $.post("<?php echo url('teacher/default/edit-data-bag')?>",{id:id,name:name,student:student,group:group,department:department},function(data){
                                    if(data.success){
                                        location.reload();
                                    }else{
                                        popBox.alertBox(data.message);
                                    }
                                })
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
            $('.docBagList li button').live('click',function(){
                _this = $(this);
                var id = _this.attr('dataId');
                $.post("<?php echo url("teacher/default/get-data-bag")?>",
                    {id: id}, function (data) {
                        $("#edit_data").dialog("open");
                        $('#edit_data').html(data);
                    });
            });
            $('.B_btn120').click(function(){
                $( "#docBagBox").dialog( "open" );
            })
        })
    </script>

<!--主体内容开始-->
<?php if($teacherId ==$userId){ ?>
    <?php  echo $this->render('_your_view', array('material'=>$material,'pages'=>$pages,'teacherId'=>$teacherId));?>
<?php }else{ ?>
    <?php  echo $this->render('_other_view', array('material'=>$material,'pages'=>$pages,'teacherId'=>$teacherId));?>
<?php }?>


<!--主体内容结束-->
<div class="popBox docBagBox" id="docBagBox" title="创建资料袋">
    <form id="forms_id">
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label><i></i>资料袋名称:</label>
                </div>
                <div class="formR">
                    <input id="names" type="text" class="text" data-validation-engine="validate[required]" >
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>资料袋权限:</label>
                </div>
                <div class="formR">
                    <input type="radio" value="0" name="stuLimit[stuLimit]" id="stuLimit" data-validation-engine="validate[minCheckbox[1]]"> <label>所有人不可见</label>&nbsp;&nbsp;
<!--                    <input type="radio" value="0" name="stuLimit[stuLimit]" id="groupMemberLimit" data-validation-engine="validate[minCheckbox[1]]"><label>教研组可见</label>&nbsp;&nbsp;-->
                    <input type="radio" value="0" name="stuLimit[stuLimit]" id="departmentMemLimit" data-validation-engine="validate[minCheckbox[1]]"> <label>所有人可见</label>
                    <br>
                </div>
            </li>
        </ul>
    </form>
</div>
<!--弹出框  创建/编辑公文袋-->
<div class="popBox docBagBox" title="修改资料袋" id="edit_data">

</div>

