<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-2
 * Time: 下午3:51
 */
/* @var $this yii\web\View */  $this->title='学校-修改头像';
$backend_asset = publicResources();
$this->registerJsFile($backend_asset . '/js/jquery.ui.widget.js');
?>
<!--主体内容开始-->
        <div class="main_c clearfix" style="padding-bottom:50px;">
            <div class="centLeft cp_email_cl">
                <h3>头像管理</h3>
                <hr/>
                <br>
                <form>
                    <ul class="form_list  organization">
                        <li>
                            <div class="formL"> </div>
                            <div class="formR">
                                <div class="imgSize"> <img id="face-img" src="<?php echo publicResources().$schoolModel->logoUrl =='' ? '/images/tx.jpg': $schoolModel->logoUrl;?>" height="140" width="140" ></div>
                                <div class="up_pic"> <span id="uploadPicBtn">修改头像</span> <em>支持文件不大于2M的jpg、gif、png格式的图片。</em> </div>
                                <input class="faceIcon" type="hidden"/>
                            </div>
                        </li>
                        <li>
                            <div class="formL"><label></label></div>
                            <div class="formR">
                                <button type="button" style="display:none" class="bg_red_d w120 save" id="save">保&nbsp;&nbsp;存</button>
                            </div>
                        </li>
                    </ul>
                </form>
            </div>

        </div>


<!--主体内容结束-->
<?php echo $this->render("//school/_faceUpload") ?>
<script>
    $(function () {
        $(".save").click(function () {
            var schoolId = "<?php echo $schoolId;?>";
            var headImgUrl = $(".faceIcon").val();
            var url = "<?php echo url('school/update-school-pic')?>";
            $.post(url, {headImgUrl: headImgUrl,schoolId:schoolId}, function (result) {
                if (result.success) {
                    popBox.alertBox("修改成功");
                    location.reload();
                }
                else {
                    popBox.alertBox("修改失败");
                }
            })
        })
    })
</script>