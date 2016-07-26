<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/8/19
 * Time: 16:38
 */
use yii\helpers\Url;

/* @var $this yii\web\View */


$this->title='我的等级';
?>
<div class="grid_19 main_r mainbox_r">
    <div class="main_cont score_library">
        <div class="title">
            <h4>我的积分</h4>
        </div>
        <div class="form_list no_padding_form_list">
            <div class="row">
                <div class="formR">
                    <ul class="resultList">
                        <?php if($user->type==1){?>
                            <li ><a href="<?=Url::to(['/teacher/integral/income-details'])?>">收入明细</a></li>
                            <li class="ac"><a href="<?=Url::to(['/teacher/integral/my-ranking'])?>">我的等级</a></li>
                            <li ><a href="<?=Url(['/teacher/integral/integral-exchange'])?>">积分兑换</a></li>
                        <?php }else{?>
                            <li ><a href="<?=Url::to(['/student/integral/income-details'])?>">收入明细</a></li>
                            <li class="ac"><a href="<?=Url::to(['/student/integral/my-ranking'])?>">我的等级</a></li>
                            <li ><a href="<?=Url(['/student/integral/integral-exchange'])?>">积分兑换</a></li>
                        <?php } ?>

                    </ul>
                </div>
            </div>
        </div>
        <div class="tab my_pointsbox">
<!--            <div class="title item_title noBorder score_title">-->
<!--                <a href="#" class="view_link">查看等级特权</a>-->
<!--            </div>-->
            <ul class="grade_box">
                <li>
                    我的等级：<span><?=$grad->gradeName?></span>
                </li>

            </ul>
        </div>
        <div class="score_rule_box">
            <div class="score_table">
                <table cellpadding="0" cellspacing="0" border="0">
                    <?php if($user->type==1){?>
                        <tr>
                            <th width="225px">等级</th>
                            <th width="225px">分数</th>
                        </tr>
                        <tr>
                            <td width="225px">翰林院编修</td>
                            <td width="225px">0-300</td>
                        </tr>
                        <tr>
                            <td width="225px">翰林院编撰</td>
                            <td width="225px">301-800</td>
                        </tr>
                        <tr>
                            <td width="225px">国子监司业</td>
                            <td width="225px">801-1600</td>
                        </tr>
                        <tr>
                            <td width="225px">侍讲学士</td>
                            <td width="225px">1601-3500</td>
                        </tr>
                        <tr>
                            <td width="225px">内阁学士</td>
                            <td width="225px">3501-7000</td>
                        </tr>
                        <tr>
                            <td width="225px">少师</td>
                            <td width="225px">7001-15000</td>
                        </tr>
                        <tr>
                            <td width="225px">太师</td>
                            <td width="225px">15000以上</td>
                        </tr>
                    <?php }else{?>
                        <tr>
                            <th width="225px">等级</th>
                            <th width="225px">分数</th>
                        </tr>
                        <tr>
                            <td width="225px">童生</td>
                            <td width="225px">0-100</td>
                        </tr>
                        <tr>
                            <td width="225px">秀才</td>
                            <td width="225px">101-300</td>
                        </tr>
                        <tr>
                            <td width="225px">举人</td>
                            <td width="225px">301-800</td>
                        </tr>
                        <tr>
                            <td width="225px">贡生</td>
                            <td width="225px">801-1500</td>
                        </tr>
                        <tr>
                            <td width="225px">探花</td>
                            <td width="225px">1501-3000</td>
                        </tr>
                        <tr>
                            <td width="225px">榜眼</td>
                            <td width="225px">3001-5000</td>
                        </tr>
                        <tr>
                            <td width="225px">状元</td>
                            <td width="225px">5000以上</td>
                        </tr>
                    <?php } ?>

                </table>
            </div>
        </div>
    </div>
</div>