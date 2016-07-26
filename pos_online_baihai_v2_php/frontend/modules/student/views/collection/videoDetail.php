<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-12
 * Time: 上午11:41
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="学生设置-收藏详情";
?>

<!--主体内容开始-->

    <div class="currentRight grid_16 push_2 stu_Detail_div stu_vo">
        <h3>视频名称</h3>
        <hr/>

        <div class="wd_details">


            <h4><?php  echo $model->videoName;?></h4>
            <ul class="wd_keywords_list clearfix">
                <li>
                    <p><?php echo $model->subjectName;?></p>
                </li>
                <li>
                    <p><?php echo $model->gradeName;?></p>
                </li>
                <li>
                    <p><?php echo $model->versionName;?></p>
                </li>
                <li class="wd_source">
                    <p class="sou_btn"><a href="<?php echo url('school/index',array('schoolId'=>$model->schoolID))?>"><?php echo $model->schoolName;?></a></p>
                </li>
            </ul>
            <ul class="wd_introduce_list ">
                <li><em>适用于:</em><?php echo AreaHelper::getAreaName($model->provience);?> &nbsp;<?php echo AreaHelper::getAreaName($model->city);?>&nbsp;<?php echo AreaHelper::getAreaName($model->country);?></li>
                <li><em>视频介绍：</em><?php echo $model->introduce;?></li>
                <li>
                    <h5>课时安排：</h5>
                    <?php foreach($model->lessoninfo as $key=>$item){?>

                        <dl class="clearfix width" >
                            <dt>第<?php echo $item->cNum;?>节</dt>
                            <dd>
                                <img src="<?php echo publicResources()?>/images/video.png" alt="课时视频"/>
<!--                                <img src="--><?php //echo publicResources().$item->videoUrl;?><!--" alt="课时视频"/>-->
                                <p class="dd_voName fl"><?php echo $item->cName;?></p>
                                <p class="fl"><?php  if(isset($item->kcid)){
                                        if($item->type==0){
                                            ?>知识难点：<?php
                                            foreach(KnowledgePointModel::findKnowledge($item->kcid) as $key=>$val){
                                               echo $val->name."&nbsp;";
                                        }
                                        }else{ ?>章节难点：<?php
                                            foreach(ChapterInfoModel::findChapter($item->kcid) as $key=>$val){
                                                echo $val->chaptername."&nbsp;";
                                          }
                                        }
                                    } ?>
                                </p>
                                <p class="fl"><a href="javascript:" id="<?php echo $item->teachMaterialID;?>">讲义名称:<?php echo $item->teachMaterialName;?></a>
                                    <button class="btn" type="button"></button>
                                </p>
                            </dd>
                        </dl>

						<div class="video" style="margin-top: 10px">
							<video  controls="controls">
								<source src="<?php echo $item->videoUrl;?>" >
							</video>
						</div>
                    <?php } ?>

                </li>
            </ul>

        </div>
    </div>

<!--主体内容结束-->
