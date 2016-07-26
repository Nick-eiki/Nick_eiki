<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-9
 * Time: 上午11:31
 */
?>
<div class="centLeft">

    <div class="data_title pr">
        <h3>Ta的收藏</h3>
    </div>
    <div id="collection">
        <?php  echo $this->render('_student_site_view', array('model'=>$model,'pages'=>$pages,'studentId'=>$studentId));?>
    </div>

</div>
<div class="centRight">
    <div class="item Ta_teacher">
        <h4>Ta的老师</h4>
        <a class="more" href="#">更多</a>
        <ul class="teacherList">
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
        </ul>


    </div>
</div>