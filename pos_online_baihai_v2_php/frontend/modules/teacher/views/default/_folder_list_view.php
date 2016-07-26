<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-2
 * Time: 下午12:10
 */

?>
<?php if($userId ==$teacherId){ ?>
    <?php  echo $this->render('_your_folder_view', array('model'=>$model,'pages'=>$pages,'teacherId'=>$teacherId));?>
<?php }else{ ?>
    <?php  echo $this->render('_other_folder_view', array('model'=>$model,'pages'=>$pages,'teacherId'=>$teacherId));?>
<?php } ?>
