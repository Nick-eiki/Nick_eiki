<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-10
 * Time: 下午4:43
 */
?>
<?php foreach($pageList as $key=>$item){ ?>
    <li id="<?php echo $item->schoolID ?>" title="<?php echo $item->schoolName ?>"><?php echo $item->schoolName ?></li>
<?php   }?>