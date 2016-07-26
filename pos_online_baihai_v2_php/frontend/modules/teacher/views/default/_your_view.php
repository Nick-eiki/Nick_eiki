<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-8
 * Time: 下午12:10
 */
?>
<div class="centLeft dataStore">
    <div class="data_title pr">
        <h3>我的资料</h3>
        <div class="fr">
            <a href="javascript:" class="B_btn120">添加资料袋</a>
        </div>
    </div>

    <hr>
    <div class="docPack pr">
        <ul class="docBagList">
            <?php foreach($material as $key=>$item){

                ?>
                <li>

                        <button type="button" title="编辑" dataId="<?php echo $item->ID;?>"></button>


                    <h5><a href="<?php echo url('teacher/default/details-list',array('id'=>$item->ID,'teacherId'=>$teacherId))?>"><?php echo cut_str($item->Name,12);?></a></h5>
                    <p><em>可见:<?php
                            if($item->departmentMemLimit){?>
                                所有人可见
                            <?php  }else{ ?>
                                所有人不可见
                         <?php   } ?>
                    </p>
                    <p>
                        <?php foreach($item->cntLst as $key=>$val){?>
                            <?php echo $val->typeName;?>:<?php echo $val->cnt;?>
                        <?php }?>
                    </p>
                </li>
            <?php   } ?>
        </ul>
            <?php
             echo \frontend\components\CLinkPagerExt::widget( array(
                   'pagination'=>$pages,
//                        'updateId'=>'#collection',
                    'maxButtonCount' => 5
                )
            );
            ?>
    </div>
</div>
<div class="centRight">
    <div class="item Ta_teacher">
        <h4>我的老师</h4>
        <a class="more" href="#">更多</a>
        <ul class="teacherList">
            <li> <img src="../images/user_m.jpg"> 张三丰 </li>
            <li> <img src="../images/user_m.jpg"> 张三丰 </li>
            <li> <img src="../images/user_m.jpg"> 张三丰 </li>
            <li> <img src="../images/user_m.jpg"> 张三丰 </li>
            <li> <img src="../images/user_m.jpg"> 张三丰 </li>
            <li> <img src="../images/user_m.jpg"> 张三丰 </li>
            <li> <img src="../images/user_m.jpg"> 张三丰 </li>
            <li> <img src="../images/user_m.jpg"> 张三丰 </li>
            <li> <img src="../images/user_m.jpg"> 张三丰 </li>
        </ul>
    </div>
</div>


