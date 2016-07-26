<?php
/**
 *
 * @var RegisterController $this
 * @var $pages Pagination
 */
use frontend\components\helper\AreaHelper;
use yii\helpers\ArrayHelper;

?>

<form action="<?php echo url('register/search-school') ?>">
    <ul class="form_list">
        <li>
            <div class="formL">
                <label class="genre">地区：</label>
            </div>
            <div class="formR">
                <?php   /** @var $form CActiveForm */
                echo Html::dropDownList('provience', "", ArrayHelper::map(AreaHelper::getProvinceList(), 'AreaID', 'AreaName'),
                    array(
                        "defaultValue" => false, "prompt" => "请选择","id" =>"provience"
                    ));
                ?>
                <?php
                echo Html:: dropDownList('city', "", [], array(
                    "defaultValue" => false, "prompt" => "请选择","id" =>"city",
                ));
                ?>
                <?php
                echo Html:: dropDownList('county', "", [],
                    array(
                        "defaultValue" => false, "prompt" => "请选择","id" =>"county",
                    ));?>
                <span id="county_prompt"></span>
            </div>
        </li>

        <li>
            <div class="formL">
                <label class="genre">学校：</label>
            </div>
            <div class="formR">
                <input name="name" type="text" class="text">
                <input name="department" type="hidden" value="<?php echo $department;?>">
                <button type="submit" class="btn" onclick=" searchSchool(this); return false;">查找</button>
            </div>

        </li>
    </ul>
</form>
<ul class="schoolList clearfix">
    <?php foreach ($pageList as $l) { ?>
        <li value="<?php echo $l->schoolID ?>"><?php echo $l->schoolName ?></li>
    <?php } ?>
</ul>
    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#updateSchool',
            'maxButtonCount' => 5
        )
    );
    ?>

<hr>

<p class="tc">如果没有找到学校，请单击此处<a href="javascript:" class="addSchoolBtn" onclick="showNewSchool();">添加学校</a></p>

<form action="<?php echo $this->createUrl('addSchool') ?>" id="addSchool">
    <ul class="form_list newSchool hide">
        <li>
            <div class="formL">
                <label>地区：</label>
            </div>
            <div class="formR">
                <?php   /** @var $form CActiveForm */
                echo Html::dropDownList('provience', "", ArrayHelper::map(AreaHelper::getProvinceList(), 'AreaID', 'AreaName'),
                    array(
                        "defaultValue" => false, "prompt" => "请选择","id" =>"provience1"
                    ));
                ?>
                <?php
                echo Html:: dropDownList('city', "", [], array(
                    "defaultValue" => false, "prompt" => "请选择","id" =>"city1",
                ));
                ?>
                <?php
                echo Html:: dropDownList('county', "", [],
                    array(
                        "defaultValue" => false, "prompt" => "请选择","id" =>"county1",
                    ));?>
                <span id="county_prompt"></span>
            </div>
        </li>
        <li>
            <div class="formL">
                <label>学校类别：</label>
            </div>
            <div class="formR">
                <?php echo Html::checkboxList('department', $pages->params['department'], ['20201' => '小学', '20202' => '初中', '20203' => '高中'],
                    ['separator' => '&nbsp;', 'uncheckValue' => $pages->params['department']
                    ]) ?>
            </div>

        </li>
        <script type="text/javascript">
            $('#addSchool :checkbox').each(function () {
                if ($(this).attr('checked')) {
                    $(this).attr("disabled", true);
                }
            });


        </script>
        <li>
            <div class="formL">
                <label>学校：</label>
            </div>
            <div class="formR">
                <input name="name" type="text" class="text">
            </div>
        </li>
        <li>
            <div class="formL">
                <label>学校学制：</label>
            </div>
            <div class="formR">
                <select name="lengthOfSchooling">
                    <option value="20501">六三制</option>
                    <option value="20502">五四制</option>
                    <option value="20502">五三制</option>
                </select>
            </div>
        </li>
    </ul>
</form>


<script type="text/javascript">
    //省市区联动
    $('#provience').change(function(){jQuery.ajax({'url':'/ajax/GetArea','data':{'id':this.value},'success':function(html){jQuery("#city").html(html).change();},'cache':false});return false;});
    $('#city').change(function(){jQuery.ajax({'url':'/ajax/GetArea','data':{'id':this.value},'success':function(html){jQuery("#county").html(html).change();},'cache':false});return false;});

    $('#provience1').change(function(){jQuery.ajax({'url':'/ajax/GetArea','data':{'id':this.value},'success':function(html){jQuery("#city1").html(html).change();},'cache':false});return false;});
    $('#city1').change(function(){jQuery.ajax({'url':'/ajax/GetArea','data':{'id':this.value},'success':function(html){jQuery("#county1").html(html).change();},'cache':false});return false;});


    searchSchool = function (obj) {
        var form = $(obj).parents('form');
        $.get(form.attr('action'), form.serialize(), function (html) {
            $('#updateSchool').html(html)
        });
    };

    $('.schoolList.clearfix li').click(function () {
        var $this = $(this);
        $('.newSchool').hide();
        $('#addSchool')[0].reset();
        $this.addClass('ac').siblings('li').each(function () {
            $(this).removeClass('ac');
        })
    });
    var showNewSchool = function () {
        $('.newSchool').show();
        $('.schoolList.clearfix li').each(function () {
                $(this).removeClass('ac');
            }
        );

    };
</script>