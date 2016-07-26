<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-3-26
 * Time: 下午3:48
 */
?>
<?php
foreach ($result as $v) {

    foreach ($v->subList as $item) {
        ?>
        <input type="radio" value='<?php echo $item->subjectID; ?>' name="HomeContactForm[subjectId]"  <?php echo $subjectId ==$item->subjectID ? 'checked' : '' ?>
               class="radio pointRadio" department="<?php echo $v->department; ?>">
        <label><?php echo $item->subjectName; ?></label>
    <?php
    }

}
?>