<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-2
 * Time: 下午12:02
 */

/* @var $this yii\web\View */  $this->title="教师首页-资料列表";
?>


    <script type="text/javascript">
        $(function(){
//点击选择按钮 加上背景
            $('.tabList li').css('cursor','pointer');
            $('.tabList li').bind('click',function(){
                $(this).siblings('li').removeClass('ac');
                $(this).addClass('ac');
                var type= $(this).attr('typeId');
                $.post('<?php echo app()->request->url;?>',{type:type},function(data){
                    $('#folder').html(data);
                })
            });

        })

    </script>

<!--主体内容开始-->
<?php if($userId ==$teacherId){ ?>
    <?php  echo $this->render('_your_detailList', array('model'=>$model,'pages'=>$pages,'teacherId'=>$teacherId,'id'=>$id,'typeName'=>$typeName,'userId'=>$userId));?>
 <?php }else{ ?>
    <?php  echo $this->render('_other_detailList', array('model'=>$model,'pages'=>$pages,'teacherId'=>$teacherId,'userId'=>$userId));?>
<?php } ?>


<!--主体内容结束-->



