<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-9
 * Time: 下午8:42
 */

?>

<div class="subTitleBar">
    <h5>选择省</h5>
    <form action="<?php echo url('register/new-search-school-info') ?>">
        <div class="subTitle_r">
            <input name="department" type="hidden" value="<?php echo Yii::$app->request->getParam('department'); ?>">
            <input id="searchText" type="text" class="text" name="name">
            <button type="submit" class="hideText searchBtn" onclick=" searchSchool(this); return false;">搜索</button>
        </div>
    </form>
</div>
<div class="popCont"><a href="#" class="txtBtn gray_d creatNewSchool hide" department="<?php echo Yii::$app->request->getParam('department'); ?>">创建新学校</a>
    <a href="javascript:;" class="backTopArea hide">返回顶级</a>

    <div class="selectArea">
        <div class="crumbListWrap">
            <div class="crumbList clearfix hide">
                <a class="back_selectArea" href="javascript:">返回上级</a></div>
        </div>
        <dl class="clearfix stateList">
        </dl>
        <dl class="clearfix cityList hide">
        </dl>
        <dl class="clearfix districtList hide">
        </dl>
    </div>
    <ul class="resultList schoolList clearfix hide" id="schoolListInfo">

    </ul>

    <div class="newSchool hide">

    </div>
</div>
<div class="popBtnArea">
    <button type="button" class="okBtn">确定</button>
    <button type="button" class="cancelBtn">取消</button>
</div>

<script type="text/javascript">
    searchSchool = function (obj) {
        var form = $(obj).parents('form');
        $.post(form.attr('action'), form.serialize(), function (html) {
            $('#updateSchool #schoolListInfo').html(html);
            $('.popCont .selectArea').hide();
            $('.newSchool').hide();
            $('.popCont .backTopArea,#schoolListInfo').show();
        });
    };
    $('#addSchool :checkbox').each(function () {
        if ($(this).attr('checked')) {
            $(this).attr("disabled", true);
        }
    });
    $('#addSchool').validationEngine({'maxErrorsPerField': 2});
</script>