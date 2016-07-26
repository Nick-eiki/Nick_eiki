<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-8
 * Time: 下午2:58
 */
?>
<div class="centLeft bookmark">

    <div class="data_title pr">
        <h3>我的收藏</h3>
        <div>
            <label>资料类型：</label>
            <?php
            echo Html:: dropDownList('type','', array('1'=>'教案', '2'=>'讲义', '3'=>'视频'),
                array("prompt" => "请选择")
            );
            ?>
        </div>
    </div>

    <div id="collection">
        <?php  echo $this->render('_site_view', array('model'=>$model,'pages'=>$pages,'teacherId'=>$teacherId));?>
    </div>

</div>
<div class="centRight">
    <div class="item Ta_teacher">
        <h4>我的老师</h4>
        <a class="more" href="#">更多</a>
        <ul class="teacherList">
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
        </ul>

    </div>
</div>