<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/7
 * Time: 14:36
 */
use yii\helpers\Url;

?>
<button id="sch_mag_classesBar_btn" type="button" class="bg_white icoBtn_wait"><i></i>更换</button>
<div id="sch_mag_homes" class="sch_mag_homes pop">
    <dl>
        <dt class="schoolLevel cur">
            <a href="<?php echo Url::to(['default/index','classId'=>$classId])?>">成绩统计</a>
        </dt>
        <dd data-sel-item class="sel_ac"></dd>
        <dd data-sel-item></dd>
    </dl>
    <dl>
        <dt class="schoolLevel cur">
            <a href="<?php echo Url::to(['homeworkexcellentrate/index','classId'=>$classId])?>">作业统计</a>
        </dt>
        <dd data-sel-item class="sel_ac"></dd>
        <dd data-sel-item></dd>
    </dl>
    <dl>
        <dt class="schoolLevel cur">
            <a href="<?php echo Url::to(['classshortboard/index','classId'=>$classId])?>">短板池</a>
        </dt>
        <dd data-sel-item class="sel_ac"></dd>
        <dd data-sel-item></dd>
    </dl>
</div>
