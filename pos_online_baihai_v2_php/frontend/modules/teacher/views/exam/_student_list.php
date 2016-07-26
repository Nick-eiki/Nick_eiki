<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-23
 * Time: 下午5:13
 */
?>
<?php foreach ($studentAnswer->data->ansList as $k => $v) {
    if ($k < 2) { ?>

        <tr>
            <td width="133"><?php echo $v->studentName ?></td>
            <td width="166"><?php echo $v->stuID ?></td>
            <input type="hidden" value="<?php echo $v->examSubID ?>">
            <td width="174"><span examSubID="<?php echo $v->examSubID ?>"
                                  studentID="<?php echo $v->studentID ?>"><?php echo $v->personalScore ?></span><i></i>
            </td>
            <td width="336">
                <?php if($v->isUploadAnswer==1) {?>  <a href="<?php echo url('teacher/exam/answer-preview',array('answerID'=>$v->answerID))?>"> 查看答案</a>
                <?php }?>
            </td>
        </tr>
    <?php } else { ?>
        <tr style="display: none">
            <td width="133"><?php echo $v->studentName ?></td>
            <td width="166"><?php echo $v->stuID ?></td>
            <input type="hidden" value="<?php echo $v->examSubID ?>">
            <td width="174"><span examSubID="<?php echo $v->examSubID ?>"
                                  studentID="<?php echo $v->studentID ?>"><?php echo $v->personalScore ?></span><i></i>
            </td>
            <td width="336">
             <?php if($v->isUploadAnswer==1) {?>  <a href="<?php echo url('teacher/exam/answer-preview',array('answerID'=>$v->answerID))?>"> 查看答案</a>
            <?php }?>
            </td>
        </tr>
    <?php }
} ?>

