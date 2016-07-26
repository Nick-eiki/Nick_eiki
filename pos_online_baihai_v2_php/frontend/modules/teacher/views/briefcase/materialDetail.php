<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-1-20
 * Time: 上午10:00
 */
/* @var $this yii\web\View */  $this->title="教师-备课--素材库--素材详情";
?>


<!--主体内容开始-->

    <div class="currentRight grid_16 push_2">
        <div class="crumbs"><a href="#">教师</a> &gt;&gt; <a href="<?php echo url('teacher/briefcase/data-list')?>">素材库</a> &gt;&gt; 素材详情</div>
        <div class="noticeH clearfix">
            <h3 class="h3L">素材名称</h3>
        </div>
        <hr>
        <div class="docBag_upload_data">
            <div class="data_details details_d data_material">
                <h4><?php echo $model->name;?></h4>
                <div class="material_div">
                    <em>素材介绍：</em>
                    <p><?php echo $model->matDescribe;?></p>
                </div>
                <div class="material_player">
                    素材播放器

                    视具体情况选择性使用播放器：

                    1、图片：图片轮播

                    2、swf：就直接播放

                    3、pdf：应该也能直接播放吧，或者增加一个按钮“阅读素材”跳转到pdf播放器

                    4、ppt和doc：文件播放器
                </div>
                <button type="button" class="bg_blue_l">下载素材<span>(<i>430</i>)</span></button>
            </div>
        </div>
    </div>

<!--主体内容结束-->

