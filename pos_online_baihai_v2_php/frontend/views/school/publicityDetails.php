<?php
/**
 * Created by PHPstorm
 * User: mahongru
 * Date: 15-7-7
 * Time: 下午18:35
 */
use frontend\components\helper\StringHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */  $this->title='学校-公示详情';
?>
				<div class="main_cont sch_public_details">
					<div class="title">
                        <a class="txtBtn backBtn" href="<?=Url::to(['school/publicity','schoolId'=>app()->request->getQueryParam('schoolId')])?>"></a>
						<h4>公示详情</h4>
                        <?php if(loginUser()->isTeacher()){?>
						<div class="title_r">
							<a href="<?=url('school/update-publicity',array('schoolId'=>app()->request->getParam('schoolId'),'publicityId'=>app()->request->getParam('publicityId')))?>" class="btn w120 bg_green btn40">修改</a>
							<!--判断当前用户是谁，我要提问按钮便链接到当前用户个人管理中心的提问页面-->
						</div>
                        <?php }?>
					</div>


                    <h5 class="public_details_title"><?=Html::encode($quData->publicityTitle)?></h5>
                    <p class="tc subTitle"><span>教师：<?=$quData->userName?></span><span>时间：<?=date("Y-m-d H:i",($quData->updateTime)/1000)?></span>
                        </p>
                    <div class="public_details_cont" >
                        <p><?=StringHelper::translateSpace(Html::encode($quData->publicityContent))?></p>
                        <?php if(!empty($quData->imageUrl)) : ?>
                        <?php $imgArr=explode(",",$quData->imageUrl); foreach($imgArr as $v){?>
                            <img src="<?=$v?>"/>
                        <?php }?>
                        <?php endif; ?>

                    </div>
					<div class="public_details_Up_down ">
						<span class="public_details_Up fl">
							上一篇：
                            <?php if(empty($lastData)){?>
                                <a href="javascript:;">没有了</a>
                            <?php }else{?>
                                <a href="<?=url('school/publicity-details',array('schoolId'=>app()->request->getParam('schoolId'),'publicityId'=>$lastData->publicityId))?>"><?=cut_str(Html::encode($lastData->publicityTitle),10)?></a>
                            <?php }?>
						</span>
						<span class="public_details_down fr">
							下一篇：
                             <?php if(empty($nextData)){?>
                                 <a href="javascript:;">没有了</a>
                             <?php }else{?>
                                 <a href="<?=url('school/publicity-details',array('schoolId'=>app()->request->getParam('schoolId'),'publicityId'=>$nextData->publicityId))?>"><?=cut_str(Html::encode($nextData->publicityTitle),10)?></a>
                             <?php }?>

						</span>
					</div>
				</div>
					
			</div>
		</div>
