<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-1
 * Time: 上午11:06
 */
/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="学生-收藏夹";
?>

    <script>
        $(function(){
            $('.personal').bind('mouseover',function(){
                $(this).children('.tab').show();

            });
            $('.personal').bind('mouseout',function(){
                $(this).children('.tab').hide();

            })


        })
    </script>

<script type="text/javascript">
    $(function(){

        $('#type').change(function(){
            var type = $('#type').val();

            $.post('<?php echo app()->request->url;?>',{type:type},function(data){
                $('#collection').html(data);
            })
        });
    })
</script>



<!--主体内容开始-->

<?php if($studentId ==user()->id){
 echo $this->render('_your_student_collection', array('model'=>$model,'pages'=>$pages,'studentId'=>$studentId));
}else{
  echo $this->render('_other_student_collection', array('model'=>$model,'pages'=>$pages,'studentId'=>$studentId));
}?>


<!--主体内容结束-->





