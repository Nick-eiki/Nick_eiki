<?php
/**
 *
 * @var ManagepaperController $this
 */
/* @var $this yii\web\View */  $this->title='试卷列表';
?>
<script>
    function search(obj){
        $.post($(obj).attr('url'), {getType: $(obj).val()}, function(result){
            $('#srchResult').replaceWith(result);
        })
    }

    function deletePaper(obj) {
        if (!confirm('确定要删除吗？')) return false;
        var url = '<?php echo url("teacher/managepaper/delete-paper")?>';
        $.post(url, {paperId: $(obj).attr('paperId')}, function(result) {
            if (result.success === true) {
                location.reload();
            }
        });
    }
</script>

<div class="currentRight grid_16 push_2">
    <div class="noticeH clearfix noticeB uploadedPaper_title">
        <h3 class="h3L">试卷管理</h3>

        <div class="new_not fr"><em>试卷类别：</em>
            <?php echo Html::dropDownList('getType', '', array('0' => '我上传的试卷', '1' => '我组织的试卷'), array(
                'prompt' => '请选择试卷类型', 'defaultValue' => null,
                ' onchange' => "search(this)", 'url' => app()->request->url
            ))?>
            <a href="<?php echo url('teacher/managepaper/upload-paper') ?>"
               class="new_examination uploadNewtestpaperBtn">上传新试卷</a>
            <a href="<?php echo url("teacher/makepaper/paper-header"); ?>"
               class="new_examination uploadNewtestpaperBtn">组卷</a></div>
    </div>
    <hr>
    <?php echo $this->render('_paperListData', array('data' => $data, 'pages' => $pages))?>
</div>

