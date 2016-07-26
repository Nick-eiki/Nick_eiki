<?php
/**
 *
 * @var RegisterController $this
 * @var PsiWhiteSpace $subjectList
 * @var PsiWhiteSpace $pageInfo
 * @var #P#M#C\TeachingGroupService.searchTeachingGroupByPage.schoolList|? $pageList
 */
use frontend\models\dicmodels\SubjectModel;

?>
<?php if ((empty($pages->params['subjectID']) && !empty($pageList)) || !empty($pages->params['subjectID'])){ ?>
<form
    action="<?php echo url($this->createUrl(''), ['schoolId' => $pages->params['schoolId'], 'department' => $pages->params['department']]) ?>">

    <ul class="form_list">
        <li>
            <div class="formL">
                <label>科目：</label>
            </div>

            <div class="formR schoolRadio">
                <?php echo Html::radioList('subjectID', $pages->params['subjectID'],
                    SubjectModel::model()->getListData(), ['separator' => '&nbsp;',
                        'template' => '<label> {input}{labelTitle}</label>',
                        'onclick' => 'searchGroup(this)'
                    ]
                )
                ?>
            </div>
        </li>
    </ul>
</form>
<ul class="groupList clearfix">

    <?php foreach ($pageList as $l) { ?>
        <li value="<?php echo $l->groupID ?>"><?php echo $l->groupName ?></li>
    <?php } ?>
</ul>

    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#updateGroup',
            'maxButtonCount' => 5
        )
    );
    ?>

<hr>
<?php } ?>
<p class="tc">如果没有找到教研组，请单击此处<a href="javascript:" class="addGroupBtn">添加教研组</a></p>

<form id="addGroupInfo"  action="<?php echo url($this->createUrl('add-teaching-group')) ?>">
    <ul class="form_list newGroupList hide">
        <li>
            <div class="formL">
                <label>科目：</label>
            </div>

            <div class="formR schoolRadio">
                <?php echo Html::radioList('subjectID', $pages->params['subjectID'],
                    SubjectModel::model()->getListData(), ['separator' => '&nbsp;',
                        'template' => '<label> {input}{labelTitle}</label>',
                    ]
                )
                ?>
            </div>
        </li>
        <li>

            <div class="formL">
                <label>教研组名称：</label>
            </div>
            <div class="formR">
                <input type="text" name="groupName" class="text">
            </div>
            <input name="schoolId" type="hidden" value="<?php echo $pages->params['schoolId'] ?>">
            <input name="department" type="hidden" value="<?php echo $pages->params['department'] ?>">
        </li>
    </ul>
</form>

<script type="text/javascript">
    $('.groupList li').click(function () {
        var $this = $(this);
        $('.newClassesList').hide();
        $('#addGroupInfo')[0].reset();
        $this.addClass('ac').siblings('li').each(function () {
            $(this).removeClass('ac');
        })
    });

    var searchGroup = function (obj) {
        var form = $(obj).parents('form');
        $.get(form.attr('action'), form.serialize(), function (html) {
            $('#updateGroup').html(html)
        });
    }
</script>