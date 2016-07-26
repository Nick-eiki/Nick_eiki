<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/8/19
 * Time: 15:35
 */
use frontend\components\helper\ViewHelper;

$this->title='积分收入明细';
?>
<div class="score_table">
    <table cellpadding="0" cellspacing="0" border="0" style=" border-collapse: inherit">
        <?php if($model):?>
            <tr>
                <th width="148px">日期</th>
                <th width="146px">获得积分</th>
                <th width="350px">内容</th>
                <th>累计积分</th>
            </tr>

            <?php foreach($model as $val){?>
                <tr>
                    <td><?php if(!empty($val->createTime)){echo date('Y-m-d H:i',strtotime($val->createTime));}?></td>
                    <td><?=$val->points?></td>
                    <td><?=$val->memo?></td>
                    <td><span><?=$val->total?></span></td>
                </tr>
            <?php }
        else:
            ViewHelper::emptyView();
        endif;
        ?>
    </table>
</div>
<br>
<?php

if(isset($pages)){
    echo \frontend\components\CLinkPagerExt::widget( array(
            'pagination'=>$pages,
            'updateId' => '#update',
            'maxButtonCount' => 5,

        )
    );
}
?>