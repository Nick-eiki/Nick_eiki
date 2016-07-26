<?php use frontend\models\dicmodels\GradeModel;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */  $this->title="教师-课题列表";?>


    <script type="text/javascript">
        $(function(){
              $("#grade").change(function () {
                var grade = $("#grade").val();
                $.post("<?php echo app()->request->url?>", {'grade': grade}, function (data) {
                    $('#update').html(data);
                })
            })
        })



    </script>


<!--主体内容开始-->

    <div class="currentRight grid_16 push_2 teachingPlan_div">
        <div class="crumbs"> <a href="#">教研课题</a> >> 课题列表</div>
        <div class="noticeH clearfix noticeB teachingPlan_title">

            <h3 class="h3L">课题列表</h3>
            <div class="new_not fr">
                <?php
                echo Html::dropDownList('grade',
                    '',
                    ArrayHelper::map(GradeModel::model()->getList(), 'gradeId', 'gradeName'),
                    array("prompt" => "所有年级"));
                ?>
             </div>
        </div>
        <hr>
        <div id="update">
        <?php  echo $this->render('_list_course', array('taskCourseList' => $taskCourseList,'pages' => $pages));?>
        </div>
    </div>

<!--主体内容结束-->

