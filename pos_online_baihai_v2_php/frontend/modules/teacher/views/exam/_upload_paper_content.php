<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-22
 * Time: 下午6:08
 */
use yii\helpers\ArrayHelper;

?>
<div class="impBox">
    <ul class="form_list">

        <li>
            <div class="formL">
                <label><i></i>选择试卷：</label>
            </div>
            <div class="formR">
                <?php
                echo Html::dropDownList("paperId",
                    '', ArrayHelper::map($paperArray, 'paperId', 'paperName')
                    ,
                    array(

                        "prompt" => "请选择",
                        "id" => "paperId"
                    ));
                ?>
            </div>
        </li>
    </ul>

</div>
<script>
    $('#up-manage select').change(function(){
        var value=$(this).val();

        if(value=='0')
        {

            $( "#up_other" ).dialog( "open" );
            //event.preventDefault();
            $(this).children('option:first').attr("selected",true);
            return false;


        }

    });
    $('#up_other').dialog({
        autoOpen: false,
        width:500,
        modal: true,
        resizable:false,
        buttons: [
            {
                text: "上传试卷",

                click: function() {
                    window.open('<?php echo url("teacher/managepaper/upload-paper")?>');
                    $( this ).dialog( "close" );


                }
            },
            {
                text: "在线组卷",

                click: function() {
                    window.open('<?php echo url("teacher/makepaper/paper-header")?>');
                    $( this ).dialog( "close" );
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
</script>