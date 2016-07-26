<div id="srchResult">
    <?php if (isset($data)): ?>
        <?php foreach ($data as $v): ?>
            <dl class="sc_guide_list clearfix">
                <dt><a href="#"><img src="<?php echo publicResources() ?>/images/pic.png" alt=""></a>
                </dt>
                <dd><h5><a href="<?php echo url('school/brief-view', array('id' => $v->briefID, 'schoolId' =>$pages->params['schoolId']))?>" class="Study">[<i><?php echo SchoolLevelModel::model()->getSchoolLevelhName($v->department)?></i>]&nbsp;&nbsp;<?php echo $v->briefName ?></a>
                        <?php if(loginUser()->getTeacherInSchool($pages->params['schoolId'])):?>
                        <a href="<?php echo url('school/brief-update', array('id' => $v->briefID, 'schoolId' => $pages->params['schoolId']))?>" class="edit">编辑</a></h5></dd>
                        <?php endif;?>

                <dd><i><?php echo $v->nameOfCreator?></i><i><?php echo $v->createTime?></i></dd>
                <dd><span>摘要：</span><em><?php echo mb_substr(strip_tags($v->detailOfBrief), 0, 200, 'utf-8') ?></em>
                </dd>

            </dl>
        <?php endforeach; ?>
    <?php else: ?>
        <dl>
            没有数据
        </dl>
    <?php endif; ?>
        <?php
         echo \frontend\components\CLinkPagerExt::widget( array(

               'pagination'=>$pages,
                'updateId' => '#srchResult',
                'maxButtonCount' => 5
            )
        );
        ?>

</div>