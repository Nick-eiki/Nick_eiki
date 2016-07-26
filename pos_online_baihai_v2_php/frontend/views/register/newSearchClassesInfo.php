<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-11
 * Time: 上午10:20
 */
?>
<?php
 if(empty($pageList)){ ?>

    <div class='prompt'> 没有检索到相应的班级，点击创建新班级 </div>
 <?php }else{
foreach ($pageList as $item) { ?>
    <li id="<?php echo $item->classID; ?>" title="<?php echo $item->viewClass; ?>"><?php echo $item->viewClass; ?></li>
<?php }   }?>
