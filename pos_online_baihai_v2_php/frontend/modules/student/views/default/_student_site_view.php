<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-5
 * Time: 下午4:30
 */
?>

<?php if($studentId ==user()->id){
 echo $this->render('_your_collection_view', array('model'=>$model,'pages'=>$pages,'studentId'=>$studentId));
}else{
  echo $this->render('_other_collection_view', array('model'=>$model,'pages'=>$pages,'studentId'=>$studentId));
}?>


<script type="text/javascript">
    $(function(){

        $('.del').bind('click',function(){
            $this = $(this);
            var collectID=$this.attr("collectID");
            $.post("<?php echo url("student/default/del-collection")?>", {collectID: collectID}, function (data) {
                if (data.success) {
//                    location.reload();
                    $this.parent().parent('li').remove();
                }else{
                    popBox.alertBox(data.message);
                }
            });
        });
    })

</script>