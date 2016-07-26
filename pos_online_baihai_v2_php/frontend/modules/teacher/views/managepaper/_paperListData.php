
<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 14-11-4
 * Time: 下午5:12
 */
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\SubjectModel;

?>

<div id="srchResult">
    <ul class="uploaded_list clearfix">
        <?php foreach($data as $v):
			if($v->getType == 0){
				if(isset($v->imageUrls) && $v->imageUrls != null){
					$image=$v->imageUrls[0]->url;
				}
			}elseif($v->getType == 1){
				$image="/images/iocPic2.png";
			} ?>
            <li class="pr"><img src="<?php echo  publicResources().$image?>" alt="上传视频图片"/>
                <h4>[<i><?php echo GradeModel::model()->getGradeName($v->gradeId)?> <?php echo SubjectModel::model()->getSubjectName($v->subjectId)?></i>]</h4>
                <a href="<?php echo url('teacher/managepaper/paper-detail',array('paperId'=>$v->paperId,'getType'=>$v->getType))?>" class="paper_name"><?php echo $v->name?></a>

                <p>简介： <?php echo $v->paperDescribe?></p>

                <p>上传时间：<?php echo $v->uploadTime?></p>
                <button class="bg_red delBtn" onclick="deletePaper(this)" paperId="<?php echo $v->paperId?>">删除试卷</button>
            </li>
        <?php endforeach;?>
        <?php echo empty($data)?'没有数据':''?>
    </ul>
        <?php
         echo \frontend\components\CLinkPagerExt::widget( array(
               'pagination'=>$pages,
                'updateId' => '#srchResult',
                'maxButtonCount' => 5
            )
        );
        ?>
</div>