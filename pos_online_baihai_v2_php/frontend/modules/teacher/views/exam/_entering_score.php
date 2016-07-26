<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-20
 * Time: 下午2:25
 */
?>
<div class="impBox">
    <ul class="form_list">

        <li>
            <div class="formL">
                <label><i></i>考试名称：</label>
            </div>
            <div class="formR">
                <span><?php echo $data->examName?></span>
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>考试科目：</label>
            </div>
            <div class="formR">
                <span><?php echo $data->subjectName?></span>
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>考试成绩：</label>
            </div>
            <div class="formR overflow">
                <form id="scoreEntryForm"
                      action="<?php echo \yii\helpers\Url::to(['score-entry','examSubID' => $examSubID]) ?>">
                    <table cellpadding="0" class="tab" cellspacing="0" width="350">
                        <tbody>
                        <tr height="38">
                            <td width="94">姓名</td>
                            <td width="94">成绩</td>
                            <td width="94">姓名</td>
                            <td width="94">成绩</td>
                        </tr>
                        <?php for ($k = 0; $k < count($classMembers); $k = $k + 2) { ?>
                            <tr height="38">
                                <?php if (($k + 1) % 2 == 1 && isset($classMembers[$k])) { ?>
                                    <td><?php echo $classMembers[$k]->memName ?></td>
                                    <input type="hidden" name="entry[<?php echo $k ?>][studentID]"
                                           value="<?php echo $classMembers[$k]->userID ?>">
                                    <td><input type="text" class="text" name="entry[<?php echo $k ?>][personalScore]">
                                    </td>
                                <?php } ?>
                                <?php if (($k + 2) % 2 == 0 && isset($classMembers[$k + 1])) { ?>
                                    <td><?php echo $classMembers[$k + 1]->memName ?></td>
                                    <input type="hidden" name="entry[<?php echo $k + 1 ?>][studentID]"
                                           value="<?php echo $classMembers[$k + 1]->userID ?>">
                                    <td><input type="text" class="text"
                                               name="entry[<?php echo $k + 1 ?>][personalScore]"></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </tbody>

                    </table>

                </form>
            </div>
        </li>

    </ul>
</div>