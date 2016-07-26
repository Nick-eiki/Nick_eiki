<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-8
 * Time: 下午2:02
 */
?>
<div class="centLeft dataStore">
    <div class="data_title pr">
        <h3>资料列表</h3>
        <div class="fr">
            <a href="<?php echo url('teacher/briefcase/upload-pack',array('id'=>$id))?>" class="B_btn120 uploadDataBtn">上传资料</a>&nbsp;&nbsp;
            <!--                <a href="javascript:;" class="B_btn120 uploadVideoBtn">上传视频</a>-->
        </div>
    </div>
    <div id="folder">
        <?php echo $this->render('_folder_list_view',array('model'=>$model, 'pages' => $pages,'teacherId'=>$teacherId,'userId'=>$userId));?>
    </div>
</div>
<div class="centRight">
    <div class="item Ta_teacher">
        <h4>我的老师</h4>
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