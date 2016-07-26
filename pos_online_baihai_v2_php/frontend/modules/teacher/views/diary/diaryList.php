<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-9-19
 * Time: 下午1:53
 */
/* @var $this yii\web\View */  $this->title="教师--教研日记";
?>

<script>
    function change_type(self) {
        var url = '<?php echo app()->request->url?>';
        var type = $(self).val();
        $.post(url, {type: type}, function (result) {
            $('#srchResult').html(result);
        })
    }
</script>
<!--主体内容开始-->
<div class="currentRight grid_16 push_2">
    <div class="c_cement clearfix">
        <h3>日记本</h3>

        <div class="c_cmentR">
            <?php
            echo Html:: dropDownList('diary_type', '', array('1' => '评课', '2' => '课题', '3' => '随笔'), array(
                "defaultValue" => false, 'prompt' => '请选择类型',
                "id" => "diary_type", 'class' => 'mySelect', 'onchange' => 'change_type(this)'
            ));
            ?>
            <a href="<?php echo url('teacher/diary/add-diary') ?>" class="B_btn120 a_link">写日记</a>
        </div>
    </div>
    <hr>
    <div id="srchResult">
        <?php echo $this->render('_diary_data', array(
            'data' => $data,
            'pages' => $pages
        )); ?>
    </div>
</div>
<!--弹出框pop--------------------->
<script type="text/javascript">
    //点击更多显示全部
    $(function () {
        $('.morejs').live('click', function () {
            $('.classHeight').css('display', 'block');
        })

    })


</script>