<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 14-11-4
 * Time: 下午5:12
 */


/* @var MakepaperController $this */
/* @var  Pagination $pages */
?>
<?php foreach ($list as $key => $item) {
echo $this->render('//publicView/paper/_showItemProblemNoAnswer', array('item' => $item));
} ?>
