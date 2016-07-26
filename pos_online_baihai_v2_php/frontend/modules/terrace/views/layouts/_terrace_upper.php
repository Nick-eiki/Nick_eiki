<div class="mainNav grid_24 terrace_Nav">
    <ul class="terrace_nav">
        <li class="<?php echo $this->context->highLightUrl('ku/questions/index') ? 'terrace_nav_this' : '' ?>"><a
                href="<?php echo url('ku/questions/index'); ?>">首页</a></li>
        <li class="<?php echo $this->context->highLightUrl('ku/questions/small-school') ? 'terrace_nav_this' : '' ?>"><a
                href="<?php echo url('ku/questions/small-school') ?>">小学</a></li>
        <li class="<?php echo $this->context->highLightUrl('ku/questions/middle-school') ? 'terrace_nav_this' : '' ?>"><a
                href="<?php echo url('ku/questions/middle-school') ?>">初中</a></li>
        <li class="<?php echo $this->context->highLightUrl('ku/questions/high-school') ? 'terrace_nav_this' : '' ?>"><a
                href="<?php echo url('ku/questions/high-school') ?>">高中</a></li>
    </ul>
    <ul class="mainNav_R clearfix">
        <li><i class="set"></i><span class="bColor setJs"><a href="<?php echo $this->getSetHoneUrl() ?>">个人设置</a></span>
        </li>
        <li><i class="dressUp"></i><span class="dress_k">装扮空间</span></li>
        <li><i class="management"></i><span><a href="<?php echo $this->getManageHoneUrl() ?>">个人管理中心</a></span></li>
    </ul>
</div>
