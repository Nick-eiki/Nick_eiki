<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-15
 * Time: 下午3:07
 */
/* @var $this yii\web\View */  $this->title="教师-备课-素材库";
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
                        if ($('#form_id').validationEngine('validate')) {
                           var name= $('#name').val();
                            var stuLimit=0;
                            var groupMemberLimit=0;

                        if($("#departmentMemLimit").attr("checked")=="checked"){
                            var departmentMemLimit=1;
                        }
                       $.post('<?php echo url('teacher/briefcase/add-material');?>',{name:name,stuLimit:stuLimit,groupMemberLimit:groupMemberLimit,departmentMemLimit:departmentMemLimit},function(data){
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
                        $.post("<?php echo url('teacher/briefcase/edit-data-bag')?>",{id:id,name:name,student:student,group:group,department:department},function(data){
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
            $.post("<?php echo url("teacher/briefcase/get-data-bag")?>",
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


    <div class="currentRight grid_16 push_2">
        <div class="noticeH clearfix">
            <h3 class="h3L">素材库</h3>
            <div class="fr">
                <a href="javascript:" class="B_btn120">添加素材包</a>
            </div>
        </div>
        <hr>
        <div class="docPack pr">
            <ul class="docBagList">
                <?php foreach($material as $key=>$item){
                    ?>
                    <li>
                        <button type="button" title="编辑" dataId="<?php echo $item->ID;?>"></button>
                        <h5><a href="<?php echo url('teacher/briefcase/details-list',array('id'=>$item->ID))?>"><?php echo cut_str($item->Name,12);?></a></h5>
                        <p><em>可见:</em><?php
                                if($item->departmentMemLimit){?>
                                    所有人可见
                              <?php  }else{ ?>
                                    所有人不可见
                               <?php } ?>
                               </p>
                        <p><em>文件:</em>
                            <?php echo count($item->detail);?>
                        </p>
                    </li>
            <?php     }?>
            </ul>
        </div>

            <?php
             echo \frontend\components\CLinkPagerExt::widget( array(
                   'pagination'=>$pages,
//                    'updateId'=>'#collection',
                    'maxButtonCount' => 5
                )
            );
            ?>

    </div>


<!--主体内容结束-->
<!--弹出框  创建/编辑公文袋-->
<div class="popBox docBagBox" id="docBagBox" title="创建素材包">
    <form id="form_id">
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label><i></i>素材包名称:</label>
                </div>
                <div class="formR">
                    <input id="name" type="text" class="text" data-validation-engine="validate[required,maxSize[30]]" >
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>素材包权限:</label>
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
<div class="popBox docBagBox" id="edit_data" title="编辑素材包">

</div>