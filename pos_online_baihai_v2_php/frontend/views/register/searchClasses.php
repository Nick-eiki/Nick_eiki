<?php
/**
 *
 * @var RegisterController $this
 * @var #P#M#C\SchoolInfoService.searchSchoolInfoByPage.schoolList|? $pageList
 * @var \Pagination $pages
 */
use yii\helpers\ArrayHelper;

?>
<?php if((empty($pages->params["gradeID"])&&!empty($pageList))||!empty($pages->params["gradeID"])){?>
<form
    action="<?php echo url('register/search-classes', ['schoolId' => $pages->params['schoolId'], 'department' => $pages->params['department']]) ?>">
    <ul class="form_list">
        <li>
            <div class="formL">
                <label>年级：</label>
            </div>
            <div class="formR">
                <?php echo Html::radioList('gradeID', $pages->params['gradeID'],
                    ArrayHelper::map($gradeList, 'gradeId', 'gradeName'), ['separator' => '&nbsp;',
                        'template' => '<label> {input}{labelTitle}</label>',
                        'onclick' => 'searchClasses(this)'
                    ]
                )
                ?>
            </div>
        </li>
        <li>
            <ul class="classesList">

                <?php foreach ($pageList as $l) { ?>
                    <li value="<?php echo $l->classID ?>"><?php echo $l->className ?></li>
                <?php } ?>
            </ul>
                <?php
                 echo \frontend\components\CLinkPagerExt::widget( array(
                       'pagination'=>$pages,
                        'updateId' => '#updateClass',
                        'maxButtonCount' => 5
                    )
                );
                ?>
        </li>
    </ul>
</form>
<?php }?>
<br>
<!--<ul id="editForm" class="form_list">-->
<!--    <li>-->
<!--        <div class="formL">-->
<!--            <label>班级别名：</label>-->
<!--        </div>-->
<!--        <div class="formR">-->
<!--            <input type="text" class="text">-->
<!--            <br>-->
<!--        </div>-->
<!--    </li>-->
<!--</ul>-->
<hr>
<p>如果没有找到班级，请单击此处<a href="javascript:" onclick="showNewClasss();" class="addClassesBtn">添加班级</a></p>
<form id="addClassInfo" action="<?php echo url($this->createUrl('add-class-info')) ?>">
<ul class="form_list newClassesList hide">

        <li>
            <div class="formL">
                <label>选择班级：</label>
            </div>
            <div class="formR">
                <?php echo Html::dropDownList('joinYear', '', getClassYears()) ?>
                <?php echo Html::dropDownList('classNumber', '', getClassNumber()) ?>
            </div>
        </li>
        <li>
            <input name="schoolId" type="hidden" value="<?php echo $pages->params['schoolId'] ?>">
            <input name="department" type="hidden" value="<?php echo $pages->params['department'] ?>">


            <div class="formL">
                <label>班级别名：</label>
            </div>
            <div class="formR">
                <input name="className" type="text" class="text">
                <br>
            </div>
        </li>

</ul>
</form>
<script type="text/javascript">
    var searchClasses = function (obj) {
        var form = $(obj).parents('form');
        $.get(form.attr('action'), form.serialize(), function (html) {
            $('#updateClass').html(html)
        });
    };
    $('.classesList li').click(function () {
        var $this = $(this);
        $('.newClassesList').hide();
        $('#addClassInfo')[0].reset();
        $this.addClass('ac').siblings('li').each(function () {
            $(this).removeClass('ac');
        })
    });
    var showNewClasss = function () {
        $('.newClassesList').show();
        $('.classesList li').each(function () {
                $(this).removeClass('ac');
            }
        );
        $('.classesList').hide();
    };
</script>