<?php  /** @var $this Controller */
use frontend\models\dicmodels\SchoolLevelModel;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */  $this->title="招生简章列表";
$schoolModel=$this->params['schoolModel'];
?>
<script>
    change = function () {
        var url = '<?php echo app()->request->url?>';
        var year = $('#year').val();
        var schoolLevel = $('#schoolLevel').val();
        $.post(url, {year: year, schoolLevel: schoolLevel}, function (result) {
            $('#srchResult').replaceWith(result);
        })
    }
</script>
<div class="main_c clearfix class_listi" style="padding-bottom:50px;">
    <h3>历年招生简章</h3>
    <?php if (loginUser()->isTeacher() && loginUser()->getTeacherInSchool($schoolModel->schoolID)): ?>
        <a href="<?php echo url('school/add-brief', array('schoolId' => $schoolModel->schoolID)) ?>"
           class="B_btn120 addBtn" style="margin-left:16px;">添&nbsp;&nbsp;加</a>
    <?php endif; ?>
	<hr>
    <div class="selectBox">
                              年份
                        	<?php
                            echo Html::dropDownList('year',
                                '',
                                ArrayHelper::map(getYears(), 'year', 'year'),
                                array('id' => 'year',
                                    'prompt' => '请选择', 'onchange' => 'change()'
                                ));
                            ?>
            
                &nbsp;&nbsp;学部
        <?php
        echo Html::dropDownList('schoolLevel',
            '',
            SchoolLevelModel::model()->getListData(),
            array('id' => 'schoolLevel', 'class' => 'mySel', 'prompt' => '请选择', 'onchange' => 'change()'));
        ?>
    </div>
    <div class="class_list_min">
        <div class="s_class_list sc_guide">
            <?php echo $this->render('_brief_data', array(
                'data' => $data,
               'pagination'=>$pages
            ))?>
        </div>

    </div>
</div>